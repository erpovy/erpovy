<?php

namespace Modules\Manufacturing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufacturingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeWorkOrders = \Modules\Manufacturing\Models\WorkOrder::whereNotIn('status', ['completed', 'cancelled'])->count();
        $pendingQualityChecks = \Modules\Manufacturing\Models\QualityCheck::where('status', 'pending')->count();
        
        $recentWorkOrders = \Modules\Manufacturing\Models\WorkOrder::with('product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('manufacturing::index', compact('activeWorkOrders', 'pendingQualityChecks', 'recentWorkOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch products for the dropdown
        $products = \Modules\Inventory\Models\Product::select('id', 'name', 'code')->get();
        
        // Fetch all employees for assignment
        $employees = \Modules\HumanResources\Models\Employee::select('id', 'first_name', 'last_name')
            ->get();

        return view('manufacturing::create', compact('products', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'employee_id' => 'nullable|exists:employees,id',
            'notes' => 'nullable|string',
        ]);

        $orderNumber = $this->generateOrderNumber();

        \Modules\Manufacturing\Models\WorkOrder::create([
            'company_id' => auth()->user()->company_id ?? 1, // Fallback for testing if no company
            'product_id' => $validated['product_id'],
            'employee_id' => $validated['employee_id'] ?? null,
            'order_number' => $orderNumber,
            'quantity' => $validated['quantity'],
            'status' => 'pending',
            'start_date' => $validated['start_date'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('manufacturing.index')->with('success', 'İş emri başarıyla oluşturuldu.');
    }

    private function generateOrderNumber()
    {
        $prefix = 'WO-' . date('Ym');
        $lastOrder = \Modules\Manufacturing\Models\WorkOrder::where('order_number', 'like', "$prefix%")->latest()->first();
        
        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }

        return $prefix . '0001';
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('manufacturing::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('manufacturing::edit');
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
