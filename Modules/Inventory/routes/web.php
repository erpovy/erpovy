<?php

use Illuminate\Support\Facades\Route;
use Modules\Inventory\Http\Controllers\ProductController;
use Modules\Inventory\Http\Controllers\CategoryController;
use Modules\Inventory\Http\Controllers\BrandController;
use Modules\Inventory\Http\Controllers\UnitController;
use Modules\Inventory\Http\Controllers\WarehouseController;
use Modules\Inventory\Http\Controllers\AnalyticsController;
use Modules\Inventory\Http\Controllers\ProductTypeController;

Route::middleware(['auth', 'verified', 'module_access:Inventory', 'readonly'])->prefix('inventory')->name('inventory.')->group(function () {
    // Ürün Türleri
    Route::resource('settings/types', ProductTypeController::class)->names([
        'index' => 'settings.types.index',
        'store' => 'settings.types.store',
        'update' => 'settings.types.update',
        'destroy' => 'settings.types.destroy',
    ]);
    // Analitik ve Raporlama
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/product/{product}', [AnalyticsController::class, 'productDetail'])->name('analytics.product');
    Route::get('analytics/export/excel', [AnalyticsController::class, 'exportExcel'])->name('analytics.export.excel');
    Route::get('analytics/export/pdf', [AnalyticsController::class, 'exportPdf'])->name('analytics.export.pdf');
    Route::get('analytics/widget', [AnalyticsController::class, 'dashboardWidget'])->name('analytics.widget');
    
    // Kategori Yönetimi
    Route::resource('categories', CategoryController::class);
    Route::post('categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.update-order');
    
    // Marka Yönetimi
    Route::resource('brands', BrandController::class);
    
    // Birim Yönetimi
    Route::resource('units', UnitController::class);
    Route::get('units/{unit}/conversions', [UnitController::class, 'conversions'])->name('units.conversions');
    Route::post('units/{unit}/conversions', [UnitController::class, 'storeConversion'])->name('units.conversions.store');
    Route::delete('conversions/{conversion}', [UnitController::class, 'destroyConversion'])->name('conversions.destroy');
    
    // Depo Yönetimi
    Route::resource('warehouses', WarehouseController::class);
    
    // Ürün Yönetimi
    Route::get('products/import/sample', [ProductController::class, 'downloadSampleCsv'])->name('products.import.sample');
    Route::get('products/import', [ProductController::class, 'importForm'])->name('products.import.form');
    Route::post('products/import', [ProductController::class, 'importCsv'])->name('products.import');
    Route::get('products/export', [ProductController::class, 'exportCsv'])->name('products.export');
    Route::resource('products', ProductController::class);
    
    // Stok Hareketleri
    Route::post('stock-movements', [Modules\Inventory\Http\Controllers\StockMovementController::class, 'store'])->name('stock.adjust');
});
