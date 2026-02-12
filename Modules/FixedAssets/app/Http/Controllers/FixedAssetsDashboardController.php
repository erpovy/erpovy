<?php

namespace Modules\FixedAssets\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FixedAssets\Models\FixedAsset;
use Modules\FixedAssets\Models\FixedAssetCategory;
use Modules\FixedAssets\Models\AssetAssignment;
use Modules\FixedAssets\Models\AssetMaintenance;

class FixedAssetsDashboardController extends Controller
{
    public function index()
    {
        // Overview Stats
        $stats = [
            'total_assets' => FixedAsset::count(),
            'total_value' => FixedAsset::sum('purchase_value'),
            'active_assets' => FixedAsset::where('status', 'active')->count(),
            'maintenance_assets' => FixedAsset::where('status', 'maintenance')->count(),
            'retired_assets' => FixedAsset::where('status', 'retired')->count(),
        ];

        // Category Distribution
        $categoryData = FixedAssetCategory::withCount('assets')
            ->get()
            ->map(fn($cat) => [
                'name' => $cat->name,
                'count' => $cat->assets_count
            ]);

        // Recent Assignments
        $recentAssignments = AssetAssignment::with(['asset', 'employee'])
            ->latest()
            ->take(5)
            ->get();

        // Upcoming Maintenances
        $upcomingMaintenances = AssetMaintenance::with('asset')
            ->where('next_maintenance_date', '>=', now())
            ->orderBy('next_maintenance_date', 'asc')
            ->take(5)
            ->get();

        // Assets currently assigned vs available
        $assignmentStats = [
            'assigned' => AssetAssignment::whereNull('returned_at')->count(),
            'available' => FixedAsset::where('status', 'active')
                ->whereDoesntHave('assignments', function($q) {
                    $q->whereNull('returned_at');
                })->count(),
        ];

        return view('fixedassets::dashboard', compact(
            'stats', 
            'categoryData', 
            'recentAssignments', 
            'upcomingMaintenances',
            'assignmentStats'
        ));
    }
}
