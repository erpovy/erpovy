<?php

namespace Modules\Ecommerce\Services;

use Illuminate\Support\Facades\Http;
use Modules\Ecommerce\Models\EcommercePlatform;
use Illuminate\Support\Facades\Log;

class WooCommerceService
{
    protected EcommercePlatform $platform;
    protected string $baseUrl;

    public function __construct(EcommercePlatform $platform)
    {
        $this->platform = $platform;
        $this->baseUrl = rtrim($platform->store_url, '/') . '/wp-json/wc/v3/';
    }

    /**
     * WooCommerce API isteği gönder
     */
    protected function request(string $method, string $endpoint, array $data = [])
    {
        try {
            $response = Http::withBasicAuth(
                $this->platform->consumer_key,
                $this->platform->consumer_secret
            )->timeout(30)->{$method}($this->baseUrl . $endpoint, $data);

            if ($response->failed()) {
                Log::error("WooCommerce API Hatası ($endpoint): " . $response->body());
                throw new \Exception("WooCommerce API Error: " . $response->status());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error("WooCommerce Servis Hatası: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Bağlantı testi
     */
    public function testConnection(): bool
    {
        try {
            $this->request('get', 'system_status');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Ürünleri çek
     */
    public function fetchProducts(array $params = [])
    {
        return $this->request('get', 'products', $params);
    }

    /**
     * Siparişleri çek
     */
    public function fetchOrders(array $params = [])
    {
        return $this->request('get', 'orders', $params);
    }

    /**
     * Stok güncelle
     */
    public function updateStock(string $productId, int $quantity)
    {
        return $this->request('put', "products/$productId", [
            'stock_quantity' => $quantity,
            'manage_stock' => true
        ]);
    }

    /**
     * Siparişleri senkronize et (Toplu)
     */
    public function syncOrders(array $params = ['status' => 'processing']): int
    {
        $wcOrders = $this->fetchOrders($params);
        $count = 0;

        foreach ($wcOrders as $wcOrder) {
            if ($this->processOrder($wcOrder)) {
                $count++;
            }
        }

        $this->platform->update(['last_sync_at' => now()]);
        return $count;
    }

    /**
     * Tekil siparişi işle
     */
    public function processOrder(array $wcOrder): bool
    {
        // 1. Zaten senkronize edilmiş mi kontrol et
        $mapping = \Modules\Ecommerce\Models\EcommerceMapping::where('ecommerce_platform_id', $this->platform->id)
            ->where('external_id', $wcOrder['id'])
            ->where('mappable_type', \Modules\Accounting\Models\Invoice::class)
            ->first();

        if ($mapping) {
            return false;
        }

        return \Illuminate\Support\Facades\DB::transaction(function () use ($wcOrder) {
            // 2. Müşteri bul veya oluştur
            $contact = \Modules\CRM\Models\Contact::where('company_id', $this->platform->company_id)
                ->where('email', $wcOrder['billing']['email'])
                ->first();

            if (!$contact) {
                $contact = \Modules\CRM\Models\Contact::create([
                    'company_id' => $this->platform->company_id,
                    'type' => 'customer',
                    'name' => $wcOrder['billing']['first_name'] . ' ' . $wcOrder['billing']['last_name'],
                    'email' => $wcOrder['billing']['email'],
                    'phone' => $wcOrder['billing']['phone'],
                    'address' => $wcOrder['billing']['address_1'] . ' ' . $wcOrder['billing']['city'],
                ]);
            }

            // 3. Fatura oluştur
            $invoice = \Modules\Accounting\Models\Invoice::create([
                'company_id' => $this->platform->company_id,
                'contact_id' => $contact->id,
                'invoice_number' => 'WC-' . $wcOrder['id'],
                'issue_date' => now(),
                'due_date' => now()->addDays(7),
                'total_amount' => $wcOrder['total'],
                'tax_amount' => $wcOrder['total_tax'],
                'status' => 'draft',
                'direction' => 'out',
                'notes' => 'WooCommerce Order #' . $wcOrder['id'],
            ]);

            // 4. Fatura satırlarını oluştur
            foreach ($wcOrder['line_items'] as $item) {
                $productMapping = \Modules\Ecommerce\Models\EcommerceMapping::where('ecommerce_platform_id', $this->platform->id)
                    ->where('external_id', $item['product_id'] ?? $item['id'])
                    ->where('mappable_type', \Modules\Inventory\Models\Product::class)
                    ->first();

                \Modules\Accounting\Models\InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $productMapping ? $productMapping->mappable_id : null,
                    'description' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'vat_rate' => 20,
                    'vat_amount' => $item['total_tax'],
                    'line_total' => $item['total'] + $item['total_tax'],
                ]);
            }

            // 5. Mapping kaydet
            \Modules\Ecommerce\Models\EcommerceMapping::create([
                'company_id' => $this->platform->company_id,
                'ecommerce_platform_id' => $this->platform->id,
                'mappable_id' => $invoice->id,
                'mappable_type' => \Modules\Accounting\Models\Invoice::class,
                'external_id' => $wcOrder['id'],
                'remote_data' => $wcOrder,
            ]);

            return true;
        });
    }
}
