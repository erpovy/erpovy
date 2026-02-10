<?php

namespace Modules\Inventory\Services;

use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockMovement;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockAnalyticsService
{
    /**
     * Ürün için günlük ortalama satış hesapla
     */
    public function calculateDailyAverageSales(int $productId, int $days = 30): float
    {
        $startDate = now()->subDays($days);
        
        // Faturalardan satış verilerini al
        $totalSales = InvoiceItem::whereHas('invoice', function($query) use ($startDate) {
                $query->where('invoice_type', 'SATIS')
                      ->where('issue_date', '>=', $startDate)
                      ->where('status', '!=', 'cancelled');
            })
            ->where('product_id', $productId)
            ->sum('quantity');
        
        return $totalSales / $days;
    }

    /**
     * Haftalık ortalama satış
     */
    public function calculateWeeklyAverageSales(int $productId, int $weeks = 4): float
    {
        return $this->calculateDailyAverageSales($productId, $weeks * 7) * 7;
    }

    /**
     * Aylık ortalama satış
     */
    public function calculateMonthlyAverageSales(int $productId, int $months = 3): float
    {
        return $this->calculateDailyAverageSales($productId, $months * 30) * 30;
    }

    /**
     * Satış trendini tespit et
     */
    public function detectSalesTrend(int $productId): string
    {
        $last30Days = $this->calculateDailyAverageSales($productId, 30);
        $previous30Days = $this->calculateDailyAverageSales($productId, 60) - $last30Days;
        
        if ($previous30Days == 0) {
            return 'stable';
        }
        
        $changePercent = (($last30Days - $previous30Days) / $previous30Days) * 100;
        
        if ($changePercent > 10) {
            return 'increasing';
        } elseif ($changePercent < -10) {
            return 'decreasing';
        }
        
        return 'stable';
    }

    /**
     * Standart sapma hesapla (talep dalgalanması için)
     */
    public function calculateStdDeviation(int $productId, int $days = 30): float
    {
        $startDate = now()->subDays($days);
        
        // Günlük satışları al
        $dailySales = InvoiceItem::whereHas('invoice', function($query) use ($startDate) {
                $query->where('invoice_type', 'SATIS')
                      ->where('issue_date', '>=', $startDate)
                      ->where('status', '!=', 'cancelled');
            })
            ->where('product_id', $productId)
            ->selectRaw('DATE(invoices.issue_date) as date, SUM(quantity) as total')
            ->join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->groupBy('date')
            ->pluck('total')
            ->toArray();
        
        if (count($dailySales) < 2) {
            return 0;
        }
        
        $mean = array_sum($dailySales) / count($dailySales);
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $dailySales)) / count($dailySales);
        
        return sqrt($variance);
    }

    /**
     * Stok devir hızını hesapla (yıllık)
     */
    public function calculateStockTurnover(int $productId, int $days = 365): float
    {
        $product = Product::find($productId);
        if (!$product) return 0;
        
        $startDate = now()->subDays($days);
        
        // COGS (Cost of Goods Sold) - Satılan malın maliyeti
        $cogs = StockMovement::where('product_id', $productId)
            ->where('type', 'out')
            ->where('created_at', '>=', $startDate)
            ->sum(DB::raw('quantity * COALESCE(unit_cost, 0)'));
        
        // Ortalama stok değeri
        $currentStock = $product->stock;
        $avgStockValue = $currentStock * $product->purchase_price;
        
        if ($avgStockValue == 0) {
            return 0;
        }
        
        return $cogs / $avgStockValue;
    }

    /**
     * Kaç günlük stok kaldığını hesapla
     */
    public function calculateDaysOfStock(int $productId): int
    {
        $product = Product::find($productId);
        if (!$product) return 0;
        
        $dailyAvgSales = $this->calculateDailyAverageSales($productId, 30);
        
        if ($dailyAvgSales == 0) {
            return 999; // Satış yok, sonsuz gün
        }
        
        return (int) ($product->stock / $dailyAvgSales);
    }

    /**
     * Stok bitme tarihini tahmin et
     */
    public function predictStockoutDate(int $productId, ?int $warehouseId = null): ?Carbon
    {
        $daysOfStock = $this->calculateDaysOfStock($productId);
        
        if ($daysOfStock >= 999) {
            return null; // Satış yok
        }
        
        return now()->addDays($daysOfStock);
    }

    /**
     * Stok bitme risk skoru (0-100)
     */
    public function calculateStockoutRisk(int $productId): int
    {
        $daysOfStock = $this->calculateDaysOfStock($productId);
        $product = Product::find($productId);
        
        if (!$product || $product->stock == 0) {
            return 100; // Kritik
        }
        
        if ($daysOfStock <= 0) {
            return 100;
        } elseif ($daysOfStock <= 7) {
            return 80;
        } elseif ($daysOfStock <= 14) {
            return 60;
        } elseif ($daysOfStock <= 30) {
            return 40;
        } else {
            return 20;
        }
    }

    /**
     * Aşırı stok risk skoru (0-100)
     */
    public function calculateOverstockRisk(int $productId): int
    {
        $product = Product::find($productId);
        if (!$product || !$product->max_stock_level) {
            return 0;
        }
        
        $currentStock = $product->stock;
        $maxStock = $product->max_stock_level;
        
        if ($currentStock > $maxStock * 2) {
            return 100;
        } elseif ($currentStock > $maxStock * 1.5) {
            return 75;
        } elseif ($currentStock > $maxStock) {
            return 50;
        }
        
        return 0;
    }

    /**
     * Atıl stok risk skoru (0-100)
     */
    public function calculateObsolescenceRisk(int $productId): int
    {
        $lastMovement = StockMovement::where('product_id', $productId)
            ->where('type', 'out')
            ->latest()
            ->first();
        
        if (!$lastMovement) {
            return 100; // Hiç hareket yok
        }
        
        $daysSinceLastMovement = now()->diffInDays($lastMovement->created_at);
        
        if ($daysSinceLastMovement > 180) {
            return 100; // Ölü stok
        } elseif ($daysSinceLastMovement > 90) {
            return 75;
        } elseif ($daysSinceLastMovement > 60) {
            return 50;
        }
        
        return 0;
    }

    /**
     * Velocity sınıfını belirle
     */
    public function determineVelocityClass(int $productId): string
    {
        $turnover = $this->calculateStockTurnover($productId);
        
        if ($turnover >= 12) {
            return 'fast'; // Ayda 1'den fazla devir
        } elseif ($turnover >= 4) {
            return 'medium'; // 3 ayda 1 devir
        } elseif ($turnover >= 1) {
            return 'slow'; // Yılda 1 devir
        }
        
        return 'dead'; // Yılda 1'den az devir
    }

    /**
     * Önerilen sipariş miktarını hesapla (EOQ - Economic Order Quantity)
     */
    public function calculateRecommendedOrderQty(int $productId): int
    {
        $product = Product::find($productId);
        if (!$product) return 0;
        
        $dailyAvgSales = $this->calculateDailyAverageSales($productId, 30);
        $leadTime = $product->lead_time_days;
        
        // Basit EOQ: Lead time boyunca satılacak miktar + güvenlik stoku
        $leadTimeDemand = $dailyAvgSales * $leadTime;
        $safetyStock = $product->safety_stock_level ?? ($leadTimeDemand * 0.5);
        
        return (int) ceil($leadTimeDemand + $safetyStock);
    }

    /**
     * Önerilen sipariş tarihini hesapla
     */
    public function calculateRecommendedOrderDate(int $productId): ?Carbon
    {
        $product = Product::find($productId);
        if (!$product || !$product->reorder_point) {
            return null;
        }
        
        $daysOfStock = $this->calculateDaysOfStock($productId);
        $leadTime = $product->lead_time_days;
        
        // Stok reorder point'e ulaşmadan lead time kadar önce sipariş ver
        $daysUntilReorder = $daysOfStock - $leadTime;
        
        if ($daysUntilReorder <= 0) {
            return now(); // Hemen sipariş ver
        }
        
        return now()->addDays($daysUntilReorder);
    }
}
