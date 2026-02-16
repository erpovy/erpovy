<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchasing\Http\Controllers\PurchasingController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('purchasings', PurchasingController::class)->names('purchasing');
});
