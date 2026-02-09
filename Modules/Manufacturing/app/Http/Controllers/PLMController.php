<?php

namespace Modules\Manufacturing\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Manufacturing\Models\BillOfMaterial;
use Modules\Manufacturing\Models\BillOfMaterialItem;
use Modules\Inventory\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class PLMController extends Controller
{
    public function index()
    {
        $scope = BillOfMaterial::where('company_id', auth()->user()->company_id);
        
        $stats = [
            'total' => (clone $scope)->count(),
            'active' => (clone $scope)->where('is_active', true)->count(),
        ];

        $boms = $scope->with(['product', 'items'])
            ->latest()
            ->paginate(10);
            
        $products = Product::where('company_id', auth()->user()->company_id)->get();

        return view('manufacturing::plm.index', compact('boms', 'products', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.0001',
        ]);

        $bom = BillOfMaterial::create([
            'company_id' => auth()->user()->company_id,
            'product_id' => $request->product_id,
            'code' => 'BOM-' . strtoupper(Str::random(6)),
            'name' => $request->name ?? 'Yeni Reçete',
            'version' => 'v1.0',
            'is_active' => true,
        ]);

        foreach ($request->items as $item) {
            BillOfMaterialItem::create([
                'bill_of_material_id' => $bom->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'] ?? 'adet',
                'wastage_percent' => $item['wastage_percent'] ?? 0,
            ]);
        }

        return redirect()->route('manufacturing.plm.index')->with('success', 'Ürün reçetesi başarıyla oluşturuldu.');
    }
}
