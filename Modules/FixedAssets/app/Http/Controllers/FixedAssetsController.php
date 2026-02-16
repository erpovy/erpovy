<?php

namespace Modules\FixedAssets\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FixedAssets\Models\FixedAsset;
use Modules\FixedAssets\Models\FixedAssetCategory;
use Modules\HumanResources\Models\Employee;

class FixedAssetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = FixedAsset::with(['category', 'currentHolder'])->latest()->paginate(10);
        
        $stats = [
            'total_count' => FixedAsset::count(),
            'active_count' => FixedAsset::where('status', 'active')->count(),
            'total_value' => FixedAsset::sum('purchase_value'),
            'retired_count' => FixedAsset::where('status', 'retired')->count(),
        ];

        return view('fixedassets::index', compact('assets', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = FixedAssetCategory::all();
        return view('fixedassets::create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fixed_assets,code',
            'category_id' => 'nullable|exists:fixed_asset_categories,id',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_value' => 'nullable|numeric|min:0',
            'useful_life_years' => 'nullable|integer|min:1',
            'depreciation_method' => 'nullable|string|in:straight_line,declining_balance',
            'prorata' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['prorata'] = $request->boolean('prorata');

        FixedAsset::create($validated);

        return redirect()->route('fixedassets.index')->with('success', 'Demirbaş başarıyla kaydedildi.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $asset = FixedAsset::with(['category', 'assignments.employee'])->findOrFail($id);
        $employees = Employee::all();
        return view('fixedassets::show', compact('asset', 'employees'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $asset = FixedAsset::findOrFail($id);
        $categories = FixedAssetCategory::all();
        return view('fixedassets::edit', compact('asset', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asset = FixedAsset::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fixed_assets,code,' . $id,
            'category_id' => 'nullable|exists:fixed_asset_categories,id',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_value' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,retired,maintenance,lost',
            'useful_life_years' => 'nullable|integer|min:1',
            'depreciation_method' => 'nullable|string|in:straight_line,declining_balance',
            'prorata' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['prorata'] = $request->boolean('prorata');

        $asset->update($validated);

        return redirect()->route('fixedassets.index')->with('success', 'Demirbaş başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asset = FixedAsset::findOrFail($id);
        $asset->delete();

        return redirect()->route('fixedassets.index')->with('success', 'Demirbaş silindi.');
    }

    public function assign(Request $request, $id)
    {
        $asset = FixedAsset::findOrFail($id);
        
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'assigned_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Close any existing active assignment just in case
        $asset->assignments()->whereNull('returned_at')->update(['returned_at' => now()]);

        // Create new assignment
        $asset->assignments()->create([
            'employee_id' => $validated['employee_id'],
            'assigned_at' => $validated['assigned_at'],
            'notes' => $validated['notes'],
        ]);

        return back()->with('success', 'Demirbaş zimmetlendi.');
    }

    public function returnAsset(Request $request, $id)
    {
        $asset = FixedAsset::findOrFail($id); // 'return' is reserved keyword
        
        // Find current assignment
        $assignment = $asset->assignments()->whereNull('returned_at')->latest()->first();
        
        if ($assignment) {
            $assignment->update(['returned_at' => now()]);
        }

        return back()->with('success', 'Zimmet iade alındı.');
    }

    public function storeMaintenance(Request $request, $id)
    {
        $asset = FixedAsset::findOrFail($id);

        $validated = $request->validate([
            'maintenance_date' => 'required|date',
            'next_maintenance_date' => 'nullable|date|after:maintenance_date',
            'type' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'performed_by' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $asset->maintenances()->create($validated);

        return back()->with('success', 'Bakım kaydı eklendi.');
    }
}
