<?php

use App\Modules\Moneybird\Controllers\MoneybirdController;
use Illuminate\Support\Facades\Route;

Route::get('/moneybird/auth', [MoneybirdController::class, 'redirectToMoneybird']);
Route::get('/moneybird/callback', [MoneybirdController::class, 'handleCallback']);
Route::get('/moneybird/{venue}/administrations', [\App\Modules\Moneybird\Services\MoneybirdService::class, 'getAdministrations']);
Route::get('/moneybird/{venue}/invoices', [\App\Modules\Moneybird\Services\MoneybirdService::class, 'getInvoices']);
Route::get('/moneybird/{venue}/products', [\App\Modules\Moneybird\Services\MoneybirdService::class, 'getProducts']);

Route::middleware('auth:sanctum')->prefix('venues/{venue}/moneybird')->group(function () {
    Route::get('/dashboard', [MoneybirdController::class, 'index']);
});