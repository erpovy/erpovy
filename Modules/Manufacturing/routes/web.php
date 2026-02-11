<?php

use Illuminate\Support\Facades\Route;
use Modules\Manufacturing\Http\Controllers\MRPController;
use Modules\Manufacturing\Http\Controllers\MESController;
use Modules\Manufacturing\Http\Controllers\PLMController;
use Modules\Manufacturing\Http\Controllers\QualityController;
use Modules\Manufacturing\Http\Controllers\ShopfloorController;
use Modules\Manufacturing\Http\Controllers\MaintenanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified', 'module_access:Manufacturing', 'readonly'])->prefix('manufacturing')->name('manufacturing.')->group(function () {
    Route::get('/', [\Modules\Manufacturing\Http\Controllers\ManufacturingController::class, 'index'])->name('index');
    Route::get('/create', [\Modules\Manufacturing\Http\Controllers\ManufacturingController::class, 'create'])->name('create');
    Route::post('/store', [\Modules\Manufacturing\Http\Controllers\ManufacturingController::class, 'store'])->name('store');
    Route::get('/mrp', [MRPController::class, 'index'])->name('mrp.index');
    Route::get('/mes', [MESController::class, 'index'])->name('mes.index');
    Route::post('/mes', [MESController::class, 'store'])->name('mes.store');
    Route::get('/plm', [PLMController::class, 'index'])->name('plm.index');
    Route::post('/plm', [PLMController::class, 'store'])->name('plm.store');
    Route::get('/quality', [QualityController::class, 'index'])->name('quality.index');
    Route::post('/quality', [QualityController::class, 'store'])->name('quality.store');
    Route::get('/shopfloor', [ShopfloorController::class, 'index'])->name('shopfloor.index');
    Route::post('/shopfloor', [ShopfloorController::class, 'store'])->name('shopfloor.store');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::patch('/maintenance/{id}', [MaintenanceController::class, 'update'])->name('maintenance.update');
});
