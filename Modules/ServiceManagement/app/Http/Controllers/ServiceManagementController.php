<?php

namespace Modules\ServiceManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = [
            'total_vehicles' => \Modules\ServiceManagement\Models\Vehicle::count(),
            'active_services' => \Modules\ServiceManagement\Models\ServiceRecord::where('status', 'in_progress')->count(),
            'pending_repairs' => \Modules\ServiceManagement\Models\ServiceRecord::where('status', 'pending')->count(),
            'monthly_cost' => \Modules\ServiceManagement\Models\ServiceRecord::whereMonth('service_date', now()->month)
                ->whereYear('service_date', now()->year)
                ->sum('total_cost'),
        ];

        // Cost History for Chart (Last 6 Months)
        $costHistory = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $costHistory[] = [
                'month' => $date->translatedFormat('M'),
                'total' => \Modules\ServiceManagement\Models\ServiceRecord::whereMonth('service_date', $date->month)
                    ->whereYear('service_date', $date->year)
                    ->sum('total_cost')
            ];
        }

        $recentServices = \Modules\ServiceManagement\Models\ServiceRecord::with('vehicle')
            ->latest()
            ->take(5)
            ->get();

        $upcomingMaintenance = \Modules\ServiceManagement\Models\ServiceRecord::with('vehicle')
            ->whereNotNull('next_planned_date')
            ->where('next_planned_date', '>=', now())
            ->orderBy('next_planned_date')
            ->take(4)
            ->get();

        // Overdue Vehicles
        $overdueVehicles = \Modules\ServiceManagement\Models\Vehicle::all()->filter(function($v) {
            return $v->maintenance_status === 'overdue';
        })->take(5);

        return view('servicemanagement::index', compact('stats', 'recentServices', 'upcomingMaintenance', 'costHistory', 'overdueVehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servicemanagement::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('servicemanagement::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('servicemanagement::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
