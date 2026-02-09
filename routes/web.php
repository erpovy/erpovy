<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WeatherController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/weather', [ProfileController::class, 'updateWeatherSettings'])->name('profile.weather.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/appearance', [SettingsController::class, 'updateAppearance'])->name('settings.update-appearance');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Weather API
    Route::get('/api/weather', [App\Http\Controllers\WeatherController::class, 'getWeather'])->name('api.weather');

    // Global Search
    Route::get('/global-search', [App\Http\Controllers\GlobalSearchController::class, 'search'])->name('global.search');

    // Activities
    Route::get('/activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');

    // Company Setup
    Route::prefix('setup')->name('setup.')->group(function () {
        Route::get('/accounting', [App\Http\Controllers\CompanySetupController::class, 'accounting'])->name('accounting');
        Route::put('/accounting', [App\Http\Controllers\CompanySetupController::class, 'updateAccounting'])->name('accounting.update');
        
        // Invoice Setup
        Route::get('/invoice', [App\Http\Controllers\CompanySetupController::class, 'invoice'])->name('invoice');
        Route::put('/invoice', [App\Http\Controllers\CompanySetupController::class, 'updateInvoice'])->name('invoice.update');

        // CRM Setup
        Route::get('/crm', [App\Http\Controllers\CompanySetupController::class, 'crm'])->name('crm');
        Route::put('/crm', [App\Http\Controllers\CompanySetupController::class, 'updateCrm'])->name('crm.update');
    });
});

require __DIR__.'/auth.php';
