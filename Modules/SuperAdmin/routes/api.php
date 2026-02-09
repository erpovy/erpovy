<?php

use Illuminate\Support\Facades\Route;
use Modules\SuperAdmin\Http\Controllers\SuperAdminController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('superadmins', SuperAdminController::class)->names('superadmin');
});
