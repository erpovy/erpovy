<?php

namespace Modules\Ecommerce\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommercePlatform;
use Modules\Ecommerce\Services\WooCommerceService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Modules\Inventory\Models\Category;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\StockMovement;
use Modules\Ecommerce\Models\EcommerceMapping;

class EcommerceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $platforms = EcommercePlatform::all();
        return view('ecommerce::index', compact('platforms'));
    }

    /**
     * Sync products from the platform.
     */
    public function syncProducts(EcommercePlatform $platform)
    {
        $service = new WooCommerceService($platform);
        try {
            $defaultWarehouse = \Modules\Inventory\Models\Warehouse::where('company_id', $platform->company_id)
                ->where('is_active', true)
                ->orderBy('is_default', 'desc')
                ->first();

            $wcProducts = $service->fetchAllProducts();
            $count = 0;

            foreach ($wcProducts as $wcProduct) {
                // Determine SKU/Code
                $sku = $wcProduct['sku'] ?: 'WC-' . $wcProduct['id'];
                $manageStock = $wcProduct['manage_stock'] ?? false;
                $wcStock = $wcProduct['stock_quantity'] ?? 0;

                // Handle Category
                $categoryId = null;
                if (!empty($wcProduct['categories'])) {
                    $wcCategory = $wcProduct['categories'][0]; // Take the first category
                    $category = Category::where('company_id', $platform->company_id)
                        ->where('name', $wcCategory['name'])
                        ->first();
                    
                    if (!$category) {
                        $category = Category::create([
                            'company_id' => $platform->company_id,
                            'name' => $wcCategory['name'],
                            'is_active' => true
                        ]);
                    }
                    $categoryId = $category->id;
                }

                // Handle Image
                $imagePath = null;
                $syncImages = $platform->settings['sync_images'] ?? true;

                if ($syncImages && !empty($wcProduct['images'])) {
                    $imageUrl = $wcProduct['images'][0]['src'];
                    try {
                        $imageContents = Http::get($imageUrl)->body();
                        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                        $filename = 'products/' . Str::slug($wcProduct['name']) . '-' . time() . '.' . $extension;
                        
                        // Attempt to save image with disk space check
                        if (!Storage::disk('public')->put($filename, $imageContents)) {
                            throw new \Exception('Failed to write image to disk (possibly disk full).');
                        }
                        $imagePath = $filename;
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning('WC Image Sync Error: ' . $e->getMessage() . ' for product ' . $wcProduct['name']);
                        // Continue sync without image
                    }
                }

                // Prepare Data
                $productData = [
                    'name' => $wcProduct['name'],
                    'sale_price' => $wcProduct['price'] ?: 0,
                    'description' => strip_tags($wcProduct['description']),
                    'stock_track' => $manageStock,
                    'category_id' => $categoryId,
                    'weight' => $wcProduct['weight'] ?: null,
                    'dimensions' => [
                        'length' => $wcProduct['dimensions']['length'] ?? null,
                        'width' => $wcProduct['dimensions']['width'] ?? null,
                        'height' => $wcProduct['dimensions']['height'] ?? null,
                    ],
                ];

                if ($imagePath) {
                    $productData['image_path'] = $imagePath;
                }

                // Check mapping first
                $mapping = EcommerceMapping::where('ecommerce_platform_id', $platform->id)
                    ->where('external_id', $wcProduct['id'])
                    ->where('mappable_type', Product::class)
                    ->first();

                if ($mapping) {
                    $product = $mapping->mappable()->withTrashed()->first();
                    if ($product) {
                        if ($product->trashed()) {
                            $product->restore();
                        }
                        $product->update($productData);
                    }
                } else {
                    // Try to find by SKU in Inventory (including trashed)
                    $product = Product::where('company_id', $platform->company_id)
                        ->where('code', $sku)
                        ->withTrashed()
                        ->first();

                    if ($product) {
                        if ($product->trashed()) {
                            $product->restore();
                        }
                        $product->update($productData);
                    } else {
                        // Create new product
                        $product = Product::create(array_merge($productData, [
                            'company_id' => $platform->company_id,
                            'code' => $sku,
                            'is_active' => true,
                        ]));
                    }

                    // Create mapping
                    EcommerceMapping::create([
                        'company_id' => $platform->company_id,
                        'ecommerce_platform_id' => $platform->id,
                        'mappable_id' => $product->id,
                        'mappable_type' => Product::class,
                        'external_id' => $wcProduct['id'],
                        'remote_data' => $wcProduct,
                    ]);
                }

                if ($product && $manageStock && $defaultWarehouse) {
                    $localStock = $product->stock;
                    $diff = $wcStock - $localStock;

                    if ($diff != 0) {
                        StockMovement::create([
                            'company_id' => $platform->company_id,
                            'user_id' => auth()->id() ?? 1,
                            'product_id' => $product->id,
                            'warehouse_id' => $defaultWarehouse->id,
                            'quantity' => $diff,
                            'type' => $diff > 0 ? 'in' : 'out',
                            'notes' => 'WooCommerce Senkronizasyonu (Stok Güncelleme)',
                            'reference' => 'WC-' . $wcProduct['id'],
                        ]);
                    }
                }

                $count++;
            }
            
            $platform->update(['last_sync_at' => now()]);
            
            return back()->with('success', $count . ' ürün başarıyla senkronize edildi.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WooCommerce Sync Products Error: ' . $e->getMessage());
            return back()->with('error', 'Ürün senkronizasyon hatası: ' . $e->getMessage());
        }
    }

    /**
     * Sync orders from the platform.
     */
    public function syncOrders(EcommercePlatform $platform)
    {
        $service = new WooCommerceService($platform);
        try {
            $count = $service->syncOrders();
            return back()->with('success', $count . ' yeni sipariş başarıyla senkronize edildi.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WooCommerce Sync Orders Error: ' . $e->getMessage());
            return back()->with('error', 'Sipariş senkronizasyon hatası: ' . $e->getMessage());
        }
    }
}
