<?php

namespace Modules\Manufacturing\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Manufacturing\Models\QualityCheck;
use Modules\Inventory\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class QualityController extends Controller
{
    public function index()
    {
        $scope = QualityCheck::where('company_id', auth()->user()->company_id);
        
        $stats = [
            'total' => (clone $scope)->count(),
            'passed' => (clone $scope)->where('status', 'pass')->count(),
            'failed' => (clone $scope)->where('status', 'fail')->count(),
        ];

        // Pass rate calculation
        $stats['pass_rate'] = $stats['total'] > 0 
            ? round(($stats['passed'] / $stats['total']) * 100, 1) 
            : 0;

        $checks = $scope->with('product')
            ->latest()
            ->paginate(10);
            
        $products = Product::where('company_id', auth()->user()->company_id)->get();

        return view('manufacturing::quality.index', compact('checks', 'products', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:incoming,in_process,final',
            'status' => 'required|in:pass,fail,conditional',
            'checked_quantity' => 'required|numeric|min:0',
            'rejected_quantity' => 'nullable|numeric|min:0',
            'check_date' => 'required|date',
        ]);

        QualityCheck::create([
            'company_id' => auth()->user()->company_id,
            'product_id' => $request->product_id,
            'reference_number' => 'QC-' . strtoupper(Str::random(8)),
            'type' => $request->type,
            'status' => $request->status,
            'checked_quantity' => $request->checked_quantity,
            'rejected_quantity' => $request->rejected_quantity ?? 0,
            'check_date' => $request->check_date,
            'inspector_name' => auth()->user()->name,
            'notes' => $request->notes,
        ]);

        return redirect()->route('manufacturing.quality.index')->with('success', 'Kalite kontrol kaydı oluşturuldu.');
    }
}
