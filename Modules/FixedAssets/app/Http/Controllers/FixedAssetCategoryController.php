<?php

namespace Modules\FixedAssets\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FixedAssets\Models\FixedAssetCategory;

class FixedAssetCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = FixedAssetCategory::withCount('assets')->latest()->get();
        return view('fixedassets::categories.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        FixedAssetCategory::create($validated);

        return back()->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = FixedAssetCategory::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return back()->with('success', 'Kategori güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = FixedAssetCategory::findOrFail($id);
        
        if ($category->assets()->exists()) {
            return back()->with('error', 'Bu kategoriye ait demirbaşlar olduğu için silinemez.');
        }

        $category->delete();

        return back()->with('success', 'Kategori silindi.');
    }
}
