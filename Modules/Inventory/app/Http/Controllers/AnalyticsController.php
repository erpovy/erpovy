<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Services\StockAnalyticsService;
use Modules\Inventory\Services\DemandForecastService;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\StockAnalytic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsController extends Controller
{
    protected $analyticsService;
    protected $forecastService;

    public function __construct(
        StockAnalyticsService $analyticsService,
        DemandForecastService $forecastService
    ) {
        $this->analyticsService = $analyticsService;
        $this->forecastService = $forecastService;
    }

    /**
     * Ana analitik dashboard
     */
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;
        
        // Genel metrikler - Stock movements'tan hesapla
        $products = Product::where('company_id', $companyId)
            ->with('stockMovements')
            ->get();
        
        $totalStockValue = $products->sum(function($product) {
            $stock = $product->stockMovements()->sum('quantity');
            return $stock * $product->purchase_price;
        });
        
        $criticalProducts = $products->filter(function($product) {
                return $this->analyticsService->calculateStockoutRisk($product->id) >= 60;
            })
            ->count();
        
        $obsoleteProducts = $products->filter(function($product) {
                return $this->analyticsService->calculateObsolescenceRisk($product->id) >= 75;
            })
            ->count();
        
        $avgTurnover = $products->map(function($product) {
                return $this->analyticsService->calculateStockTurnover($product->id);
            })
            ->average();
        
        // ABC Dağılımı
        $abcDistribution = Product::where('company_id', $companyId)
            ->whereNotNull('abc_classification')
            ->selectRaw('abc_classification, COUNT(*) as count')
            ->groupBy('abc_classification')
            ->pluck('count', 'abc_classification');
        
        // Kritik ürünler listesi
        $criticalProductsList = Product::where('company_id', $companyId)
            ->where('stock_track', true)
            ->with(['category', 'brand', 'unit', 'stockMovements'])
            ->get()
            ->map(function($product) {
                $currentStock = $product->stock; // Uses accessor
                $stockoutRisk = $this->analyticsService->calculateStockoutRisk($product->id);
                $overstockRisk = $this->analyticsService->calculateOverstockRisk($product->id);
                $obsolescenceRisk = $this->analyticsService->calculateObsolescenceRisk($product->id);
                $daysOfStock = $this->analyticsService->calculateDaysOfStock($product->id);
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'category' => $product->category?->name,
                    'unit' => $product->unit?->symbol ?? $product->unit?->name,
                    'current_stock' => $currentStock,
                    'min_stock' => $product->min_stock_level,
                    'max_stock' => $product->max_stock_level,
                    'abc_class' => $product->abc_classification,
                    'days_of_stock' => $daysOfStock,
                    'daily_avg_sales' => round($this->analyticsService->calculateDailyAverageSales($product->id, 30), 2),
                    'stockout_risk' => $stockoutRisk,
                    'overstock_risk' => $overstockRisk,
                    'obsolescence_risk' => $obsolescenceRisk,
                    'turnover' => $this->analyticsService->calculateStockTurnover($product->id),
                    'status' => $this->getStockStatus($product, $stockoutRisk, $overstockRisk, $obsolescenceRisk),
                ];
            })
            ->sortByDesc('stockout_risk')
            ->values();
        
        return view('inventory::analytics.index', compact(
            'totalStockValue',
            'criticalProducts',
            'obsoleteProducts',
            'avgTurnover',
            'abcDistribution',
            'criticalProductsList'
        ));
    }

    /**
     * Ürün detay analizi
     */
    public function productDetail($productId)
    {
        $product = Product::with(['category', 'brand', 'unit'])->findOrFail($productId);
        
        // Metrikler
        $metrics = [
            'daily_avg_sales' => $this->analyticsService->calculateDailyAverageSales($productId, 30),
            'weekly_avg_sales' => $this->analyticsService->calculateWeeklyAverageSales($productId, 4),
            'monthly_avg_sales' => $this->analyticsService->calculateMonthlyAverageSales($productId, 3),
            'sales_trend' => $this->analyticsService->detectSalesTrend($productId),
            'stock_turnover' => $this->analyticsService->calculateStockTurnover($productId),
            'days_of_stock' => $this->analyticsService->calculateDaysOfStock($productId),
            'velocity_class' => $this->analyticsService->determineVelocityClass($productId),
            'stockout_risk' => $this->analyticsService->calculateStockoutRisk($productId),
            'overstock_risk' => $this->analyticsService->calculateOverstockRisk($productId),
            'obsolescence_risk' => $this->analyticsService->calculateObsolescenceRisk($productId),
            'recommended_order_qty' => $this->analyticsService->calculateRecommendedOrderQty($productId),
            'recommended_order_date' => $this->analyticsService->calculateRecommendedOrderDate($productId),
            'predicted_stockout_date' => $this->analyticsService->predictStockoutDate($productId),
        ];
        
        // Talep tahmini (30 gün)
        $forecast = $this->forecastService->forecastDemand($productId, 30);
        
        // Son 6 ay satış geçmişi
        $salesHistory = $this->getSalesHistory($productId, 180);
        
        return view('inventory::analytics.product-detail', compact('product', 'metrics', 'forecast', 'salesHistory'));
    }

    /**
     * Excel export
     */
    public function exportExcel(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $products = $this->getAnalyticsData($companyId);
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'Ürün Kodu');
        $sheet->setCellValue('B1', 'Ürün Adı');
        $sheet->setCellValue('C1', 'Kategori');
        $sheet->setCellValue('D1', 'Mevcut Stok');
        $sheet->setCellValue('E1', 'Min Stok');
        $sheet->setCellValue('F1', 'Max Stok');
        $sheet->setCellValue('G1', 'ABC Sınıfı');
        $sheet->setCellValue('H1', 'Günlük Ort. Satış');
        $sheet->setCellValue('I1', 'Stok Devir Hızı');
        $sheet->setCellValue('J1', 'Kaç Günlük Stok');
        $sheet->setCellValue('K1', 'Stok Bitme Riski');
        $sheet->setCellValue('L1', 'Aşırı Stok Riski');
        $sheet->setCellValue('M1', 'Atıl Stok Riski');
        $sheet->setCellValue('N1', 'Durum');
        
        // Data
        $row = 2;
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product['code']);
            $sheet->setCellValue('B' . $row, $product['name']);
            $sheet->setCellValue('C' . $row, $product['category']);
            $sheet->setCellValue('D' . $row, $product['current_stock']);
            $sheet->setCellValue('E' . $row, $product['min_stock']);
            $sheet->setCellValue('F' . $row, $product['max_stock']);
            $sheet->setCellValue('G' . $row, $product['abc_class']);
            $sheet->setCellValue('H' . $row, $product['daily_avg_sales']);
            $sheet->setCellValue('I' . $row, $product['turnover']);
            $sheet->setCellValue('J' . $row, $product['days_of_stock']);
            $sheet->setCellValue('K' . $row, $product['stockout_risk']);
            $sheet->setCellValue('L' . $row, $product['overstock_risk']);
            $sheet->setCellValue('M' . $row, $product['obsolescence_risk']);
            $sheet->setCellValue('N' . $row, $product['status']);
            $row++;
        }
        
        // Style
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
        
        $writer = new Xlsx($spreadsheet);
        $fileName = 'stok-analizi-' . now()->format('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);
        
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * PDF export
     */
    public function exportPdf(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $products = $this->getAnalyticsData($companyId);
        
        $pdf = Pdf::loadView('inventory::analytics.pdf-report', compact('products'));
        
        return $pdf->download('stok-analizi-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Dashboard widget data (AJAX)
     */
    public function dashboardWidget()
    {
        $companyId = auth()->user()->company_id;
        
        $criticalProducts = Product::where('company_id', $companyId)
            ->where('stock_track', true)
            ->where('is_active', true)
            ->get()
            ->map(function($product) {
                $risk = $this->analyticsService->calculateStockoutRisk($product->id);
                $daysOfStock = $this->analyticsService->calculateDaysOfStock($product->id);
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'current_stock' => $product->stock,
                    'min_stock' => $product->min_stock_level,
                    'days_of_stock' => $daysOfStock,
                    'risk_score' => $risk,
                    'risk_level' => $this->getRiskLevel($risk),
                ];
            })
            ->filter(function($item) {
                return $item['risk_score'] >= 60;
            })
            ->sortByDesc('risk_score')
            ->take(10)
            ->values();
        
        return response()->json($criticalProducts);
    }

    /**
     * Helper: Analitik veri hazırla
     */
    protected function getAnalyticsData($companyId)
    {
        return Product::where('company_id', $companyId)
            ->with(['category', 'brand'])
            ->get()
            ->map(function($product) {
                return [
                    'code' => $product->code,
                    'name' => $product->name,
                    'category' => $product->category?->name,
                    'current_stock' => $product->stock,
                    'min_stock' => $product->min_stock_level,
                    'max_stock' => $product->max_stock_level,
                    'abc_class' => $product->abc_classification,
                    'daily_avg_sales' => round($this->analyticsService->calculateDailyAverageSales($product->id, 30), 2),
                    'turnover' => round($this->analyticsService->calculateStockTurnover($product->id), 2),
                    'days_of_stock' => $this->analyticsService->calculateDaysOfStock($product->id),
                    'stockout_risk' => $this->analyticsService->calculateStockoutRisk($product->id),
                    'overstock_risk' => $this->analyticsService->calculateOverstockRisk($product->id),
                    'obsolescence_risk' => $this->analyticsService->calculateObsolescenceRisk($product->id),
                    'status' => $this->getStockStatus(
                        $product,
                        $this->analyticsService->calculateStockoutRisk($product->id),
                        $this->analyticsService->calculateOverstockRisk($product->id),
                        $this->analyticsService->calculateObsolescenceRisk($product->id)
                    ),
                ];
            });
    }

    /**
     * Helper: Satış geçmişi
     */
    protected function getSalesHistory($productId, $days)
    {
        // TODO: Implement sales history from invoices
        return [];
    }

    /**
     * Helper: Stok durumu
     */
    protected function getStockStatus($product, $stockoutRisk, $overstockRisk, $obsolescenceRisk)
    {
        if ($stockoutRisk >= 80) return 'Kritik - Stok Tükeniyor';
        if ($stockoutRisk >= 60) return 'Düşük Stok';
        if ($overstockRisk >= 75) return 'Aşırı Stok';
        if ($obsolescenceRisk >= 75) return 'Atıl Stok';
        return 'Normal';
    }

    /**
     * Helper: Risk seviyesi
     */
    protected function getRiskLevel($score)
    {
        if ($score >= 80) return 'critical';
        if ($score >= 60) return 'high';
        if ($score >= 40) return 'medium';
        return 'low';
    }
}
