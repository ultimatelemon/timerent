<?php

use App\Http\Controllers\Application\ApplicationReservationController;
use App\Http\Controllers\Application\ApplicationTenantController;
use App\Http\Controllers\Member\MemberController;
use Illuminate\Support\Facades\Route;
Route::get('/help', function() {
   return response()->json('This is a application, enjoy!');
});

Route::get('/venue/{subdomain}', [ApplicationTenantController::class, 'getVenueBySubdomain']);
Route::get('/venue/{venue}/units', [ApplicationTenantController::class, 'getUnitAvailabilityByday']);
Route::get('/venue/{venue}/products', [ApplicationTenantController::class, 'getAvailableProducts']);

Route::post('/reservations/store', [ApplicationReservationController::class, 'store']);

Route::post('/app/sanctum/token', [\App\Http\Controllers\Application\ApplicationAuthenticationController::class, 'createToken']);
Route::post('/app/sanctum/register', [\App\Http\Controllers\Application\ApplicationAuthenticationController::class, 'storeMember']);
Route::post('/app/sanctum/email/verify', [\App\Http\Controllers\Application\ApplicationAuthenticationController::class, 'verifyEmail']);
Route::post('/app/sanctum/email/verify/resend', [\App\Http\Controllers\Application\ApplicationAuthenticationController::class, 'resendVerifyEmail']);
Route::post('/app/sanctum/password/reset/request', [\App\Http\Controllers\Application\ApplicationAuthenticationController::class, 'createPasswordResetToken']);
Route::post('/app/sanctum/password/reset', [\App\Http\Controllers\Application\ApplicationAuthenticationController::class, 'resetPassword']);
//
Route::middleware('member')->group(function () {
    Route::post('/app/sanctum/logout', [\App\Http\Controllers\Application\ApplicationAuthenticationController::class, 'revokeToken']);
    Route::get('/app/members/current', [MemberController::class, 'current']);
    Route::get('/app/members/current/reservations', [ApplicationReservationController::class, 'index']);
    Route::get('/app/members/current/reservations/{reservation}', [ApplicationReservationController::class, 'show']);
    Route::post('/app/members/current/reservations/{reservation}/cancel', [ApplicationReservationController::class, 'cancel']);
});

