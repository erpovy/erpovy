<?php

use Illuminate\Support\Facades\Route;
use Modules\HumanResources\Http\Controllers\EmployeeController;
use Modules\HumanResources\Http\Controllers\LeaveController;
use Modules\HumanResources\Http\Controllers\PermissionController;
use Modules\HumanResources\Http\Controllers\RoleController;
use Modules\HumanResources\Http\Controllers\UserController;
use Modules\HumanResources\Http\Controllers\VehicleController;

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

Route::middleware(['auth', 'verified'])->prefix('hr')->name('hr.')->group(function () {
    // Dashboard
    Route::get('/', [\Modules\HumanResources\Http\Controllers\HRController::class, 'index'])->name('index');
    
    Route::resource('employees', EmployeeController::class);
    Route::resource('leaves', LeaveController::class)->only(['index', 'store']);
    
    // Fleet Routes
    Route::resource('fleet', VehicleController::class)->parameters(['fleet' => 'vehicle'])->names([
        'index' => 'fleet.index',
        'create' => 'fleet.create',
        'store' => 'fleet.store',
        'edit' => 'fleet.edit',
        'update' => 'fleet.update',
        'destroy' => 'fleet.destroy',
    ]);
    Route::post('fleet/{vehicle}/expense', [VehicleController::class, 'storeExpense'])->name('fleet.expenses.store');

    // User Management Routes
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::post('permissions/seed', [PermissionController::class, 'seed'])->name('permissions.seed');
    Route::resource('departments', \Modules\HumanResources\Http\Controllers\DepartmentController::class);
});
