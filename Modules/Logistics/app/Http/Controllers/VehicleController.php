<?php

namespace Modules\Logistics\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Logistics\Models\Vehicle;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('company_id', auth()->user()->company_id)
            ->latest()
            ->paginate(10);
            
        return view('logistics::vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('logistics::vehicles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20|unique:logistics_vehicles',
            'type' => 'required|string|max:50',
            'brand' => 'nullable|string|max:50',
            'model' => 'nullable|string|max:50',
            'capacity_weight' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,on_route,maintenance',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        
        Vehicle::create($validated);

        return redirect()->route('logistics.vehicles.index')
            ->with('success', 'Araç başarıyla eklendi.');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('logistics::vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20|unique:logistics_vehicles,plate_number,' . $vehicle->id,
            'type' => 'required|string|max:50',
            'brand' => 'nullable|string|max:50',
            'model' => 'nullable|string|max:50',
            'capacity_weight' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,on_route,maintenance',
        ]);

        $vehicle->update($validated);

        return redirect()->route('logistics.vehicles.index')
            ->with('success', 'Araç başarıyla güncellendi.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('logistics.vehicles.index')
            ->with('success', 'Araç başarıyla silindi.');
    }
}
