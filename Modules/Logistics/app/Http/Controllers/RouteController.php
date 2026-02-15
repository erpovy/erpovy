<?php

namespace Modules\Logistics\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Logistics\Models\Route;
use Modules\Logistics\Models\Vehicle;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::where('company_id', auth()->user()->company_id)
            ->with('vehicle')
            ->latest()
            ->paginate(15);
            
        return view('logistics::routes.index', compact('routes'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('company_id', auth()->user()->company_id)
            ->where('status', 'available')
            ->get();
            
        return view('logistics::routes.create', compact('vehicles'));
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
            'total_distance' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:0',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        
        Route::create($validated);

        return redirect()->route('logistics.routes.index')
            ->with('success', 'Rota başarıyla oluşturuldu.');
    }

    public function edit(Route $route)
    {
        $vehicles = Vehicle::where('company_id', auth()->user()->company_id)->get();
        return view('logistics::routes.edit', compact('route', 'vehicles'));
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
            'total_distance' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:0',
        ]);

        $route->update($validated);

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
