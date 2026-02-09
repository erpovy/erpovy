<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of brands
     */
    public function index()
    {
        $brands = Brand::withCount('products')
            ->orderBy('name')
            ->paginate(20);

        return view('inventory::brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new brand
     */
    public function create()
    {
        return view('inventory::brands.create');
    }

    /**
     * Store a newly created brand
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['is_active'] = $request->has('is_active');

        // Logo yükleme
        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($validated);

        return redirect()->route('inventory.brands.index')
            ->with('success', 'Marka başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified brand
     */
    public function edit(Brand $brand)
    {
        return view('inventory::brands.edit', compact('brand'));
    }

    /**
     * Update the specified brand
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Logo güncelleme
        if ($request->hasFile('logo')) {
            // Eski logoyu sil
            if ($brand->logo_path) {
                Storage::disk('public')->delete($brand->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($validated);

        return redirect()->route('inventory.brands.index')
            ->with('success', 'Marka başarıyla güncellendi.');
    }

    /**
     * Remove the specified brand
     */
    public function destroy(Brand $brand)
    {
        // Ürünleri kontrol et
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'Bu markaya ait ürünler var. Önce ürünleri başka markaya taşıyın.');
        }

        // Logo sil
        if ($brand->logo_path) {
            Storage::disk('public')->delete($brand->logo_path);
        }

        $brand->delete();

        return redirect()->route('inventory.brands.index')
            ->with('success', 'Marka başarıyla silindi.');
    }
}
