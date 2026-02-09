<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::with(['parent', 'children'])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return view('inventory::categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
            ->orWhereNotNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('inventory::categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['is_active'] = $request->has('is_active');

        Category::create($validated);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('inventory::categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Alt kategorileri kontrol et
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Bu kategorinin alt kategorileri var. Önce onları silin.');
        }

        // Ürünleri kontrol et
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Bu kategoriye ait ürünler var. Önce ürünleri başka kategoriye taşıyın.');
        }

        $category->delete();

        return redirect()->route('inventory.categories.index')
            ->with('success', 'Kategori başarıyla silindi.');
    }

    /**
     * Update category order (for drag-drop)
     */
    public function updateOrder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
