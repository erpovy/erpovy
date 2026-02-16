<?php

namespace Modules\Ecommerce\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * Handle WooCommerce Webhook
     */
    public function handleWooCommerce(Request $request, EcommercePlatform $platform)
    {
        // 1. Verify Signature (Her platformun kendi secret'ı olabilir, şimdilik opsiyonel)
        // $signature = $request->header('X-WC-Webhook-Signature');
        
        $topic = $request->header('X-WC-Webhook-Topic');
        $payload = $request->all();

        \Illuminate\Support\Facades\Log::info("WooCommerce Webhook Received: $topic for Platform #{$platform->id}");

        if ($topic === 'order.created' || $topic === 'order.updated') {
            try {
                // Sadece 'processing' durumundaki siparişleri işle (veya WooCommerce ayarına göre)
                if (isset($payload['status']) && $payload['status'] === 'processing') {
                    $service = new \Modules\Ecommerce\Services\WooCommerceService($platform);
                    $processed = $service->processOrder($payload);
                    
                    if ($processed) {
                        return response()->json(['message' => 'Order processed successfully'], 201);
                    }
                }
                return response()->json(['message' => 'Order skipped or already processed'], 200);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Webhook Process Error: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return response()->json(['message' => 'Topic ignored'], 200);
    }
}
