<?php

use Illuminate\Support\Facades\Route;
use Modules\ServiceManagement\Http\Controllers\ServiceManagementController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('servicemanagements', ServiceManagementController::class)->names('servicemanagement');
});
