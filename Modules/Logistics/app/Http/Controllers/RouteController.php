<?php

namespace Modules\Logistics\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Logistics\Models\Route;
use Modules\Logistics\Models\Vehicle;
use Modules\Logistics\Models\Shipment;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::where('company_id', auth()->user()->company_id)
            ->with(['vehicle', 'shipments'])
            ->latest()
            ->paginate(15);
            
        return view('logistics::routes.index', compact('routes'));
    }

    public function show(Route $route)
    {
        $route->load(['vehicle', 'shipments.contact']);
        return view('logistics::routes.show', compact('route'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('company_id', auth()->user()->company_id)
            ->where('status', 'available')
            ->get();
        
        $availableShipments = Shipment::where('company_id', auth()->user()->company_id)
            ->whereNull('route_id')
            ->whereIn('status', ['pending', 'processing'])
            ->get();
            
        return view('logistics::routes.create', compact('vehicles', 'availableShipments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_id' => 'nullable|exists:logistics_vehicles,id',
            'planned_date' => 'required|date',
            'status' => 'required|in:draft,optimized,in_progress,completed',
            'stops' => 'nullable|array',
            'stops.*.location' => 'required|string|max:255',
            'stops.*.estimated_arrival' => 'nullable|string',
            'stops.*.shipment_id' => 'nullable|exists:logistics_shipments,id',
            'shipment_ids' => 'nullable|array',
            'shipment_ids.*' => 'exists:logistics_shipments,id',
            'total_distance' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:0',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        
        $route = Route::create($validated);

        if (!empty($request->shipment_ids)) {
            Shipment::whereIn('id', $request->shipment_ids)->update(['route_id' => $route->id]);
        }

        return redirect()->route('logistics.routes.index')
            ->with('success', 'Rota başarıyla oluşturuldu.');
    }

    public function edit(Route $route)
    {
        $vehicles = Vehicle::where('company_id', auth()->user()->company_id)->get();
        
        $availableShipments = Shipment::where('company_id', auth()->user()->company_id)
            ->where(function($query) use ($route) {
                $query->whereNull('route_id')->orWhere('route_id', $route->id);
            })
            ->get();

        return view('logistics::routes.edit', compact('route', 'vehicles', 'availableShipments'));
    }

    public function update(Request $request, Route $route)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_id' => 'nullable|exists:logistics_vehicles,id',
            'planned_date' => 'required|date',
            'status' => 'required|in:draft,optimized,in_progress,completed',
            'stops' => 'nullable|array',
            'stops.*.location' => 'required|string|max:255',
            'stops.*.estimated_arrival' => 'nullable|string',
            'stops.*.shipment_id' => 'nullable|exists:logistics_shipments,id',
            'shipment_ids' => 'nullable|array',
            'shipment_ids.*' => 'exists:logistics_shipments,id',
            'total_distance' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:0',
        ]);

        $route->update($validated);

        // Önce eski sevkiyatların bağını kopar (opsiyonel, sync mantığı)
        Shipment::where('route_id', $route->id)->update(['route_id' => null]);
        
        if (!empty($request->shipment_ids)) {
            Shipment::whereIn('id', $request->shipment_ids)->update(['route_id' => $route->id]);
        }

        // Rota tamamlandıysa bağlı sevkiyatları teslim edildi yap
        if ($route->status === 'completed') {
            Shipment::where('route_id', $route->id)->update([
                'status' => 'delivered',
                'delivered_at' => now()
            ]);
        }

        return redirect()->route('logistics.routes.index')
            ->with('success', 'Rota başarıyla güncellendi.');
    }

    public function destroy(Route $route)
    {
        $route->delete();
        return redirect()->route('logistics.routes.index')
            ->with('success', 'Rota başarıyla silindi.');
    }
}
