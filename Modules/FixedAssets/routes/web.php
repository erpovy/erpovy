<?php

use Illuminate\Support\Facades\Route;
use Modules\FixedAssets\Http\Controllers\FixedAssetsController;
use Modules\FixedAssets\Http\Controllers\FixedAssetCategoryController;
use Modules\FixedAssets\Http\Controllers\FixedAssetsDashboardController;

Route::middleware(['auth', 'verified', 'module_access:FixedAssets'])->prefix('fixedassets')->name('fixedassets.')->group(function () {
    Route::get('/dashboard', [FixedAssetsDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('categories', FixedAssetCategoryController::class)
        ->except(['create', 'show', 'edit']); 

    Route::resource('/', FixedAssetsController::class)
        ->parameter('', 'fixedasset')
        ->names([
            'index' => 'index',
            'create' => 'create',
            'store' => 'store',
            'show' => 'show',
            'edit' => 'edit',
            'update' => 'update',
            'destroy' => 'destroy',
        ]);

    Route::post('/{id}/assign', [FixedAssetsController::class, 'assign'])->name('assign');
    Route::post('/{id}/return', [FixedAssetsController::class, 'returnAsset'])->name('return');
    Route::post('/{id}/maintenance', [FixedAssetsController::class, 'storeMaintenance'])->name('maintenance.store');
});
