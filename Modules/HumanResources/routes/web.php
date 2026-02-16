<?php

use Illuminate\Support\Facades\Route;
use Modules\HumanResources\Http\Controllers\EmployeeController;
use Modules\HumanResources\Http\Controllers\LeaveController;
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

Route::middleware(['auth', 'verified', 'module_access:HumanResources', 'readonly'])->prefix('hr')->name('hr.')->group(function () {
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
    Route::resource('departments', \Modules\HumanResources\Http\Controllers\DepartmentController::class);

    // Payroll Routes
    Route::resource('payrolls', \Modules\HumanResources\Http\Controllers\PayrollController::class);
    Route::post('payrolls/{payroll}/calculate', [\Modules\HumanResources\Http\Controllers\PayrollController::class, 'calculateAll'])->name('payrolls.calculate');
    Route::post('payrolls/{payroll}/post-accounting', [\Modules\HumanResources\Http\Controllers\PayrollController::class, 'postToAccounting'])->name('payrolls.postAccounting');
});
