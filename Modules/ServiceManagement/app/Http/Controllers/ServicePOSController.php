<?php

namespace Modules\ServiceManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\CRM\Models\Contact;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\InvoiceItem;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\FiscalPeriod;
use Modules\ServiceManagement\Models\Vehicle;
use Modules\ServiceManagement\Models\ServiceRecord;
use Illuminate\Support\Facades\DB;

class ServicePOSController extends Controller
{
    public function index()
    {
        $contacts = Contact::where('company_id', auth()->user()->company_id)
            ->where('type', 'customer')
            ->get();

        return view('servicemanagement::pos.index', compact('contacts'));
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
            $type = $request->type;
            $query->whereHas('productType', function ($pq) use ($type) {
                $pq->where('code', $type);
            });
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
            'received_amount' => 'nullable|numeric',
            'plate_number' => 'nullable|string|max:20',
            'current_mileage' => 'nullable|integer|min:0'
        ]);

        return DB::transaction(function () use ($request) {
            $companyId = auth()->user()->company_id;
            $contactId = $request->contact_id;

            if (!$contactId) {
                $defaultContact = Contact::where('company_id', $companyId)
                    ->where('type', 'customer')
                    ->first();
                
                if ($defaultContact) {
                    $contactId = $defaultContact->id;
                } else {
                    return response()->json(['message' => 'Lütfen bir müşteri seçin veya sistemde en az bir müşteri kaydı olduğundan emin olun.'], 422);
                }
            }
            
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
                'contact_id' => $contactId,
                'invoice_number' => 'S-POS-' . now()->format('YmdHis'),
                'plate_number' => $request->plate_number,
                'issue_date' => now(),
                'due_date' => now(),
                'total_amount' => $grandTotal,
                'tax_amount' => $totalTaxAmount,
                'status' => 'paid',
                'notes' => 'Servis POS Satışı - Yöntem: ' . strtoupper($request->payment_method) . 
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
                            'reference' => 'S-POS-' . $invoice->id
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
                'description' => 'Servis POS Satışı (' . strtoupper($request->payment_method) . '): ' . $invoice->invoice_number,
                'is_approved' => true
            ]);

            $invoice->update(['transaction_id' => $transaction->id]);

            // Create Service Record linkage if plate number is provided
            if ($request->plate_number) {
                $normalizedPlate = strtoupper(str_replace(' ', '', $request->plate_number));
                
                // Find or create vehicle
                $vehicle = Vehicle::firstOrCreate(
                    [
                        'company_id' => $companyId,
                        'plate_number' => $normalizedPlate
                    ],
                    [
                        'customer_id' => $contactId,
                        'brand' => 'Bilinmiyor',
                        'model' => 'Bilinmiyor',
                        'current_mileage' => $request->current_mileage ?? 0,
                        'status' => 'active'
                    ]
                );

                // Update vehicle mileage if provided and higher
                if ($request->filled('current_mileage') && $request->current_mileage > $vehicle->current_mileage) {
                    $vehicle->update(['current_mileage' => $request->current_mileage]);
                }

                // Update vehicle customer if it was found but belongs to a different contact
                if ($vehicle->customer_id != $contactId) {
                    $vehicle->update(['customer_id' => $contactId]);
                }

                // Create Service Record
                $itemNames = collect($request->items)->map(function($item) {
                    return Product::find($item['product_id'])->name . " (x" . $item['quantity'] . ")";
                })->implode(', ');

                ServiceRecord::create([
                    'company_id' => $companyId,
                    'vehicle_id' => $vehicle->id,
                    'service_type' => 'Hızlı Satış',
                    'service_date' => now(),
                    'mileage_at_service' => $request->current_mileage ?? $vehicle->current_mileage ?? 0,
                    'description' => 'Servis POS üzerinden otomatik oluşturuldu. Ürünler: ' . $itemNames,
                    'total_cost' => $grandTotal,
                    'status' => 'completed',
                    'completed_at' => now(),
                    'performed_by' => auth()->user()->name
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Servis satışı başarıyla tamamlandı.',
                'invoice_id' => $invoice->id
            ]);
        });
    }
}
