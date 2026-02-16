<?php

namespace Modules\Manufacturing\Services;

use Modules\Inventory\Models\Product;
use Modules\Manufacturing\Models\BillOfMaterial;
use Modules\Manufacturing\Models\WorkOrder;
use Modules\Purchasing\Models\PurchaseOrder;
use Modules\Purchasing\Models\PurchaseOrderItem;

class MRPService
{
    /**
     * Calculate net requirements for a company.
     * 
     * @param int $companyId
     * @return array
     */
    public function calculateRequirements(int $companyId)
    {
        $products = Product::where('company_id', $companyId)
            ->where('stock_track', true)
            ->with(['billOfMaterials', 'billOfMaterials.items'])
            ->get();

        $requirementsList = [];
        $rawMaterialNeeds = [];

        foreach ($products as $product) {
            // 1. Current available stock
            $currentStock = $product->stock;

            // 2. Incoming Stock (Work Orders)
            $incomingWorkOrders = WorkOrder::where('product_id', $product->id)
                ->where('company_id', $companyId)
                ->whereIn('status', ['pending', 'in_progress'])
                ->sum('quantity');

            // 3. Incoming Stock (Purchase Orders)
            $incomingPurchaseOrders = PurchaseOrderItem::where('product_id', $product->id)
                ->whereHas('purchaseOrder', function($query) use ($companyId) {
                    $query->where('company_id', $companyId)
                        ->whereIn('status', ['pending', 'approved', 'partial']);
                })
                ->sum('quantity');

            $totalIncoming = $incomingWorkOrders + $incomingPurchaseOrders;
            $projectedStock = $currentStock + $totalIncoming;

            // 4. Check if we need more
            if ($projectedStock < $product->min_stock_level) {
                $deficit = $product->min_stock_level - $currentStock;
                $netRequirement = $product->min_stock_level - $projectedStock;
                
                $hasBOM = $product->billOfMaterials()->where('is_active', true)->exists();
                $suggestionType = $hasBOM ? 'production' : 'purchase';
                
                $requirementsList[] = [
                    'product' => $product,
                    'current_stock' => $currentStock,
                    'min_level' => $product->min_stock_level,
                    'deficit' => $deficit,
                    'incoming' => $totalIncoming,
                    'suggestion_type' => $suggestionType,
                    'suggestion_quantity' => $netRequirement,
                    'components' => $this->explodeBOM($product, $netRequirement)
                ];

                // If production is suggested, track hammadde (raw material) needs
                if ($hasBOM) {
                    foreach ($this->explodeBOM($product, $netRequirement) as $comp) {
                        $pid = $comp['product_id'];
                        if (!isset($rawMaterialNeeds[$pid])) {
                            $rawMaterialNeeds[$pid] = [
                                'product_id' => $pid,
                                'name' => $comp['name'],
                                'quantity' => 0,
                                'unit' => $comp['unit']
                            ];
                        }
                        $rawMaterialNeeds[$pid]['quantity'] += $comp['quantity'];
                    }
                }
            }
        }

        // Stats calculation
        $stats = [
            'open_orders' => WorkOrder::where('company_id', $companyId)->whereIn('status', ['pending', 'in_progress'])->count(),
            'critical_stock' => $products->filter(function($p) { return $p->stock <= $p->critical_stock_level; })->count(),
            'planned_production' => WorkOrder::where('company_id', $companyId)->where('status', 'pending')->sum('quantity'),
        ];

        return [
            'stats' => $stats,
            'requirements' => $requirementsList,
            'raw_material_needs' => array_values($rawMaterialNeeds)
        ];
    }

    /**
     * Explode Bill of Materials for a product.
     * 
     * @param Product $product
     * @param float $neededQuantity
     * @return array
     */
    private function explodeBOM(Product $product, float $neededQuantity)
    {
        $bom = $product->billOfMaterials()->where('is_active', true)->first();
        if (!$bom) return [];

        $components = [];
        foreach ($bom->items as $item) {
            $requiredQty = ($item->quantity * $neededQuantity) * (1 + ($item->wastage_percent / 100));
            $components[] = [
                'product_id' => $item->product_id,
                'name' => $item->product->name ?? 'Unknown',
                'quantity' => $requiredQty,
                'unit' => $item->unit
            ];
        }

        return $components;
    }

    /**
     * Build a grouped report of requirements.
     * 
     * @param array $requirements
     * @return array
     */
    private function buildFinalReport(array $requirements)
    {
        $rawMaterialNeeds = [];
        $productionNeeds = [];

        foreach ($requirements as $req) {
            if ($req['suggestion_type'] === 'production') {
                $productionNeeds[] = $req;
                
                // Track raw materials needed for this production
                foreach ($req['components'] as $comp) {
                    $pid = $comp['product_id'];
                    if (!isset($rawMaterialNeeds[$pid])) {
                        $rawMaterialNeeds[$pid] = [
                            'product_id' => $pid,
                            'name' => $comp['name'],
                            'quantity' => 0,
                            'unit' => $comp['unit']
                        ];
                    }
                    $rawMaterialNeeds[$pid]['quantity'] += $comp['quantity'];
                }
            } else {
                // Direct purchase needed for finished goods or trade goods
                $pid = $req['product_id'];
                if (!isset($rawMaterialNeeds[$pid])) {
                    $rawMaterialNeeds[$pid] = [
                        'product_id' => $pid,
                        'name' => $req['name'],
                        'quantity' => 0,
                        'unit' => $req['product']->unit->name ?? 'Adet' // Simplified
                    ];
                }
                $rawMaterialNeeds[$pid]['quantity'] += $req['net_requirement'];
            }
        }

        return [
            'production' => $productionNeeds,
            'purchase' => array_values($rawMaterialNeeds)
        ];
    }
}
