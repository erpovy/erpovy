<?php

namespace Modules\Purchasing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Purchasing\Models\PurchaseOrder;
use Modules\Purchasing\Models\PurchaseOrderItem;
use Modules\CRM\Models\Contact;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;

        // Temel Metrikler
        $stats = [
            'total_orders' => PurchaseOrder::where('company_id', $companyId)->count(),
            'total_amount' => PurchaseOrder::where('company_id', $companyId)->sum('total_amount'),
            'received_count' => PurchaseOrder::where('company_id', $companyId)->where('status', 'received')->count(),
            'pending_count' => PurchaseOrder::where('company_id', $companyId)->where('status', 'sent')->count(),
        ];

        // Aylık Harcama Grafiği (Son 6 Ay)
        $monthlyPurchases = PurchaseOrder::where('company_id', $companyId)
            ->where('status', '!=', 'cancelled')
            ->where('order_date', '>=', now()->subMonths(6))
            ->select(
                DB::raw('strftime("%Y-%m", order_date) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // En Çok Alım Yapılan 5 Tedarikçi
        $topSuppliers = PurchaseOrder::where('company_id', $companyId)
            ->where('status', '!=', 'cancelled')
            ->select('supplier_id', DB::raw('SUM(total_amount) as total_spent'), DB::raw('COUNT(*) as order_count'))
            ->with('supplier')
            ->groupBy('supplier_id')
            ->orderBy('total_spent', 'desc')
            ->take(5)
            ->get();

        // Son 5 Sipariş
        $recentOrders = PurchaseOrder::where('company_id', $companyId)
            ->with('supplier')
            ->latest()
            ->take(5)
            ->get();

        return view('purchasing::dashboard', compact('stats', 'monthlyPurchases', 'topSuppliers', 'recentOrders'));
    }
}
