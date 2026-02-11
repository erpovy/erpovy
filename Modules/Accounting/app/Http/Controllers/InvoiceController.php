<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\InvoiceItem;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\FiscalPeriod;
use Modules\Accounting\Models\LedgerEntry;
use Modules\Accounting\Models\VatRate;
use Modules\Accounting\Services\AccountTransactionService;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Warehouse;
use Illuminate\Support\Facades\DB;
// DomPDF facade will be checked dynamically

class InvoiceController extends Controller
{
    protected $accountTransactionService;

    public function __construct(AccountTransactionService $accountTransactionService)
    {
        $this->accountTransactionService = $accountTransactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function downloadPdf(Invoice $invoice)
    {
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return back()->with('error', 'PDF kütüphanesi sistemde yüklü değil.');
        }

        // GD Extension Check
        if (!extension_loaded('gd')) {
            // Log warning but try to proceed, or return error if critical
            // return back()->with('error', 'PHP GD eklentisi yüklü değil. PDF oluşturulamaz.');
        }

        // 1. Varsayılan Şablonu Bul
        $template = \Modules\Accounting\Models\InvoiceTemplate::where('company_id', auth()->user()->company_id)
            ->where('is_default', true)
            ->first();

        if (!$template) {
            $template = \Modules\Accounting\Models\InvoiceTemplate::where('company_id', auth()->user()->company_id)->first();
        }

        // Clear any previous output (buffers, warnings) to prevent PDF corruption
        if (ob_get_length()) {
            ob_end_clean();
        }

        // 3. Şablon yoksa varsayılan view
        if (!$template) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('accounting::invoices.pdf', compact('invoice'));
            return $pdf->stream('fatura-' . $invoice->invoice_number . '.pdf');
        }

