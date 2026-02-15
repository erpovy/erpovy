<?php

use Illuminate\Support\Facades\Route;
use Modules\Logistics\Http\Controllers\LogisticsController;
use Modules\Logistics\Http\Controllers\DashboardController;
use Modules\Logistics\Http\Controllers\VehicleController;
use Modules\Logistics\Http\Controllers\ShipmentController;
use Modules\Logistics\Http\Controllers\RouteController;


Route::middleware(['auth', 'verified'])->prefix('logistics')->name('logistics.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('vehicles', VehicleController::class)->names('vehicles');
    Route::resource('shipments', ShipmentController::class)->names('shipments');
    Route::resource('routes', RouteController::class)->names('routes');
    Route::resource('settings', LogisticsController::class)->names('settings');
});

// Public Tracking Route
Route::get('logistics/track/{number?}', [ShipmentController::class, 'track'])->name('logistics.shipments.track');

