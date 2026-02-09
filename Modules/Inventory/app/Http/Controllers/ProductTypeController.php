<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Inventory\Models\ProductType;
use Illuminate\Validation\Rule;

class ProductTypeController extends Controller
{
    public function index()
    {
        $types = ProductType::latest()->get();
        return view('inventory::settings.types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:product_types,code',
            'is_active' => 'boolean'
        ]);

        // Auto-generate code if empty
        if (empty($validated['code'])) {
            $validated['code'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $validated['company_id'] = auth()->user()->company_id; // Optional if global

        ProductType::create($validated);

        return redirect()->back()->with('success', 'Ürün türü başarıyla eklendi.');
    }

    public function update(Request $request, ProductType $type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['nullable', 'string', 'max:50', Rule::unique('product_types')->ignore($type->id)],
            'is_active' => 'boolean'
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $type->update($validated);

        return redirect()->back()->with('success', 'Ürün türü güncellendi.');
    }

    public function destroy(ProductType $type)
    {
        if ($type->products()->exists()) {
            return redirect()->back()->with('error', 'Bu türe bağlı ürünler var, silinemez.');
        }

        $type->delete();

        return redirect()->back()->with('success', 'Ürün türü silindi.');
    }
}
