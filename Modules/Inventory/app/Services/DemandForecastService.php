<?php

namespace Modules\Inventory\Services;

use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\SeasonalPattern;
use Modules\Accounting\Models\InvoiceItem;
use Carbon\Carbon;

class DemandForecastService
{
    protected $analyticsService;

    public function __construct(StockAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Talep tahmini yap (gelecek N gün için)
     */
    public function forecastDemand(int $productId, int $days = 30): array
    {
        $product = Product::find($productId);
        if (!$product) {
            return [];
        }

        // Geçmiş veri miktarına göre yöntem seç
        $dataMonths = $this->getAvailableDataMonths($productId);
        
        if ($dataMonths < 3) {
            return $this->simpleMovingAverage($productId, $days);
        } elseif ($dataMonths < 12) {
            return $this->weightedMovingAverage($productId, $days);
        } else {
            return $this->seasonalForecast($productId, $days);
        }
    }

    /**
     * Basit hareketli ortalama (< 3 ay veri)
     */
    protected function simpleMovingAverage(int $productId, int $days): array
    {
        $dailyAvg = $this->analyticsService->calculateDailyAverageSales($productId, 30);
        
        $forecast = [];
        for ($i = 1; $i <= $days; $i++) {
            $forecast[] = [
                'date' => now()->addDays($i)->format('Y-m-d'),
                'predicted_demand' => round($dailyAvg, 2),
                'confidence' => 0.6, // Düşük güven
            ];
        }
        
        return $forecast;
    }

    /**
     * Ağırlıklı hareketli ortalama (3-12 ay veri)
     */
    protected function weightedMovingAverage(int $productId, int $days): array
    {
        // Son 7, 14, 30 günlük ortalamaları al
        $last7Days = $this->analyticsService->calculateDailyAverageSales($productId, 7);
        $last14Days = $this->analyticsService->calculateDailyAverageSales($productId, 14);
        $last30Days = $this->analyticsService->calculateDailyAverageSales($productId, 30);
        
        // Ağırlıklı ortalama: Son günlere daha fazla ağırlık
        $weightedAvg = ($last7Days * 0.5) + ($last14Days * 0.3) + ($last30Days * 0.2);
        
        // Trend faktörü
        $trend = $this->analyticsService->detectSalesTrend($productId);
        $trendMultiplier = match($trend) {
            'increasing' => 1.1,
            'decreasing' => 0.9,
            default => 1.0,
        };
        
        $forecast = [];
        for ($i = 1; $i <= $days; $i++) {
            $predicted = $weightedAvg * pow($trendMultiplier, $i / 30);
            
            $forecast[] = [
                'date' => now()->addDays($i)->format('Y-m-d'),
                'predicted_demand' => round($predicted, 2),
                'confidence' => 0.75,
            ];
        }
        
        return $forecast;
    }

    /**
     * Sezonsal tahmin (> 12 ay veri)
     */
    protected function seasonalForecast(int $productId, int $days): array
    {
        $baseAvg = $this->analyticsService->calculateDailyAverageSales($productId, 30);
        
        $forecast = [];
        for ($i = 1; $i <= $days; $i++) {
            $futureDate = now()->addDays($i);
            $month = $futureDate->month;
            
            // Sezonsal indeks al
            $seasonalIndex = $this->getSeasonalIndex($productId, $month);
            
            // Trend faktörü
            $trend = $this->analyticsService->detectSalesTrend($productId);
            $trendMultiplier = match($trend) {
                'increasing' => 1.05,
                'decreasing' => 0.95,
                default => 1.0,
            };
            
            $predicted = $baseAvg * $seasonalIndex * pow($trendMultiplier, $i / 30);
            
            $forecast[] = [
                'date' => $futureDate->format('Y-m-d'),
                'predicted_demand' => round($predicted, 2),
                'confidence' => 0.85,
            ];
        }
        
        return $forecast;
    }

    /**
     * Sezonsal indeks al veya hesapla
     */
    protected function getSeasonalIndex(int $productId, int $month): float
    {
        $pattern = SeasonalPattern::where('product_id', $productId)
            ->where('month', $month)
            ->first();
        
        if ($pattern) {
            return $pattern->seasonal_index;
        }
        
        // Yoksa hesapla ve kaydet
        return $this->calculateSeasonalIndex($productId, $month);
    }

    /**
     * Sezonsal indeks hesapla
     */
    protected function calculateSeasonalIndex(int $productId, int $month): float
    {
        // Son 2 yıldaki bu ay satışlarını al
        $monthlySales = [];
        for ($year = 0; $year < 2; $year++) {
            $startDate = now()->subYears($year)->month($month)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            
            $sales = InvoiceItem::whereHas('invoice', function($query) use ($startDate, $endDate) {
                    $query->where('type', 'sales')
                          ->whereBetween('invoice_date', [$startDate, $endDate])
                          ->where('status', '!=', 'cancelled');
                })
                ->where('product_id', $productId)
                ->sum('quantity');
            
            if ($sales > 0) {
                $monthlySales[] = $sales;
            }
        }
        
        if (empty($monthlySales)) {
            return 1.0; // Veri yok, normal kabul et
        }
        
        // Yıllık ortalamaya göre indeks hesapla
        $yearlyAvg = $this->analyticsService->calculateMonthlyAverageSales($productId, 12);
        $monthAvg = array_sum($monthlySales) / count($monthlySales);
        
        $index = $yearlyAvg > 0 ? $monthAvg / $yearlyAvg : 1.0;
        
        // Kaydet
        SeasonalPattern::updateOrCreate(
            [
                'company_id' => auth()->user()->company_id,
                'product_id' => $productId,
                'month' => $month,
            ],
            [
                'seasonal_index' => $index,
                'confidence_level' => count($monthlySales) / 2, // 2 yıl veri = 1.0 güven
            ]
        );
        
        return $index;
    }

    /**
     * Kaç aylık veri var?
     */
    protected function getAvailableDataMonths(int $productId): int
    {
        $firstSale = InvoiceItem::whereHas('invoice', function($query) {
                $query->where('type', 'sales')
                      ->where('status', '!=', 'cancelled');
            })
            ->where('product_id', $productId)
            ->oldest()
            ->first();
        
        if (!$firstSale) {
            return 0;
        }
        
        return now()->diffInMonths($firstSale->created_at);
    }

    /**
     * Tüm sezonsal pattern'leri yeniden hesapla
     */
    public function recalculateSeasonalPatterns(int $companyId): void
    {
        $products = Product::where('company_id', $companyId)->get();
        
        foreach ($products as $product) {
            $dataMonths = $this->getAvailableDataMonths($product->id);
            
            if ($dataMonths >= 12) {
                for ($month = 1; $month <= 12; $month++) {
                    $this->calculateSeasonalIndex($product->id, $month);
                }
            }
        }
    }
}
