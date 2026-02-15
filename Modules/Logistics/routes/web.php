<?php

use Illuminate\Support\Facades\Route;
use Modules\Logistics\Http\Controllers\LogisticsController;
use Modules\Logistics\Http\Controllers\DashboardController;
use Modules\Logistics\Http\Controllers\VehicleController;
use Modules\Logistics\Http\Controllers\ShipmentController;


Route::middleware(['auth', 'verified'])->prefix('logistics')->name('logistics.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('vehicles', VehicleController::class)->names('vehicles');
    Route::resource('shipments', ShipmentController::class)->names('shipments');
    Route::resource('settings', LogisticsController::class)->names('settings');
});

