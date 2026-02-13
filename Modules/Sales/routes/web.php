<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\SalesController;
use Modules\Sales\Http\Controllers\DashboardController;
use Modules\Sales\Http\Controllers\QuoteController;

use Modules\Sales\Http\Controllers\POSController;
use Modules\Sales\Http\Controllers\SubscriptionController;
use Modules\Sales\Http\Controllers\ReturnController;
use Modules\Sales\Http\Controllers\RentalController;

Route::middleware(['auth', 'verified', 'module_access:Sales', 'readonly'])->prefix('sales')->name('sales.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('sales', SalesController::class);

    Route::resource('quotes', QuoteController::class);
    Route::post('quotes/{quote}/approve', [QuoteController::class, 'approve'])->name('quotes.approve');
    Route::post('quotes/{quote}/send', [QuoteController::class, 'send'])->name('quotes.send');
    Route::resource('subscriptions', SubscriptionController::class);
    Route::post('subscriptions/{subscription}/toggle-status', [SubscriptionController::class, 'toggleStatus'])->name('subscriptions.toggleStatus');
    Route::post('subscriptions/{subscription}/create-invoice', [SubscriptionController::class, 'createInvoice'])->name('subscriptions.createInvoice');
    Route::resource('rentals', RentalController::class);

    // POS Routes
    Route::get('pos', [POSController::class, 'index'])->name('pos.index');
    Route::get('pos/products', [POSController::class, 'products'])->name('pos.products');
    Route::post('pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');

    Route::resource('returns', ReturnController::class);
});
