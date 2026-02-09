<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\CRM\Models\Contact;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\InvoiceItem;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\FiscalPeriod;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $contacts = Contact::where('company_id', auth()->user()->company_id)
            ->where('type', 'customer')
            ->get();

        return view('sales::pos.index', compact('contacts'));
    }

    public function products(Request $request)
    {
        $query = Product::where('company_id', auth()->user()->company_id);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && in_array($request->type, ['good', 'service'])) {
            $query->where('type', $request->type);
        }

        return response()->json($query->take(50)->get());
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.discount_rate' => 'nullable|numeric|min:0|max:100',
            'contact_id' => 'nullable|exists:contacts,id',
            'payment_method' => 'required|string',
            'received_amount' => 'nullable|numeric'
        ]);

        return DB::transaction(function () use ($request) {
            $companyId = auth()->user()->company_id;
            
            // Get open fiscal period
            $fiscalPeriod = FiscalPeriod::where('company_id', $companyId)
                ->where('status', 'open')
                ->first();

            if (!$fiscalPeriod) {
                return response()->json(['message' => 'Açık bir mali dönem bulunamadı.'], 422);
            }

            $subtotalAmount = 0;
            $totalTaxAmount = 0;
            $totalDiscountAmount = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $lineBase = $item['quantity'] * $product->sale_price;
                $lineDiscount = $lineBase * (($item['discount_rate'] ?? 0) / 100);
                $lineSubAfterDisc = $lineBase - $lineDiscount;
                $lineTax = $lineSubAfterDisc * ($product->vat_rate / 100);
                
                $subtotalAmount += $lineBase;
                $totalDiscountAmount += $lineDiscount;
                $totalTaxAmount += $lineTax;
            }

            $grandTotal = $subtotalAmount - $totalDiscountAmount + $totalTaxAmount;

            // Create Invoice
            $invoice = Invoice::create([
                'company_id' => $companyId,
                'contact_id' => $request->contact_id,
                'invoice_number' => 'POS-' . now()->format('YmdHis'),
                'issue_date' => now(),
                'due_date' => now(),
                'total_amount' => $grandTotal,
                'tax_amount' => $totalTaxAmount,
                'status' => 'paid',
                'notes' => 'POS Satışı - Yöntem: ' . strtoupper($request->payment_method) . 
                          ($request->received_amount ? ' - Alınan: ' . $request->received_amount : '')
            ]);

            // Create Invoice Items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $lineBase = $item['quantity'] * $product->sale_price;
                $lineDiscount = $lineBase * (($item['discount_rate'] ?? 0) / 100);
                $lineSubAfterDisc = $lineBase - $lineDiscount;
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'description' => $product->name . ($item['discount_rate'] > 0 ? " (%{$item['discount_rate']} İndirim)" : ""),
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->sale_price,
                    'tax_rate' => $product->vat_rate,
                    'total' => $lineSubAfterDisc * (1 + ($product->vat_rate / 100))
                ]);

                // Stock Movement
                if ($product->stock_track && $product->type === 'good') {
                    $warehouse = \Modules\Inventory\Models\Warehouse::first();
                    if ($warehouse) {
                        \Modules\Inventory\Models\StockMovement::create([
                            'company_id' => $companyId,
                            'product_id' => $product->id,
                            'warehouse_id' => $warehouse->id,
                            'quantity' => -$item['quantity'],
                            'type' => 'sale',
                            'reference' => 'POS-' . $invoice->id
                        ]);
                    }
                }
            }

            // Create Transaction
            $transaction = Transaction::create([
                'company_id' => $companyId,
                'fiscal_period_id' => $fiscalPeriod->id,
                'type' => 'regular',
                'receipt_number' => $invoice->invoice_number,
                'date' => now(),
                'description' => 'POS Satışı (' . strtoupper($request->payment_method) . '): ' . $invoice->invoice_number,
                'is_approved' => true
            ]);

            $invoice->update(['transaction_id' => $transaction->id]);

            return response()->json([
                'success' => true,
                'message' => 'Satış başarıyla tamamlandı.',
                'invoice_id' => $invoice->id
            ]);
        });
    }
}
