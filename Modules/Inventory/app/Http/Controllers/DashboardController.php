<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;

        // Temel İstatistikler
        $totalProducts = Product::where('company_id', $companyId)->count();
        
        $totalStockValue = DB::table('products')
            ->where('company_id', $companyId)
            ->where('stock_track', true)
            ->selectRaw('SUM((SELECT SUM(quantity) FROM stock_movements WHERE product_id = products.id) * purchase_price) as total_value')
            ->value('total_value') ?? 0;

        $criticalStockCount = Product::where('company_id', $companyId)
            ->where('stock_track', true)
            ->whereNotNull('min_stock_level')
            ->whereRaw('(SELECT SUM(quantity) FROM stock_movements WHERE product_id = products.id) <= min_stock_level')
            ->count();

        $outOfStockCount = Product::where('company_id', $companyId)
            ->where('stock_track', true)
            ->whereRaw('(SELECT SUM(quantity) FROM stock_movements WHERE product_id = products.id) <= 0')
            ->count();

        // Kategori Bazlı Stok Dağılımı (Grafik için)
        $stockByCategory = Category::where('company_id', $companyId)
            ->withCount(['products' => function($q) {
                $q->where('stock_track', true);
            }])
            ->get()
            ->map(function($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->products_count
                ];
            });

        // Son Stok Hareketleri
        $recentMovements = StockMovement::where('company_id', $companyId)
            ->with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Haftalık Stok Hareketleri (Grafik için)
        $weeklyMovements = StockMovement::where('company_id', $companyId)
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, type, SUM(quantity) as total')
            ->groupBy('date', 'type')
            ->get();

        return view('inventory::dashboard', compact(
            'totalProducts',
            'totalStockValue',
            'criticalStockCount',
            'outOfStockCount',
            'stockByCategory',
            'recentMovements',
            'weeklyMovements'
        ));
    }
}
