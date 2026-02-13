namespace Modules\Logistics\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Logistics\Models\Shipment;
use Modules\Logistics\Models\Vehicle;
use Modules\Logistics\Models\Route;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;

        $totalShipments = Shipment::where('company_id', $companyId)->count();
        $activeRoutes = Route::where('company_id', $companyId)->where('status', 'active')->count();
        $availableVehicles = Vehicle::where('company_id', $companyId)->where('status', 'available')->count();
        $pendingShipments = Shipment::where('company_id', $companyId)->where('status', 'pending')->count();

        $recentShipments = Shipment::where('company_id', $companyId)
            ->with('contact')
            ->latest()
            ->take(5)
            ->get();

        $recentRoutes = Route::where('company_id', $companyId)
            ->with('vehicle')
            ->where('planned_date', '>=', now()->toDateString())
            ->orderBy('planned_date')
            ->take(5)
            ->get();

        return view('logistics::dashboard', compact(
            'totalShipments',
            'activeRoutes',
            'availableVehicles',
            'pendingShipments',
            'recentShipments',
            'recentRoutes'
        ));
    }
}

