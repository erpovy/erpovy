<?php

namespace Modules\Inventory\Services;

use Modules\Inventory\Models\Product;

class StockOptimizationService
{
    protected $analyticsService;

    public function __construct(StockAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Ürün için min/max stok seviyelerini hesapla ve güncelle
     */
    public function calculateAndUpdateMinMaxLevels(Product $product): array
    {
        $dailyAvgSales = $this->analyticsService->calculateDailyAverageSales($product->id, 30);
        $stdDev = $this->analyticsService->calculateStdDeviation($product->id, 30);
        $leadTime = $product->lead_time_days;

        // Güvenlik stoku
        $safetyStock = ($leadTime * $dailyAvgSales) + (1.65 * $stdDev * sqrt($leadTime));
        $safetyStock = max(0, ceil($safetyStock));

        // Minimum stok (Reorder Point)
        $minStock = $safetyStock + ($leadTime * $dailyAvgSales);
        $minStock = max(0, ceil($minStock));

        // Optimal sipariş miktarı (basitleştirilmiş EOQ)
        $orderQty = $this->calculateEconomicOrderQuantity($product, $dailyAvgSales);

        // Maksimum stok
        $maxStock = $minStock + $orderQty;

        // Ürünü güncelle
        $product->update([
            'safety_stock_level' => $safetyStock,
            'reorder_point' => $minStock,
            'min_stock_level' => $minStock,
            'max_stock_level' => $maxStock,
            'last_stock_analysis_at' => now(),
        ]);

        return [
            'safety_stock' => $safetyStock,
            'min_stock' => $minStock,
            'max_stock' => $maxStock,
            'order_quantity' => $orderQty,
        ];
    }

    /**
     * Economic Order Quantity (EOQ) hesapla
     */
    protected function calculateEconomicOrderQuantity(Product $product, float $dailyDemand): int
    {
        $annualDemand = $dailyDemand * 365;
        
        if ($annualDemand <= 0) {
            return 0;
        }

        // Basitleştirilmiş EOQ (gerçek maliyetler olmadan)
        // EOQ = sqrt((2 * D * S) / H)
        // D = yıllık talep, S = sipariş maliyeti, H = elde tutma maliyeti
        
        // Varsayılan değerler
        $orderingCost = 100; // Sipariş başına maliyet
        $holdingCostRate = 0.25; // Yıllık %25 elde tutma maliyeti
        $holdingCost = $product->purchase_price * $holdingCostRate;

        if ($holdingCost <= 0) {
            return (int) ceil($annualDemand / 12); // Aylık talep
        }

        $eoq = sqrt((2 * $annualDemand * $orderingCost) / $holdingCost);
        
        return max(1, (int) ceil($eoq));
    }

    /**
     * Tüm şirket ürünleri için min/max hesapla
     */
    public function optimizeAllProducts(int $companyId): array
    {
        $products = Product::where('company_id', $companyId)
            ->where('stock_track', true)
            ->where('is_active', true)
            ->get();

        $results = [];

        foreach ($products as $product) {
            try {
                $results[$product->id] = $this->calculateAndUpdateMinMaxLevels($product);
            } catch (\Exception $e) {
                $results[$product->id] = ['error' => $e->getMessage()];
            }
        }

        return $results;
    }

    /**
     * Sipariş önerileri oluştur
     */
    public function generateReplenishmentPlan(int $companyId): array
    {
        $products = Product::where('company_id', $companyId)
            ->where('stock_track', true)
            ->where('is_active', true)
            ->with('stockMovements')
            ->get();

        $recommendations = [];

        foreach ($products as $product) {
            $currentStock = $product->stock;
            $reorderPoint = $product->reorder_point ?? $product->min_stock_level;

            if ($reorderPoint && $currentStock <= $reorderPoint) {
                $recommendedQty = $this->analyticsService->calculateRecommendedOrderQty($product->id);
                $recommendedDate = $this->analyticsService->calculateRecommendedOrderDate($product->id);

                $recommendations[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                    'current_stock' => $currentStock,
                    'reorder_point' => $reorderPoint,
                    'recommended_qty' => $recommendedQty,
                    'recommended_date' => $recommendedDate,
                    'priority' => $this->calculatePriority($product, $currentStock, $reorderPoint),
                ];
            }
        }

        // Önceliğe göre sırala
        usort($recommendations, function($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });

        return $recommendations;
    }

    /**
     * Sipariş önceliği hesapla
     */
    protected function calculatePriority(Product $product, int $currentStock, int $reorderPoint): int
    {
        $stockRatio = $reorderPoint > 0 ? ($currentStock / $reorderPoint) : 1;
        
        // ABC sınıfı ağırlığı
        $abcWeight = match($product->abc_classification) {
            'A' => 3,
            'B' => 2,
            'C' => 1,
            default => 1,
        };

        // Öncelik: düşük stok oranı * ABC ağırlığı
        return (int) ((1 - $stockRatio) * 100 * $abcWeight);
    }
}
