<?php

namespace Modules\Manufacturing\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Manufacturing\Models\WorkOrder;
use Modules\Inventory\Models\Product;
use Illuminate\Support\Str;

class MESController extends Controller
{
    public function index()
    {
        $scope = WorkOrder::where('company_id', auth()->user()->company_id);
        
        $stats = [
            'total' => (clone $scope)->count(),
            'pending' => (clone $scope)->where('status', 'pending')->count(),
            'in_progress' => (clone $scope)->where('status', 'in_progress')->count(),
            'completed' => (clone $scope)->where('status', 'completed')->count(),
        ];

        $workOrders = $scope->with('product')
            ->latest()
            ->paginate(10);
            
        $products = Product::where('company_id', auth()->user()->company_id)->get();

        return view('manufacturing::mes.index', compact('workOrders', 'products', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'due_date' => 'nullable|date',
        ]);

        WorkOrder::create([
            'company_id' => auth()->user()->company_id,
            'product_id' => $request->product_id,
            'order_number' => 'WO-' . strtoupper(Str::random(8)), // Basit bir numara üretimi
            'quantity' => $request->quantity,
            'status' => 'pending',
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('manufacturing.mes.index')->with('success', 'İş emri başarıyla oluşturuldu.');
    }
}
