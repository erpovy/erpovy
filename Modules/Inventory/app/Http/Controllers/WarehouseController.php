<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = \Modules\Inventory\Models\Warehouse::where('company_id', auth()->user()->company_id)
            ->with('manager')
            ->get();
            
        return view('inventory::warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventory::warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        if (!empty($validated['is_default'])) {
            \Modules\Inventory\Models\Warehouse::where('company_id', $validated['company_id'])->update(['is_default' => false]);
        }

        \Modules\Inventory\Models\Warehouse::create($validated);

        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'Depo başarıyla oluşturuldu.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $warehouse = \Modules\Inventory\Models\Warehouse::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('inventory::warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $warehouse = \Modules\Inventory\Models\Warehouse::where('company_id', auth()->user()->company_id)->findOrFail($id);
        return view('inventory::warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $warehouse = \Modules\Inventory\Models\Warehouse::where('company_id', auth()->user()->company_id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        if (!empty($validated['is_default'])) {
            \Modules\Inventory\Models\Warehouse::where('company_id', $warehouse->company_id)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        $warehouse->update($validated);

        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'Depo başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $warehouse = \Modules\Inventory\Models\Warehouse::where('company_id', auth()->user()->company_id)->findOrFail($id);
        
        // Varsayılan depo silinemez
        if ($warehouse->is_default) {
            return back()->with('error', 'Varsayılan depo silinemez!');
        }

        $warehouse->delete();

        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'Depo başarıyla silindi.');
    }
}
