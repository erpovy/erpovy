<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchasing\Http\Controllers\PurchaseOrderController;
use Modules\Purchasing\Http\Controllers\DashboardController;
use Modules\Purchasing\Http\Controllers\SupplierController;

Route::middleware(['auth', 'verified'])->prefix('purchasing')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('purchasing.dashboard');
    
    // Siparişler
    Route::get('/orders', [PurchaseOrderController::class, 'index'])->name('purchasing.orders.index');
    Route::get('/orders/create', [PurchaseOrderController::class, 'create'])->name('purchasing.orders.create');
    Route::post('/orders', [PurchaseOrderController::class, 'store'])->name('purchasing.orders.store');
    Route::get('/orders/{order}', [PurchaseOrderController::class, 'show'])->name('purchasing.orders.show');
    Route::get('/orders/{order}/edit', [PurchaseOrderController::class, 'edit'])->name('purchasing.orders.edit');
    Route::put('/orders/{order}', [PurchaseOrderController::class, 'update'])->name('purchasing.orders.update');
    Route::post('/orders/{order}/receive', [PurchaseOrderController::class, 'receive'])->name('purchasing.orders.receive');
    Route::post('/orders/{order}/convert-to-invoice', [PurchaseOrderController::class, 'convertToInvoice'])->name('purchasing.orders.convert-to-invoice');

    // Tedarikçiler
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('purchasing.suppliers.index');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('purchasing.suppliers.show');
});
