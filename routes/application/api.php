<?php

use App\Http\Controllers\Application\ApplicationTenantController;
use Illuminate\Support\Facades\Route;

Route::get('/venue/{subdomain}', [ApplicationTenantController::class, 'getVenueBySubdomain']);
Route::get('/venue/{venue}/units', [ApplicationTenantController::class, 'getUnitAvailabilityByday']);
