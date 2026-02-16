<?php

namespace Modules\Ecommerce\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Ecommerce\Models\EcommercePlatform;
use Modules\Ecommerce\Services\WooCommerceService;

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
            $wcProducts = $service->fetchProducts();
            $count = 0;

            foreach ($wcProducts as $wcProduct) {
                // Determine SKU/Code
                $sku = $wcProduct['sku'] ?: 'WC-' . $wcProduct['id'];

                // Check mapping first
                $mapping = \Modules\Ecommerce\Models\EcommerceMapping::where('ecommerce_platform_id', $platform->id)
                    ->where('external_id', $wcProduct['id'])
                    ->where('mappable_type', \Modules\Inventory\Models\Product::class)
                    ->first();

                if ($mapping) {
                    $product = $mapping->mappable;
                    if ($product) {
                        $product->update([
                            'name' => $wcProduct['name'],
                            'sale_price' => $wcProduct['price'] ?: 0,
                            'description' => strip_tags($wcProduct['description']),
                        ]);
                    }
                } else {
                    // Try to find by SKU in Inventory
                    $product = \Modules\Inventory\Models\Product::where('company_id', $platform->company_id)
                        ->where('code', $sku)
                        ->first();

                    if (!$product) {
                        // Create new product
                        $product = \Modules\Inventory\Models\Product::create([
                            'company_id' => $platform->company_id,
                            'name' => $wcProduct['name'],
                            'code' => $sku,
                            'sale_price' => $wcProduct['price'] ?: 0,
                            'description' => strip_tags($wcProduct['description']),
                            'is_active' => true,
                            'stock_track' => true,
                        ]);
                    }

                    // Create mapping
                    \Modules\Ecommerce\Models\EcommerceMapping::create([
                        'company_id' => $platform->company_id,
                        'ecommerce_platform_id' => $platform->id,
                        'mappable_id' => $product->id,
                        'mappable_type' => \Modules\Inventory\Models\Product::class,
                        'external_id' => $wcProduct['id'],
                        'remote_data' => $wcProduct,
                    ]);
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
