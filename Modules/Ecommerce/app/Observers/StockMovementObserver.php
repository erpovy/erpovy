<?php

namespace Modules\Ecommerce\Observers;

use Modules\Inventory\Models\StockMovement;
use Modules\Ecommerce\Models\EcommerceMapping;
use Modules\Ecommerce\Services\WooCommerceService;
use Modules\Inventory\Models\Product;
use Illuminate\Support\Facades\Log;

class StockMovementObserver
{
    /**
     * Handle the StockMovement "created" event.
     */
    public function created(StockMovement $stockMovement)
    {
        $this->syncStock($stockMovement->product);
    }

    /**
     * Handle the StockMovement "updated" event.
     */
    public function updated(StockMovement $stockMovement)
    {
        $this->syncStock($stockMovement->product);
    }

    /**
     * Handle the StockMovement "deleted" event.
     */
    public function deleted(StockMovement $stockMovement)
    {
        if ($stockMovement->product) {
            $this->syncStock($stockMovement->product);
        }
    }

    /**
     * Sync stock to all mapped ecommerce platforms.
     */
    protected function syncStock(Product $product)
    {
        // Find mappings for this product
        $mappings = EcommerceMapping::where('mappable_id', $product->id)
            ->where('mappable_type', Product::class)
            ->get();

        if ($mappings->isEmpty()) {
            return;
        }

        // Calculate total stock in Erpovy (Using the accessor in Product model)
        $totalStock = $product->stock;

        foreach ($mappings as $mapping) {
            $platform = $mapping->platform;
            if ($platform && $platform->status === 'active' && $platform->type === 'woocommerce') {
                try {
                    $service = new WooCommerceService($platform);
                    $service->updateStock($mapping->external_id, (int)$totalStock);
                    
                    Log::info("Stock Synced: Product #{$product->id} -> WooCommerce #{$mapping->external_id}. New Quantity: {$totalStock}");
                } catch (\Exception $e) {
                    Log::error("Stock Sync Error for Product #{$product->id} on Platform #{$platform->id}: " . $e->getMessage());
                }
            }
        }
    }
}
