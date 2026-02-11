<?php

use Illuminate\Support\Facades\Route;
use Modules\FixedAssets\Http\Controllers\FixedAssetsController;
use Modules\FixedAssets\Http\Controllers\FixedAssetCategoryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('fixedassets/categories', FixedAssetCategoryController::class)
        ->names('fixedassets.categories')
        ->except(['create', 'show', 'edit']); 

    Route::resource('fixedassets', FixedAssetsController::class)->names('fixedassets');
    Route::post('fixedassets/{id}/assign', [FixedAssetsController::class, 'assign'])->name('fixedassets.assign');
    Route::post('fixedassets/{id}/return', [FixedAssetsController::class, 'returnAsset'])->name('fixedassets.return');
    Route::post('fixedassets/{id}/maintenance', [FixedAssetsController::class, 'storeMaintenance'])->name('fixedassets.maintenance.store');
});
