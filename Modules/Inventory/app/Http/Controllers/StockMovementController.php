<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockMovement;
use Modules\Inventory\Models\Warehouse;

class StockMovementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
            'type' => 'required|in:in,out', // 'in' = purchase/adjustment_in, 'out' = sale/adjustment_out
            'description' => 'nullable|string|max:255',
        ]);

        // Default warehouse for MVP (First one created or create one)
        $warehouse = Warehouse::firstOrCreate(
            ['company_id' => auth()->user()->company_id],
            ['name' => 'Merkez Depo', 'is_default' => true]
        );

        $quantity = $validated['type'] === 'out' ? -$validated['quantity'] : $validated['quantity'];

        StockMovement::create([
            'company_id' => auth()->user()->company_id,
            'product_id' => $validated['product_id'],
            'warehouse_id' => $warehouse->id,
            'quantity' => $quantity,
            'type' => 'adjustment',
            'reference' => 'Manuel Düzeltme: ' . ($validated['description'] ?? '-'),
        ]);

        return back()->with('success', 'Stok hareketi kaydedildi.');
    }
}
