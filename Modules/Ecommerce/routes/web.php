<?php

use Illuminate\Support\Facades\Route;
use Modules\Ecommerce\Http\Controllers\EcommerceController;
use Modules\Ecommerce\Http\Controllers\EcommercePlatformController;

Route::middleware(['auth', 'verified', 'module_access:Ecommerce'])->prefix('ecommerce')->name('ecommerce.')->group(function () {
    Route::get('/', [EcommerceController::class, 'index'])->name('index');
    
    // Platforms (Stores)
    Route::resource('platforms', EcommercePlatformController::class);
    Route::post('platforms/{platform}/test-connection', [EcommercePlatformController::class, 'testConnection'])->name('platforms.test-connection');
    
    // Sync Actions
    Route::post('platforms/{platform}/sync-products', [EcommerceController::class, 'syncProducts'])->name('platforms.sync-products');
    Route::post('platforms/{platform}/sync-orders', [EcommerceController::class, 'syncOrders'])->name('platforms.sync-orders');
});
