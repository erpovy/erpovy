<?php

use Illuminate\Support\Facades\Route;
use Modules\Ecommerce\Http\Controllers\EcommerceController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('ecommerces', EcommerceController::class)->names('ecommerce');
});

Route::post('webhook/woocommerce/{platform}', [\Modules\Ecommerce\Http\Controllers\WebhookController::class, 'handleWooCommerce'])
    ->name('ecommerce.webhook.woocommerce');
