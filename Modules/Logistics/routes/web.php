<?php

use Illuminate\Support\Facades\Route;
use Modules\Logistics\Http\Controllers\LogisticsController;
use Modules\Logistics\Http\Controllers\DashboardController;


Route::middleware(['auth', 'verified'])->prefix('logistics')->name('logistics.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('settings', LogisticsController::class)->names('settings');
});

