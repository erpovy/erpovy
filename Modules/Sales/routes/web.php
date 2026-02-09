<?php

use Illuminate\Support\Facades\Route;
use Modules\Sales\Http\Controllers\SalesController;
use Modules\Sales\Http\Controllers\QuoteController;
use Modules\Sales\Http\Controllers\POSController;
use Modules\Sales\Http\Controllers\SubscriptionController;
use Modules\Sales\Http\Controllers\ReturnController;
use Modules\Sales\Http\Controllers\RentalController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('sales', SalesController::class)->names('sales.sales');
    Route::resource('quotes', QuoteController::class)->names('sales.quotes');
    Route::post('quotes/{quote}/approve', [QuoteController::class, 'approve'])->name('sales.quotes.approve');
    Route::post('quotes/{quote}/send', [QuoteController::class, 'send'])->name('sales.quotes.send');
    Route::resource('subscriptions', SubscriptionController::class)->names('sales.subscriptions');
    Route::post('subscriptions/{subscription}/toggle-status', [SubscriptionController::class, 'toggleStatus'])->name('sales.subscriptions.toggleStatus');
    Route::post('subscriptions/{subscription}/create-invoice', [SubscriptionController::class, 'createInvoice'])->name('sales.subscriptions.createInvoice');
    Route::resource('rentals', RentalController::class)->names('sales.rentals');

    // POS Routes
    Route::get('pos', [POSController::class, 'index'])->name('sales.pos.index');
    Route::get('pos/products', [POSController::class, 'products'])->name('sales.pos.products');
    Route::post('pos/checkout', [POSController::class, 'checkout'])->name('sales.pos.checkout');

    Route::resource('returns', ReturnController::class)->names('sales.returns');
});
