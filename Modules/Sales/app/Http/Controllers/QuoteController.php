<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Sales\Models\Quote;
use Modules\Sales\Models\QuoteItem;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Quote::with('contact');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('quote_number', 'like', "%{$search}%")
                  ->orWhereHas('contact', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $quotes = $query->latest('date')->paginate(15)->withQueryString();

        $stats = [
            'total_count' => Quote::count(),
            'total_amount' => Quote::sum('total_amount'),
            'pending_count' => Quote::where('status', 'sent')->count(),
            'accepted_count' => Quote::where('status', 'accepted')->count(),
        ];

        return view('sales::quotes.index', compact('quotes', 'stats'));
    }

    public function create()
    {
        $contacts = Contact::where('type', 'customer')->orderBy('name')->get();
        $products = Product::where('type', 'good')->orWhere('type', 'service')->orderBy('name')->get();
        return view('sales::quotes.create', compact('contacts', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $totalTax = 0;

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $lineTax = $lineTotal * ($item['tax_rate'] / 100);
                
                $totalAmount += $lineTotal + $lineTax;
                $totalTax += $lineTax;
            }

            $quote = Quote::create([
                'company_id' => auth()->user()->company_id,
                'contact_id' => $validated['contact_id'],
                'quote_number' => 'QT-' . date('Ymd') . '-' . rand(100, 999),
                'date' => $validated['date'],
                'expiry_date' => $validated['expiry_date'],
                'total_amount' => $totalAmount,
                'tax_amount' => $totalTax,
                'status' => 'draft',
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $lineTax = $lineTotal * ($item['tax_rate'] / 100);

                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'],
                    'total' => $lineTotal + $lineTax,
                ]);
            }

            DB::commit();

            return redirect()->route('sales.quotes.index')->with('success', 'Teklif başarıyla oluşturuldu.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $quote = Quote::with(['contact', 'items.product'])->findOrFail($id);
        return view('sales::quotes.show', compact('quote'));
    }

    public function edit($id)
    {
        $quote = Quote::with('items')->findOrFail($id);
        $contacts = Contact::where('type', 'customer')->orderBy('name')->get();
        $products = Product::where('type', 'good')->orWhere('type', 'service')->orderBy('name')->get();
        return view('sales::quotes.edit', compact('quote', 'contacts', 'products'));
    }

    public function update(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);
        
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $totalTax = 0;

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $lineTax = $lineTotal * ($item['tax_rate'] / 100);
                
                $totalAmount += $lineTotal + $lineTax;
                $totalTax += $lineTax;
            }

            $quote->update([
                'contact_id' => $validated['contact_id'],
                'date' => $validated['date'],
                'expiry_date' => $validated['expiry_date'],
                'total_amount' => $totalAmount,
                'tax_amount' => $totalTax,
                'notes' => $validated['notes'],
            ]);

            $quote->items()->delete();

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $lineTax = $lineTotal * ($item['tax_rate'] / 100);

                QuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'],
                    'total' => $lineTotal + $lineTax,
                ]);
            }

            DB::commit();

            return redirect()->route('sales.quotes.index')->with('success', 'Teklif başarıyla güncellendi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();
        return redirect()->route('sales.quotes.index')->with('success', 'Teklif silindi.');
    }

    public function approve($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->update(['status' => 'accepted']);
        return back()->with('success', 'Teklif onaylandı.');
    }

    public function send($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->update(['status' => 'sent']);
        // Burada gerçek e-posta gönderim mantığı eklenebilir.
        return back()->with('success', 'Teklif e-posta olarak gönderildi.');
    }
}
