<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Sales\Models\Rental;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Rental::with(['contact', 'product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('rental_no', 'like', "%{$search}%")
                  ->orWhereHas('contact', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rentals = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'active_rentals' => Rental::where('status', 'active')->count(),
            'total_volume' => Rental::count(),
            'overdue_count' => Rental::where('status', 'active')->where('end_date', '<', now())->count(),
            'monthly_revenue' => Rental::whereMonth('created_at', now()->month)->get()->sum('total_amount'),
        ];

        return view('sales::rentals.index', compact('rentals', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contacts = Contact::where('type', 'customer')->orderBy('name')->get();
        $products = Product::whereHas('productType', function($q) {
            $q->where('code', 'good');
        })->orderBy('name')->get();
        return view('sales::rentals.create', compact('contacts', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'product_id' => 'nullable|exists:products,id',
            'daily_price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['rental_no'] = 'KRL-' . strtoupper(uniqid());

         Rental::create($validated);

        return redirect()->route('sales.rentals.index')->with('success', 'Kiralama kaydı başarıyla oluşturuldu.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $rental = Rental::with(['contact', 'product'])->findOrFail($id);
        return view('sales::rentals.show', compact('rental'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->delete();

        return redirect()->route('sales.rentals.index')->with('success', 'Kiralama kaydı başarıyla silindi.');
    }
}
