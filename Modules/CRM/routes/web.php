<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\ContactController;

Route::middleware(['auth', 'verified', 'module_access:CRM', 'readonly'])->prefix('crm')->name('crm.')->group(function () {
    Route::resource('contacts', ContactController::class);
    Route::resource('leads', \Modules\CRM\Http\Controllers\LeadController::class);
    Route::resource('deals', \Modules\CRM\Http\Controllers\DealController::class);
    Route::post('deals/{deal}/stage', [\Modules\CRM\Http\Controllers\DealController::class, 'updateStage'])->name('deals.stage');
    Route::resource('contracts', \Modules\CRM\Http\Controllers\ContractController::class);
});
