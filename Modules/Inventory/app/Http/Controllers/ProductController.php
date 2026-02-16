<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\Inventory\Models\ProductType;
use Modules\Inventory\Models\Product;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function importForm()
    {
        return view('inventory::products.import');
    }

    public function downloadSampleCsv()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=urun_sablonu.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['code', 'name', 'type_code', 'sale_price', 'purchase_price', 'vat_rate', 'stock_track'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Fetch type codes for examples
            $goodType = ProductType::where('code', 'good')->first()->code ?? 'good';
            $serviceType = ProductType::where('code', 'service')->first()->code ?? 'service';

            $example1 = ['URUN001', 'Örnek Ürün', $goodType, 100.00, 80.00, 20, 1];
            fputcsv($file, $example1);

            $example2 = ['HIZMET001', 'Örnek Hizmet', $serviceType, 500.00, 0.00, 20, 0];
            fputcsv($file, $example2);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCsv()
    {
        $products = \Modules\Inventory\Models\Product::with('productType')->get();
        $csvFileName = 'urunler_export_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['code', 'name', 'type_name', 'sale_price', 'purchase_price', 'vat_rate', 'stock_track', 'current_stock'];

        $callback = function() use ($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); 
            
            foreach ($products as $product) {
                $row = [
                    $product->code,
                    $product->name,
                    $product->productType->name ?? 'Bilinmiyor',
                    $product->sale_price,
                    $product->purchase_price,
                    $product->vat_rate ?? 0,
                    $product->stock_track ? '1' : '0',
                    $product->stock
                ];
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $file = $request->file('csv_file');
            $data = array_map('str_getcsv', file($file->getRealPath()));
            
            if (count($data) < 2) {
                return back()->with('error', 'Dosya boş veya hatalı format.');
            }

            $header = array_shift($data); 

            $count = 0;
            // Cache types for performance
            $types = ProductType::pluck('id', 'code')->toArray();
            $defaultTypeId = ProductType::where('code', 'good')->value('id') ?? ProductType::first()->id;

            foreach ($data as $row) {
                if (count($row) < 2) continue;
                
                $code = $row[0] ?? null;
                if (!$code) continue;

                $typeCode = $row[2] ?? 'good';
                $typeId = $types[$typeCode] ?? $defaultTypeId;

                \Modules\Inventory\Models\Product::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $row[1] ?? 'İsimsiz Ürün',
                        'product_type_id' => $typeId,
                        'sale_price' => (float)($row[3] ?? 0),
                        'purchase_price' => (float)($row[4] ?? 0),
                        'vat_rate' => (int)($row[5] ?? 0),
                        'stock_track' => (bool)(int)($row[6] ?? 0),
                    ]
                );
                $count++;
            }

            return redirect()->route('inventory.products.index')->with('success', "$count adet ürün başarıyla içe aktarıldı.");

        } catch (\Exception $e) {
            return back()->with('error', 'Hata oluştu: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $products = Product::with(['unit', 'productType'])
            ->latest()
            ->paginate(10);

        $companyId = auth()->user()->company_id;

        // Dashboard Stats
        $totalProducts = Product::where('company_id', $companyId)->count();
        
        $totalStockValue = \Illuminate\Support\Facades\DB::table('stock_movements')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->where('products.company_id', $companyId)
            ->selectRaw('SUM(stock_movements.quantity) as current_stock, products.purchase_price')
            ->groupBy('products.id', 'products.purchase_price')
            ->get()
            ->sum(function ($row) {
                return $row->current_stock * $row->purchase_price;
            });

        $criticalStockCount = Product::where('company_id', $companyId)
            ->where('stock_track', true)
            ->whereNotNull('min_stock_level')
            ->whereRaw('(SELECT SUM(quantity) FROM stock_movements WHERE product_id = products.id) <= min_stock_level')
            ->count();

        return view('inventory::index', compact('products', 'totalProducts', 'totalStockValue', 'criticalStockCount'));
    }

    public function create()
    {
        $units = \Modules\Inventory\Models\Unit::all();
        $categories = \Modules\Inventory\Models\Category::all();
        $brands = \Modules\Inventory\Models\Brand::all();
        $productTypes = ProductType::where('is_active', true)->get();
        
        return view('inventory::create', compact('units', 'categories', 'brands', 'productTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:product_types,id',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'sale_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'stock_track' => 'boolean',
            'min_stock_level' => 'nullable|numeric|min:0',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        Product::create($validated);

        return redirect()->route('inventory.products.index')
            ->with('success', 'Ürün başarıyla oluşturuldu.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('inventory::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $units = \Modules\Inventory\Models\Unit::all();
        $categories = \Modules\Inventory\Models\Category::all();
        $brands = \Modules\Inventory\Models\Brand::all();
        $productTypes = ProductType::where('is_active', true)->get();
        
        return view('inventory::edit', compact('product', 'units', 'categories', 'brands', 'productTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:product_types,id',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'sale_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'stock_track' => 'boolean',
            'min_stock_level' => 'nullable|numeric|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('inventory.products.index')
            ->with('success', 'Ürün başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('inventory.products.index')
            ->with('success', 'Ürün başarıyla silindi.');
    }

    public function bulkEdit(Request $request)
    {
        $ids = $request->input('product_ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Lütfen en az bir ürün seçin.');
        }

        $products = Product::whereIn('id', $ids)
            ->where('company_id', auth()->user()->company_id)
            ->get();

        $units = \Modules\Inventory\Models\Unit::all();
        $categories = \Modules\Inventory\Models\Category::all();
        $brands = \Modules\Inventory\Models\Brand::all();
        $productTypes = ProductType::where('is_active', true)->get();

        return view('inventory::products.bulk-edit', compact('products', 'units', 'categories', 'brands', 'productTypes'));
    }

    public function bulkUpdate(Request $request)
    {
        $ids = $request->input('product_ids', []);
        $updateFields = $request->input('fields', []);
        
        if (empty($ids) || empty($updateFields)) {
            return redirect()->route('inventory.products.index')->with('error', 'Geçersiz istek.');
        }

        $validated = $request->validate([
            'fields.product_type_id' => 'nullable|exists:product_types,id',
            'fields.category_id' => 'nullable|exists:categories,id',
            'fields.brand_id' => 'nullable|exists:brands,id',
            'fields.unit_id' => 'nullable|exists:units,id',
            'fields.sale_price' => 'nullable|numeric|min:0',
            'fields.purchase_price' => 'nullable|numeric|min:0',
            'fields.vat_rate' => 'nullable|numeric|min:0|max:100',
            'fields.stock_track' => 'nullable|boolean',
        ]);

        // Filter out null values to only update what was provided
        $dataToUpdate = array_filter($validated['fields'], fn($value) => !is_null($value));

        if (!empty($dataToUpdate)) {
            Product::whereIn('id', $ids)
                ->where('company_id', auth()->user()->company_id)
                ->update($dataToUpdate);
        }

        return redirect()->route('inventory.products.index')->with('success', count($ids) . ' ürün başarıyla güncellendi.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('product_ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Lütfen en az bir ürün seçin.');
        }

        $count = Product::whereIn('id', $ids)
            ->where('company_id', auth()->user()->company_id)
            ->delete();

        return redirect()->route('inventory.products.index')->with('success', $count . ' ürün başarıyla silindi.');
    }
}
