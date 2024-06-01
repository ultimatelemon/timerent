<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\User\UserVenueController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\WeekController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/hash', function (Request $request) {return response()->json(\Illuminate\Support\Facades\Hash::make('joejoe123'));});
Route::post('/sanctum/token', [\App\Http\Controllers\AuthenticationController::class, 'createToken']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/current', [\App\Http\Controllers\User\UserController::class, 'current']);
    Route::resource('/users', \App\Http\Controllers\User\UserController::class);
    Route::resource('users.user-venues', UserVenueController::class)->only(['index']);
    Route::post('/venues/{venue}/weeks/update', [WeekController::class, 'updateOrCreate']);

    // Todo: Permission routes
    Route::resource('venues',               VenueController::class)->except(['create', 'edit']);
    Route::resource('venues.units',         UnitController::class)->except(['create', 'edit']);
    Route::resource('venues.templates',     TemplateController::class)->except(['create', 'edit']);
    Route::resource('venues.weeks',         WeekController::class)->except(['create', 'edit']);
    Route::resource('venues.products',      ProductController::class)->except(['create', 'edit']);
});
