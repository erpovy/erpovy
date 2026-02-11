<?php

use Illuminate\Support\Facades\Route;
use Modules\SuperAdmin\Http\Controllers\SuperAdminController;

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // Module Market (Accessible to all admins/users)
    Route::get('/market', [\Modules\SuperAdmin\Http\Controllers\ModuleMarketController::class, 'index'])->name('superadmin.market.index');
});

Route::middleware(['auth', 'verified', 'superadmin'])->prefix('admin')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('superadmin.index');

    // Company Management
    Route::resource('companies', \Modules\SuperAdmin\Http\Controllers\CompanyManagementController::class)->names('superadmin.companies');
    Route::post('companies/{company}/toggle-module', [\Modules\SuperAdmin\Http\Controllers\CompanyManagementController::class, 'toggleModule'])->name('superadmin.companies.toggle-module');
    Route::put('companies/{company}/update-location', [\Modules\SuperAdmin\Http\Controllers\CompanyManagementController::class, 'updateLocation'])->name('superadmin.companies.update-location');
    Route::post('companies/{company}/inspect', [\Modules\SuperAdmin\Http\Controllers\CompanyManagementController::class, 'inspect'])->name('superadmin.companies.inspect');
    Route::post('stop-inspection', [\Modules\SuperAdmin\Http\Controllers\CompanyManagementController::class, 'stopInspection'])->name('superadmin.stop-inspection');
});
