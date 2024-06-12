<?php

use App\Http\Controllers\Application\ApplicationReservationController;
use App\Http\Controllers\Application\ApplicationTenantController;
use Illuminate\Support\Facades\Route;

Route::get('/venue/{subdomain}', [ApplicationTenantController::class, 'getVenueBySubdomain']);
Route::get('/venue/{venue}/units', [ApplicationTenantController::class, 'getUnitAvailabilityByday']);
Route::get('/venue/{venue}/products', [ApplicationTenantController::class, 'getAvailableProducts']);

Route::post('/reservations/store', [ApplicationReservationController::class, 'store']);
