<?php

namespace Modules\Inventory\Services;

use Modules\Inventory\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsNotificationService
{
    protected $analyticsService;
    protected $apiUrl;
    protected $apiKey;

    public function __construct(StockAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
        $this->apiUrl = config('services.sms.url');
        $this->apiKey = config('services.sms.api_key');
    }

    /**
     * Kritik stoklar için SMS gönder
     */
    public function sendCriticalStockAlerts(int $companyId): array
    {
        $criticalProducts = Product::where('company_id', $companyId)
            ->where('stock_track', true)
            ->where('is_active', true)
            ->get()
            ->filter(function($product) {
                $risk = $this->analyticsService->calculateStockoutRisk($product->id);
                return $risk >= 80; // Sadece kritik (80+) için SMS gönder
            });

        $sentMessages = [];

        foreach ($criticalProducts as $product) {
            $daysOfStock = $this->analyticsService->calculateDaysOfStock($product->id);
            $recommendedQty = $this->analyticsService->calculateRecommendedOrderQty($product->id);
            
            $message = $this->buildCriticalStockMessage($product, $daysOfStock, $recommendedQty);
            
            // Stok yöneticisine SMS gönder
            $recipients = $this->getStockManagerPhones($companyId);
            
            foreach ($recipients as $phone) {
                $result = $this->sendSms($phone, $message);
                $sentMessages[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'phone' => $phone,
                    'success' => $result,
                ];
            }
        }

        return $sentMessages;
    }

    /**
     * Stok tükendi SMS'i
     */
    public function sendStockoutAlert(Product $product): bool
    {
        $companyId = $product->company_id;
        $message = "⚠️ STOK TÜKENDİ!\n\n" .
                   "Ürün: {$product->name}\n" .
                   "Kod: {$product->code}\n" .
                   "Mevcut Stok: 0\n\n" .
                   "Acil sipariş gerekiyor!";

        $recipients = $this->getStockManagerPhones($companyId);
        
        foreach ($recipients as $phone) {
            $this->sendSms($phone, $message);
        }

        return true;
    }

    /**
     * Özel SMS gönder
     */
    public function sendCustomAlert(string $phone, string $message): bool
    {
        return $this->sendSms($phone, $message);
    }

    /**
     * SMS mesajı oluştur
     */
    protected function buildCriticalStockMessage(Product $product, int $daysOfStock, int $recommendedQty): string
    {
        $urgency = $daysOfStock <= 3 ? '🔴 ACİL' : '⚠️ DİKKAT';
        
        return "{$urgency} STOK UYARISI!\n\n" .
               "Ürün: {$product->name}\n" .
               "Kod: {$product->code}\n" .
               "Mevcut: {$product->stock} {$product->unit?->symbol}\n" .
               "Min: {$product->min_stock_level}\n" .
               "Kalan: {$daysOfStock} gün\n" .
               "Önerilen Sipariş: {$recommendedQty}\n\n" .
               "Erpovy X1M";
    }

    /**
     * SMS gönder (API entegrasyonu)
     */
    protected function sendSms(string $phone, string $message): bool
    {
        // SMS API entegrasyonu yoksa sadece log'a yaz
        if (!$this->apiUrl || !$this->apiKey) {
            Log::info('SMS Notification', [
                'phone' => $phone,
                'message' => $message,
            ]);
            return true;
        }

        try {
            $response = Http::post($this->apiUrl, [
                'api_key' => $this->apiKey,
                'phone' => $this->formatPhone($phone),
                'message' => $message,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SMS gönderme hatası', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Stok yöneticilerinin telefonlarını al
     */
    protected function getStockManagerPhones(int $companyId): array
    {
        // TODO: Kullanıcı rollerine göre telefon numaralarını al
        // Şimdilik config'den al
        $phones = config('inventory.stock_manager_phones', []);
        
        if (empty($phones)) {
            // Fallback: Company admin'in telefonu
            $admin = \App\Models\User::where('company_id', $companyId)
                ->where('role', 'admin')
                ->first();
            
            if ($admin && $admin->phone) {
                $phones[] = $admin->phone;
            }
        }

        return $phones;
    }

    /**
     * Telefon numarasını formatla
     */
    protected function formatPhone(string $phone): string
    {
        // +90 ile başlamıyorsa ekle
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (!str_starts_with($phone, '90')) {
            $phone = '90' . $phone;
        }

        return '+' . $phone;
    }

    /**
     * Toplu SMS raporu
     */
    public function getDailySmsReport(int $companyId): array
    {
        // Son 24 saatte gönderilen SMS'leri raporla
        // TODO: SMS log tablosu oluştur ve buradan çek
        return [
            'total_sent' => 0,
            'critical_alerts' => 0,
            'stockout_alerts' => 0,
        ];
    }
}
