<?php

namespace Modules\Sales\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = [
            'total_sales' => \Modules\Accounting\Models\Invoice::sum('total_amount'),
            'total_orders' => \Modules\Accounting\Models\Invoice::count(),
            'pending_amount' => \Modules\Accounting\Models\Invoice::where('status', '!=', 'paid')->sum('total_amount'),
            'monthly_sales' => \Modules\Accounting\Models\Invoice::whereMonth('issue_date', now()->month)->sum('total_amount'),
        ];

        $recentSales = \Modules\Accounting\Models\Invoice::with('contact')
            ->latest()
            ->take(8)
            ->get();

        return view('sales::index', compact('stats', 'recentSales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('accounting.invoices.create', ['type' => 'sales']);
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
        return redirect()->route('accounting.invoices.show', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return redirect()->route('accounting.invoices.edit', $id);
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
