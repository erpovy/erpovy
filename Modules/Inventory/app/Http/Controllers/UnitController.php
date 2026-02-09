<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Unit;
use Modules\Inventory\Models\UnitConversion;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of units
     */
    public function index()
    {
        $units = Unit::withCount('products')
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(20);

        return view('inventory::units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit
     */
    public function create()
    {
        return view('inventory::units.create');
    }

    /**
     * Store a newly created unit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:20',
            'type' => 'required|in:piece,weight,volume,length,area,other',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['is_base_unit'] = $request->has('is_base_unit');
        $validated['is_active'] = $request->has('is_active');

        Unit::create($validated);

        return redirect()->route('inventory.units.index')
            ->with('success', 'Birim başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified unit
     */
    public function edit(Unit $unit)
    {
        return view('inventory::units.edit', compact('unit'));
    }

    /**
     * Update the specified unit
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:20',
            'type' => 'required|in:piece,weight,volume,length,area,other',
        ]);

        $validated['is_base_unit'] = $request->has('is_base_unit');
        $validated['is_active'] = $request->has('is_active');

        $unit->update($validated);

        return redirect()->route('inventory.units.index')
            ->with('success', 'Birim başarıyla güncellendi.');
    }

    /**
     * Remove the specified unit
     */
    public function destroy(Unit $unit)
    {
        // Ürünleri kontrol et
        if ($unit->products()->count() > 0) {
            return back()->with('error', 'Bu birime ait ürünler var.');
        }

        // Çevirim kurallarını sil
        UnitConversion::where('from_unit_id', $unit->id)
            ->orWhere('to_unit_id', $unit->id)
            ->delete();

        $unit->delete();

        return redirect()->route('inventory.units.index')
            ->with('success', 'Birim başarıyla silindi.');
    }

    /**
     * Show unit conversions
     */
    public function conversions(Unit $unit)
    {
        $conversions = UnitConversion::where('from_unit_id', $unit->id)
            ->with('toUnit')
            ->get();

        $availableUnits = Unit::where('id', '!=', $unit->id)
            ->where('type', $unit->type)
            ->get();

        return view('inventory::units.conversions', compact('unit', 'conversions', 'availableUnits'));
    }

    /**
     * Store unit conversion
     */
    public function storeConversion(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'to_unit_id' => 'required|exists:units,id',
            'multiplier' => 'required|numeric|min:0.000001',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['from_unit_id'] = $unit->id;

        UnitConversion::create($validated);

        return back()->with('success', 'Çevirim kuralı başarıyla eklendi.');
    }

    /**
     * Delete unit conversion
     */
    public function destroyConversion(UnitConversion $conversion)
    {
        $conversion->delete();

        return back()->with('success', 'Çevirim kuralı başarıyla silindi.');
    }
}
