<?php

namespace Modules\Inventory\Services;

use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProformaInvoice;
use Carbon\Carbon;

class ProformaService
{
    protected $analyticsService;

    public function __construct(StockAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Kritik stoklar için otomatik proforma oluştur
     */
    public function generateProformaForCriticalStock(int $companyId): array
    {
        $criticalProducts = Product::where('company_id', $companyId)
            ->where('stock_track', true)
            ->where('is_active', true)
            ->get()
            ->filter(function($product) {
                $risk = $this->analyticsService->calculateStockoutRisk($product->id);
                return $risk >= 60; // Orta ve üzeri risk
            });
        
        $proformas = [];
        
        foreach ($criticalProducts as $product) {
            $proforma = $this->createProforma($product);
            if ($proforma) {
                $proformas[] = $proforma;
            }
        }
        
        return $proformas;
    }

    /**
     * Tek ürün için proforma oluştur
     */
    public function createProforma(Product $product, ?string $supplierName = null): ?ProformaInvoice
    {
        $recommendedQty = $this->analyticsService->calculateRecommendedOrderQty($product->id);
        
        if ($recommendedQty <= 0) {
            return null;
        }
        
        $items = [
            [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $recommendedQty,
                'unit_price' => $product->purchase_price,
                'total' => $recommendedQty * $product->purchase_price,
            ]
        ];
        
        $subtotal = $recommendedQty * $product->purchase_price;
        $taxAmount = $subtotal * ($product->vat_rate / 100);
        $total = $subtotal + $taxAmount;
        
        $proforma = ProformaInvoice::create([
            'company_id' => $product->company_id,
            'user_id' => auth()->id(),
            'proforma_number' => $this->generateProformaNumber($product->company_id),
            'proforma_date' => now(),
            'valid_until' => now()->addDays(15),
            'supplier_name' => $supplierName ?? 'Tedarikçi Belirtilmedi',
            'items' => $items,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $total,
            'notes' => "Kritik stok seviyesi nedeniyle otomatik oluşturuldu.\n" .
                      "Mevcut Stok: {$product->stock}\n" .
                      "Minimum Stok: {$product->min_stock_level}\n" .
                      "Önerilen Sipariş: {$recommendedQty} adet",
            'status' => 'draft',
        ]);
        
        return $proforma;
    }

    /**
     * Çoklu ürün için tek proforma oluştur
     */
    public function createBulkProforma(array $productIds, ?string $supplierName = null): ProformaInvoice
    {
        $items = [];
        $subtotal = 0;
        $taxAmount = 0;
        
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if (!$product) continue;
            
            $recommendedQty = $this->analyticsService->calculateRecommendedOrderQty($productId);
            if ($recommendedQty <= 0) continue;
            
            $itemTotal = $recommendedQty * $product->purchase_price;
            $itemTax = $itemTotal * ($product->vat_rate / 100);
            
            $items[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity' => $recommendedQty,
                'unit_price' => $product->purchase_price,
                'total' => $itemTotal,
            ];
            
            $subtotal += $itemTotal;
            $taxAmount += $itemTax;
        }
        
        $total = $subtotal + $taxAmount;
        $companyId = Product::find($productIds[0])->company_id;
        
        return ProformaInvoice::create([
            'company_id' => $companyId,
            'user_id' => auth()->id(),
            'proforma_number' => $this->generateProformaNumber($companyId),
            'proforma_date' => now(),
            'valid_until' => now()->addDays(15),
            'supplier_name' => $supplierName ?? 'Tedarikçi Belirtilmedi',
            'items' => $items,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $total,
            'notes' => count($items) . " ürün için toplu proforma fatura",
            'status' => 'draft',
        ]);
    }

    /**
     * Proforma numarası oluştur
     */
    protected function generateProformaNumber(int $companyId): string
    {
        $year = now()->year;
        $month = now()->format('m');
        
        $lastProforma = ProformaInvoice::where('company_id', $companyId)
            ->whereYear('proforma_date', $year)
            ->whereMonth('proforma_date', now()->month)
            ->latest('id')
            ->first();
        
        $sequence = $lastProforma ? (int) substr($lastProforma->proforma_number, -4) + 1 : 1;
        
        return "PRO-{$year}{$month}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Proforma'yı PDF olarak export et
     */
    public function exportToPdf(ProformaInvoice $proforma): string
    {
        // TODO: PDF generation implementation
        // Şimdilik sadece path döndür
        return "proformas/PRO-{$proforma->id}.pdf";
    }

    /**
     * Proforma'yı email ile gönder
     */
    public function sendToSupplier(ProformaInvoice $proforma, string $email): bool
    {
        // TODO: Email sending implementation
        // Şimdilik true döndür
        return true;
    }
}
