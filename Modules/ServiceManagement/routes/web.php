<?php

use Illuminate\Support\Facades\Route;
use Modules\ServiceManagement\Http\Controllers\ServiceManagementController;
use Modules\ServiceManagement\Http\Controllers\VehicleController;
use Modules\ServiceManagement\Http\Controllers\ServiceRecordController;
use Modules\ServiceManagement\Http\Controllers\JobCardController;
use Modules\ServiceManagement\Http\Controllers\ServicePOSController;

Route::middleware(['auth', 'verified', 'module_access:ServiceManagement'])->group(function () {
    Route::resource('service-management/vehicles', VehicleController::class)->names('servicemanagement.vehicles');
    Route::get('service-management/maintenance-schedule', [ServiceRecordController::class, 'maintenanceSchedule'])->name('servicemanagement.maintenance-schedule');
    Route::resource('service-management/service-records', ServiceRecordController::class)->names('servicemanagement.service-records');
    
    // Job Cards
    Route::resource('service-management/job-cards', JobCardController::class)->names('servicemanagement.job-cards');
    Route::post('service-management/job-cards/{job_card}/add-item', [JobCardController::class, 'addItem'])->name('servicemanagement.job-cards.add-item');
    Route::delete('service-management/job-cards/{job_card}/items/{item}', [JobCardController::class, 'removeItem'])->name('servicemanagement.job-cards.remove-item');

    Route::resource('service-management', ServiceManagementController::class)->names('servicemanagement');

    // Service POS Routes
    Route::get('service-management-pos', [ServicePOSController::class, 'index'])->name('servicemanagement.pos.index');
    Route::get('service-management-pos/products', [ServicePOSController::class, 'products'])->name('servicemanagement.pos.products');
    Route::post('service-management-pos/checkout', [ServicePOSController::class, 'checkout'])->name('servicemanagement.pos.checkout');
    Route::get('service-management/api/vehicle-status/{plate}', [VehicleController::class, 'statusByPlate'])->name('servicemanagement.vehicles.status-by-plate');
});
