<?php

namespace Modules\ServiceManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ServiceManagement\Models\JobCard;
use Modules\ServiceManagement\Models\JobCardItem;
use Modules\ServiceManagement\Models\Vehicle;
use Modules\Inventory\Models\Product;

class JobCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JobCard::with(['vehicle', 'customer']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_number', 'like', "%{$search}%")
                  ->orWhereHas('vehicle', function($v) use ($search) {
                      $v->where('plate_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('customer', function($c) use ($search) {
                      $c->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobCards = $query->latest('entry_date')->paginate(15)->withQueryString();

        return view('servicemanagement::job_cards.index', compact('jobCards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::with('customer')->orderBy('plate_number')->get();
        return view('servicemanagement::job_cards.create', compact('vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:sm_vehicles,id',
            'entry_date' => 'required|date',
            'expected_completion_date' => 'nullable|date|after_or_equal:entry_date',
            'priority' => 'required|in:low,normal,high,urgent',
            'customer_complaint' => 'nullable|string',
            'odometer_reading' => 'nullable|integer',
            'fuel_level' => 'nullable|numeric|between:0,1',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        
        // Auto-generate Job Number
        $year = date('Y');
        $lastJob = JobCard::whereYear('created_at', $year)->latest()->first();
        $sequence = $lastJob ? intval(substr($lastJob->job_number, -4)) + 1 : 1;
        $jobNumber = 'JC-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        $jobCard = JobCard::create([
            'company_id' => auth()->user()->company_id,
            'vehicle_id' => $vehicle->id,
            'customer_id' => $vehicle->customer_id,
            'job_number' => $jobNumber,
            'status' => 'pending',
            'priority' => $validated['priority'],
            'entry_date' => $validated['entry_date'],
            'expected_completion_date' => $validated['expected_completion_date'],
            'customer_complaint' => $validated['customer_complaint'],
            'odometer_reading' => $validated['odometer_reading'],
            'fuel_level' => $validated['fuel_level'],
        ]);

        // Update Vehicle Status
        $vehicle->update(['status' => 'maintenance', 'current_mileage' => $validated['odometer_reading'] ?? $vehicle->current_mileage]);

        return redirect()->route('servicemanagement.job-cards.edit', $jobCard->id)
            ->with('success', 'İş emri oluşturuldu.');
    }

    /**
     * Show the specified resource.
     */
    public function show(JobCard $jobCard)
    {
        $jobCard->load(['vehicle', 'customer', 'items.product']);
        return view('servicemanagement::job_cards.show', compact('jobCard'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobCard $jobCard)
    {
        $jobCard->load(['vehicle', 'customer', 'items.product']);
        $products = Product::select('id', 'name', 'code', 'selling_price')->get();
        return view('servicemanagement::job_cards.edit', compact('jobCard', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobCard $jobCard)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'diagnosis' => 'nullable|string',
            'internal_notes' => 'nullable|string',
            'expected_completion_date' => 'nullable|date',
            'actual_completion_date' => 'nullable|date',
        ]);

        $jobCard->update($validated);

        if ($validated['status'] == 'completed' && !$jobCard->actual_completion_date) {
            $jobCard->update(['actual_completion_date' => now()]);
            $jobCard->vehicle->update(['status' => 'active']);
        }

        return redirect()->back()->with('success', 'İş emri güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobCard $jobCard)
    {
        $jobCard->delete();
        return redirect()->route('servicemanagement.job-cards.index')
            ->with('success', 'İş emri silindi.');
    }

    public function addItem(Request $request, JobCard $jobCard)
    {
        $validated = $request->validate([
            'type' => 'required|in:part,labor,service,other',
            'product_id' => 'nullable|exists:inv_products,id',
            'name' => 'required_without:product_id|string',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0',
        ]);

        if ($request->filled('product_id')) {
            $product = Product::find($request->product_id);
            $name = $product->name; // You might want to append code etc.
        } else {
            $name = $request->name;
        }

        $jobCard->items()->create([
            'product_id' => $request->product_id,
            'type' => $request->type,
            'name' => $name,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'tax_rate' => $request->tax_rate ?? 0,
            'total_price' => $request->quantity * $request->unit_price, // Will be recalculated by model boot
        ]);

        return redirect()->back()->with('success', 'Kalem eklendi.');
    }

    public function removeItem(JobCard $jobCard, JobCardItem $item)
    {
        if ($item->job_card_id !== $jobCard->id) {
            abort(403);
        }
        $item->delete();
        return redirect()->back()->with('success', 'Kalem silindi.');
    }
}
