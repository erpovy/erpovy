<?php

namespace Modules\Purchasing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\Purchasing\Models\PurchaseOrder;
use Modules\Purchasing\Models\PurchaseOrderItem;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::where('company_id', auth()->user()->company_id)
            ->with('supplier')
            ->latest()
            ->get();
        return view('purchasing::index', compact('orders'));
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;
        $suppliers = Contact::where('company_id', $companyId)->where('type', 'vendor')->get();
        $products = Product::where('company_id', $companyId)->get();
        
        // Basit bir siparişi numarası üretimi
        $lastOrder = PurchaseOrder::where('company_id', $companyId)->latest()->first();
        $nextOrderNumber = 'PO-' . str_pad(($lastOrder ? intval(substr($lastOrder->order_number, 3)) + 1 : 1), 6, '0', STR_PAD_LEFT);

        return view('purchasing::create', compact('suppliers', 'products', 'nextOrderNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:contacts,id',
            'order_number' => 'required|string|unique:purchasing_orders,order_number',
            'order_date' => 'required|date',
            'status' => 'required|in:draft,sent',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric',
        ]);

        return DB::transaction(function () use ($validated) {
            $order = PurchaseOrder::create([
                'company_id' => auth()->user()->company_id,
                'supplier_id' => $validated['supplier_id'],
                'order_number' => $validated['order_number'],
                'order_date' => $validated['order_date'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'total_amount' => 0,
                'tax_amount' => 0,
            ]);

            $totalAmount = 0;
            $taxAmount = 0;

            foreach ($validated['items'] as $itemData) {
                $subTotal = $itemData['quantity'] * $itemData['unit_price'];
                $itemTax = $subTotal * (($itemData['tax_rate'] ?? 20) / 100);
                $itemTotal = $subTotal + $itemTax;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'tax_rate' => $itemData['tax_rate'] ?? 20,
                    'tax_amount' => $itemTax,
                    'total_amount' => $itemTotal,
                ]);

                $totalAmount += $itemTotal;
                $taxAmount += $itemTax;
            }

            $order->update([
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
            ]);

            return redirect()->route('purchasing.orders.index')->with('success', 'Satın alma siparişi başarıyla oluşturuldu.');
        });
    }

    public function show(PurchaseOrder $order)
    {
        $order->load(['supplier', 'items.product']);
        $warehouses = \Modules\Inventory\Models\Warehouse::where('company_id', auth()->user()->company_id)->get();
        return view('purchasing::show', compact('order', 'warehouses'));
    }

    public function receive(Request $request, PurchaseOrder $order)
    {
        if ($order->status !== 'sent') {
            return back()->with('error', 'Sadece gönderildi durumundaki siparişlerin mal kabulü yapılabilir.');
        }

        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        return DB::transaction(function () use ($order, $validated) {
            foreach ($order->items as $item) {
                StockMovement::create([
                    'company_id' => $order->company_id,
                    'product_id' => $item->product_id,
                    'warehouse_id' => $validated['warehouse_id'],
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'reference_type' => 'purchase_order',
                    'reference_id' => $order->id,
                    'date' => now(),
                    'description' => $order->order_number . ' nolu sipariş ile mal kabulü.',
                ]);
            }

            $order->update(['status' => 'received']);

            return redirect()->route('purchasing.orders.show', $order)->with('success', 'Mal kabulü başarıyla yapıldı ve stoklar güncellendi.');
        });
    }

    public function edit(PurchaseOrder $order)
    {
        $companyId = auth()->user()->company_id;
        $order->load(['items.product', 'supplier']);
        $suppliers = Contact::where('company_id', $companyId)->where('type', 'vendor')->get();
        $products = Product::where('company_id', $companyId)->get();

        return view('purchasing::edit', compact('order', 'suppliers', 'products'));
    }

    public function update(Request $request, PurchaseOrder $order)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:contacts,id',
            'order_date' => 'required|date',
            'status' => 'required|in:draft,sent,received,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric',
        ]);

        return DB::transaction(function () use ($validated, $order) {
            $order->update([
                'supplier_id' => $validated['supplier_id'],
                'order_date' => $validated['order_date'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            // Eski kalemleri sil ve yenilerini ekle (Basit yaklaşım)
            $order->items()->delete();

            $totalAmount = 0;
            $taxAmount = 0;

            foreach ($validated['items'] as $itemData) {
                $subTotal = $itemData['quantity'] * $itemData['unit_price'];
                $itemTax = $subTotal * (($itemData['tax_rate'] ?? 20) / 100);
                $itemTotal = $subTotal + $itemTax;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'tax_rate' => $itemData['tax_rate'] ?? 20,
                    'tax_amount' => $itemTax,
                    'total_amount' => $itemTotal,
                ]);

                $totalAmount += $itemTotal;
                $taxAmount += $itemTax;
            }

            $order->update([
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
            ]);

            return redirect()->route('purchasing.orders.show', $order)->with('success', 'Satın alma siparişi güncellendi.');
        });
    }

    public function destroy(PurchaseOrder $order)
    {
        if ($order->status === 'received') {
            return back()->with('error', 'Mal kabulü yapılmış siparişler silinemez. Önce stok hareketlerini kontrol etmelisiniz.');
        }

        $order->delete();
        return redirect()->route('purchasing.orders.index')->with('success', 'Sipariş silindi.');
    }

    public function convertToInvoice(PurchaseOrder $order)
    {
        if ($order->invoice_id) {
            return back()->with('error', 'Bu sipariş zaten faturaya dönüştürülmüş.');
        }

        if ($order->status !== 'received') {
            return back()->with('error', 'Sadece teslim alınmış siparişler faturaya dönüştürülebilir.');
        }

        return DB::transaction(function () use ($order) {
            // 1. Muhasebe Faturası Oluştur
            $invoice = \Modules\Accounting\Models\Invoice::create([
                'company_id' => $order->company_id,
                'contact_id' => $order->supplier_id,
                'invoice_number' => 'ALIS-' . $order->order_number,
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $order->total_amount - $order->tax_amount,
                'vat_total' => $order->tax_amount,
                'grand_total' => $order->total_amount,
                'total_amount' => $order->total_amount, // Uyumluluk için
                'tax_amount' => $order->tax_amount, // Uyumluluk için
                'status' => 'sent',
                'notes' => $order->order_number . ' nolu siparişten otomatik oluşturuldu.',
            ]);

            // 2. Fatura Kalemlerini Oluştur
            foreach ($order->items as $item) {
                \Modules\Accounting\Models\InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product_id,
                    'description' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'vat_rate' => $item->tax_rate,
                    'vat_amount' => $item->tax_amount,
                    'line_total' => $item->total_amount,
                    'tax_rate' => $item->tax_rate, // Uyumluluk için
                    'total' => $item->total_amount, // Uyumluluk için
                ]);
            }

            // 3. Cari Hareketi Senkronize Et
            $transactionService = app(\Modules\Accounting\Services\AccountTransactionService::class);
            $transactionService->syncFromInvoice($invoice);

            // 4. Siparişi Güncelle
            $order->update(['invoice_id' => $invoice->id]);

            return redirect()->route('purchasing.orders.show', $order)->with('success', 'Sipariş başarıyla faturaya dönüştürüldü ve muhasebeye işlendi.');
        });
    }
}