        // 4. Dinamik Şablon
        try {
            $html = \Illuminate\Support\Facades\Blade::render($template->html_content, ['invoice' => $invoice]);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            return $pdf->stream('fatura-' . $invoice->invoice_number . '.pdf');
        } catch (\Exception $e) {
             return back()->with('error', 'PDF hatası: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = Invoice::with('contact');

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('contact', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest('issue_date')->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'total_count' => Invoice::count(),
            'total_amount' => Invoice::sum('total_amount'),
            'pending_count' => Invoice::where('status', '!=', 'paid')->count(),
            'monthly_amount' => Invoice::whereMonth('issue_date', now()->month)->sum('total_amount'),
        ];

        return view('accounting::invoices.index', compact('invoices', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contacts = Contact::where('type', 'customer')->orderBy('name')->get();
            $products = Product::where('is_active', true)->whereHas('productType', function($q) {
            $q->whereIn('code', ['good', 'service']);
        })->orderBy('name')->get();
        $vat_rates = VatRate::where('is_active', true)->orderBy('rate')->get();
        return view('accounting::invoices.create', compact('contacts', 'products', 'vat_rates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'invoice_type' => 'required|in:SATIS,IADE,TEVKIFAT,ISTISNA',
            'invoice_scenario' => 'required|in:EARSIV,KAGIT',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Calculate Totals
            $subtotal = 0;
            $vatTotal = 0;

            foreach ($validated['items'] as $item) {
                $lineAmount = $item['quantity'] * $item['unit_price'];
                $lineVat = $lineAmount * ($item['tax_rate'] / 100);
                
                $subtotal += $lineAmount;
                $vatTotal += $lineVat;
            }

            $grandTotal = $subtotal + $vatTotal;

            // 2. Create Invoice
            $invoice = Invoice::create([
                'company_id' => auth()->user()->company_id,
                'contact_id' => $validated['contact_id'],
                'invoice_type' => $validated['invoice_type'],
                'invoice_scenario' => $validated['invoice_scenario'],
                'invoice_number' => 'INV-' . date('Ymd') . '-' . rand(100, 999), 
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'grand_total' => $grandTotal,
                'total_amount' => $grandTotal, // Legacy
                'tax_amount' => $vatTotal, // Legacy
                'status' => 'sent',
            ]);

            // 3. Process Items & Inventory
            $warehouse = Warehouse::where('company_id', auth()->user()->company_id)->first();

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $lineTax = $lineTotal * ($item['tax_rate'] / 100);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'],
                    'total' => $lineTotal + $lineTax,
                ]);

                // Inventory Deduction
                if (!empty($item['product_id']) && $warehouse) {
                    $product = Product::find($item['product_id']);
                    if ($product && $product->stock_track) {
                        StockMovement::create([
                            'company_id' => auth()->user()->company_id,
                            'product_id' => $product->id,
                            'warehouse_id' => $warehouse->id,
                            'quantity' => -$item['quantity'], // Negative for sale
                            'type' => 'sale',
                            'reference' => 'Fatura #' . $invoice->invoice_number,
                        ]);
                    }
                }
            }

            // 4. Accounting Transaction (Auto-Journal)
            $fiscalPeriod = FiscalPeriod::where('company_id', auth()->user()->company_id)
                ->where('status', 'open')
                ->first();

            if (!$fiscalPeriod) {
                throw new \Exception('Aktif bir mali dönem bulunamadı. Lütfen mali yıl açılışını yapın.');
            }

            $transaction = Transaction::create([
                'company_id' => auth()->user()->company_id,
                'fiscal_period_id' => $fiscalPeriod->id,
                'type' => 'regular',
                'receipt_number' => 'FIS-' . strtoupper(uniqid()),
                'date' => $validated['issue_date'],
                'description' => 'Satış Faturası: ' . $invoice->invoice_number,
                'is_approved' => true,
            ]);

            // Entry 1: Debit Accounts Receivable (120)
            $debtorAccount = Account::where('company_id', auth()->user()->company_id)->where('code', '120')->first()
                             ?? Account::where('code', '120')->first();
            
            LedgerEntry::create([
                'company_id' => auth()->user()->company_id,
                'transaction_id' => $transaction->id,
                'account_id' => $debtorAccount->id,
                'debit' => $grandTotal,
                'credit' => 0,
                'description' => 'Fatura Toplam Tutarı',
            ]);

            // Entry 2: Credit Sales Revenue (600)
            $salesAccount = Account::where('company_id', auth()->user()->company_id)->where('code', '600')->first()
                             ?? Account::where('code', '600')->first();

            LedgerEntry::create([
                'company_id' => auth()->user()->company_id,
                'transaction_id' => $transaction->id,
                'account_id' => $salesAccount->id,
                'debit' => 0,
                'credit' => $subtotal,
                'description' => 'Net Satış Tutarı',
            ]);

            // Entry 3: Credit VAT (391) if exists
            if ($vatTotal > 0) { // Changed $totalTax to $vatTotal
                $vatAccount = Account::where('company_id', auth()->user()->company_id)->where('code', '391')->first()
                                 ?? Account::where('code', '391')->first();
                if ($vatAccount) {
                    LedgerEntry::create([
                        'company_id' => auth()->user()->company_id,
                        'transaction_id' => $transaction->id,
                        'account_id' => $vatAccount->id,
                        'debit' => 0,
                        'credit' => $vatTotal,
                        'description' => 'KDV Tutarı',
                    ]);
                }
            }

            $invoice->update(['transaction_id' => $transaction->id]);

            // Update Contact Balance (Simple MVP logic)
            $contact = Contact::find($validated['contact_id']);
            $contact->increment('current_balance', $grandTotal); // Changed $totalAmount to $grandTotal

            // 5. Cari Hesap Hareketi (Account Transaction)
            $this->accountTransactionService->syncFromInvoice($invoice);

            DB::commit();

            return redirect()->route('accounting.invoices.index')->with('success', 'Fatura başarıyla oluşturuldu.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Invoice Creation Failed: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            return back()->with('error', 'Hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['items.product', 'contact', 'transaction'])->findOrFail($id);
        return view('accounting::invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::with(['items'])->findOrFail($id);
        $contacts = Contact::all();
        $products = Product::where('is_active', true)->whereHas('productType', function($q) {
        $q->whereIn('code', ['good', 'service']);
    })->orderBy('name')->get();
        $vat_rates = VatRate::where('is_active', true)->orderBy('rate')->get();
        return view('accounting::invoices.edit', compact('invoice', 'contacts', 'products', 'vat_rates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Calculate New Totals
            $subtotal = 0;
            $vatTotal = 0;

            foreach ($validated['items'] as $item) {
                $lineAmount = $item['quantity'] * $item['unit_price'];
                $lineVat = $lineAmount * ($item['tax_rate'] / 100);
                
                $subtotal += $lineAmount;
                $vatTotal += $lineVat;
            }

            $grandTotal = $subtotal + $vatTotal;

            // 2. Update Invoice
            $invoice->update([
                'contact_id' => $validated['contact_id'],
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'grand_total' => $grandTotal,
                'total_amount' => $grandTotal, // Legacy
                'tax_amount' => $vatTotal, // Legacy
            ]);

            // 3. Update/Replace Items
            // For simplicity and correctness with inventory sync, we might need to be careful.
            // MVP Strategy: Delete old items and recreate new ones.
            // WARN: This affects inventory tracking if we don't reverse previous movements.
            
            // Reversing previous inventory movements
            foreach ($invoice->items as $oldItem) {
                if ($oldItem->product_id) {
                     // Reverse: If it was a sale (-10), we add back (+10)
                     // Implementation of specific reversal logic would be better, but "resetting" is safer for sync.
                     // TODO: Add refined inventory reversal logic later.
                }
            }
            
            $invoice->items()->delete();

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $lineTax = $lineTotal * ($item['tax_rate'] / 100);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'],
                    'total' => $lineTotal + $lineTax,
                ]);
            }
            
            // 4. Update Accounting Transaction (if exists)
            if ($invoice->transaction) {
                 $transaction = $invoice->transaction;
                 $transaction->update(['date' => $validated['issue_date']]);
                 
                 // Update Ledger Entries (Re-calculate amounts)
                 // This is complex, for now we might just update the description or date.
                 // Ideally, we should recalculate the journal entry amounts.
                 
                 // Re-creating ledger entries is safest.
                 $transaction->entries()->delete();
                 
                  // Entry 1: Debit Accounts Receivable (120)
                $debtorAccount = Account::where('company_id', auth()->user()->company_id)->where('code', '120')->first()
                                 ?? Account::where('code', '120')->first();
                
                LedgerEntry::create([
                    'company_id' => auth()->user()->company_id,
                    'transaction_id' => $transaction->id,
                    'account_id' => $debtorAccount->id,
                    'debit' => $grandTotal,
                    'credit' => 0,
                    'description' => 'Fatura Toplam Tutarı (Güncel)',
                ]);

                // Entry 2: Credit Sales Revenue (600)
                $salesAccount = Account::where('company_id', auth()->user()->company_id)->where('code', '600')->first()
                                 ?? Account::where('code', '600')->first();

                LedgerEntry::create([
                    'company_id' => auth()->user()->company_id,
                    'transaction_id' => $transaction->id,
                    'account_id' => $salesAccount->id,
                    'debit' => 0,
                    'credit' => $subtotal,
                    'description' => 'Net Satış Tutarı (Güncel)',
                ]);

                // Entry 3: Credit VAT (391) if exists
                if ($totalTax > 0) {
                    $vatAccount = Account::where('company_id', auth()->user()->company_id)->where('code', '391')->first()
                                     ?? Account::where('code', '391')->first();
                    if ($vatAccount) {
                        LedgerEntry::create([
                            'company_id' => auth()->user()->company_id,
                            'transaction_id' => $transaction->id,
                            'account_id' => $vatAccount->id,
                            'debit' => 0,
                            'credit' => $vatTotal,
                            'description' => 'KDV Tutarı (Güncel)',
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('accounting.invoices.show', $invoice->id)->with('success', 'Fatura başarıyla güncellendi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Güncelleme hatası: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Faturayı GİB'e gönder (Taslak).
     */
    public function sendToGib($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        try {
            // Service instance manually or via DI if registered
            $gibService = new \Modules\Accounting\Services\GibPortalService();
            $gibService->createDraft($invoice);
            
            return back()->with('success', 'Fatura başarıyla GİB e-Arşiv Portalına taslak olarak gönderildi.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('GİB Gönderim Hatası: ' . $e->getMessage());
            return back()->with('error', 'GİB Gönderim Hatası: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
