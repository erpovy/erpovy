<?php

namespace Modules\Manufacturing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\Manufacturing\Models\WorkOrder;
use Modules\Manufacturing\Models\BillOfMaterial;

class MRPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Calculate General Stats
        $stats = [
            'open_orders' => WorkOrder::whereIn('status', ['pending', 'in_progress'])->count(),
            'critical_stock' => Product::where('stock_track', true)
                                      ->get()
                                      ->filter(function($product) {
                                          return $product->stock <= $product->min_stock_level;
                                      })->count(),
            'planned_production' => WorkOrder::where('status', 'pending')->sum('quantity'),
        ];

        // 2. Identify Requirements (Products below min stock)
        // In a real MRP, this would also consider Sales Orders demand vs Incoming Stock.
        // For now, we drive it by "Reorder Point" logic.
        
        $requirements = Product::with(['stockMovements', 'billOfMaterials'])
            ->where('stock_track', true)
            ->get()
            ->filter(function ($product) {
                // Determine effective stock (Current Stock + Incoming Work Orders)
                $incoming = WorkOrder::where('product_id', $product->id)
                                    ->whereIn('status', ['pending', 'in_progress'])
                                    ->sum('quantity');
                                    
                $projectedStock = $product->stock + $incoming;
                
                return $projectedStock <= $product->min_stock_level; 
            })
            ->map(function ($product) {
                $deficit = $product->min_stock_level - $product->stock;
                // If projected stock is okay but current is low, we might still want to warn, 
                // but strictly speaking MRP looks at availability. 
                // Let's suggest order quantity = max(deficit, economic order qty -> arbitrarily 10 for now or deficit)
                
                $suggestionType = $product->billOfMaterials()->exists() ? 'production' : 'purchase';
                
                return [
                    'product' => $product,
                    'current_stock' => $product->stock,
                    'min_level' => $product->min_stock_level,
                    'deficit' => $deficit,
                    'suggestion_type' => $suggestionType, // 'production' or 'purchase'
                    'suggestion_quantity' => $deficit > 0 ? $deficit : 0,
                ];
            });

        return view('manufacturing::mrp.index', compact('stats', 'requirements'));
    }
}
