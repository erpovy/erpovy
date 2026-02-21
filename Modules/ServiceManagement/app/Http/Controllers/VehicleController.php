<?php

namespace Modules\ServiceManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ServiceManagement\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vehicles = $query->with('customer')->latest()->paginate(15)->withQueryString();

        return view('servicemanagement::vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = \Modules\CRM\Models\Contact::where('type', 'customer')->orderBy('name')->get();
        return view('servicemanagement::vehicles.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:crm_contacts,id',
            'plate_number' => 'required|string|unique:sm_vehicles,plate_number',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'vin' => 'nullable|string|unique:sm_vehicles,vin',
            'chassis_number' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'current_mileage' => 'required|integer|min:0',
            'status' => 'required|in:active,maintenance,inactive',
            'notes' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        Vehicle::create($validated);

        return redirect()->route('servicemanagement.vehicles.index')
            ->with('success', 'Araç başarıyla kaydedildi.');
    }

    /**
     * Show the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['serviceRecords' => function($q) {
            $q->latest();
        }]);

        $totalCost = $vehicle->serviceRecords->sum('total_cost');
        $costPerKm = $vehicle->current_mileage > 0 ? $totalCost / $vehicle->current_mileage : 0;

        // Cost History for Chart (Last 6 Months)
        $costHistory = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $costHistory[] = [
                'month' => $date->translatedFormat('M'),
                'total' => $vehicle->serviceRecords()
                    ->whereMonth('service_date', $date->month)
                    ->whereYear('service_date', $date->year)
                    ->sum('total_cost')
            ];
        }

        return view('servicemanagement::vehicles.show', compact('vehicle', 'totalCost', 'costPerKm', 'costHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $customers = \Modules\CRM\Models\Contact::where('type', 'customer')->orderBy('name')->get();
        return view('servicemanagement::vehicles.edit', compact('vehicle', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:crm_contacts,id',
            'plate_number' => 'required|string|unique:sm_vehicles,plate_number,' . $vehicle->id,
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'vin' => 'nullable|string|unique:sm_vehicles,vin,' . $vehicle->id,
            'chassis_number' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
            'current_mileage' => 'required|integer|min:0',
            'status' => 'required|in:active,maintenance,inactive',
            'notes' => 'nullable|string',
        ]);

        $vehicle->update($validated);

        return redirect()->route('servicemanagement.vehicles.index')
            ->with('success', 'Araç bilgileri güncellendi.');
    }

    /**
     * Get maintenance status by plate number for POS alerts.
     */
    public function statusByPlate($plate)
    {
        $vehicle = Vehicle::where('company_id', auth()->user()->company_id)
            ->where('plate_number', strtoupper(str_replace(' ', '', $plate)))
            ->first();

        if (!$vehicle) {
            return response()->json(['status' => 'new']);
        }

        return response()->json([
            'status' => 'exists',
            'maintenance_status' => $vehicle->maintenance_status, // healthy, upcoming, overdue
            'current_mileage' => $vehicle->current_mileage,
            'brand' => $vehicle->brand,
            'model' => $vehicle->model
        ]);
    }
}
