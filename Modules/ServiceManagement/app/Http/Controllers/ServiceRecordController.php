<?php

namespace Modules\ServiceManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ServiceManagement\Models\Vehicle;
use Modules\ServiceManagement\Models\ServiceRecord;

class ServiceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceRecord::with('vehicle');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('vehicle', function($v) use ($search) {
                $v->where('plate_number', 'like', "%{$search}%");
            })->orWhere('service_type', 'like', "%{$search}%");
        }

        $records = $query->latest()->paginate(20)->withQueryString();

        return view('servicemanagement::service-records.index', compact('records'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:sm_vehicles,id',
            'service_type' => 'required|string|max:255',
            'service_date' => 'required|date',
            'mileage_at_service' => 'required|integer|min:0',
            'description' => 'required|string',
            'total_cost' => 'required|numeric|min:0',
            'performed_by' => 'nullable|string|max:255',
            'status' => 'required|in:pending,in_progress,completed',
            'next_planned_date' => 'nullable|date|after:service_date',
            'next_planned_mileage' => 'nullable|integer|gt:mileage_at_service',
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        $serviceRecord = ServiceRecord::create($validated);

        // Update vehicle current mileage if service is completed
        if ($serviceRecord->status === 'completed') {
            $vehicle = $serviceRecord->vehicle;
            if ($serviceRecord->mileage_at_service > $vehicle->current_mileage) {
                $vehicle->update(['current_mileage' => $serviceRecord->mileage_at_service]);
            }
        }

        return redirect()->route('servicemanagement.vehicles.show', $serviceRecord->vehicle_id)
            ->with('success', 'Servis kaydı başarıyla eklendi.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceRecord $serviceRecord)
    {
        $validated = $request->validate([
            'service_type' => 'required|string|max:255',
            'service_date' => 'required|date',
            'mileage_at_service' => 'required|integer|min:0',
            'description' => 'required|string',
            'total_cost' => 'required|numeric|min:0',
            'performed_by' => 'nullable|string|max:255',
            'status' => 'required|in:pending,in_progress,completed',
            'next_planned_date' => 'nullable|date',
            'next_planned_mileage' => 'nullable|integer',
        ]);

        $serviceRecord->update($validated);

        // Update vehicle current mileage if service is completed
        if ($serviceRecord->status === 'completed') {
            $vehicle = $serviceRecord->vehicle;
            if ($serviceRecord->mileage_at_service > $vehicle->current_mileage) {
                $vehicle->update(['current_mileage' => $serviceRecord->mileage_at_service]);
            }
        }

        return redirect()->route('servicemanagement.vehicles.show', $serviceRecord->vehicle_id)
            ->with('success', 'Servis kaydı güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceRecord $serviceRecord)
    {
        $vehicleId = $serviceRecord->vehicle_id;
        $serviceRecord->delete();

        return redirect()->route('servicemanagement.vehicles.show', $vehicleId)
            ->with('success', 'Servis kaydı silindi.');
    }
}
