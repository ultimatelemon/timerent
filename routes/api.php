<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Management\PaymentProviderController;
use App\Http\Controllers\Management\PlanController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\Stripe\StripeConnectController;
use App\Http\Controllers\Stripe\StripeWebhookController;
use App\Http\Controllers\Support\TicketController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserVenueController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\WeekController;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::get('/hash', function (Request $request) {return response()->json(\Illuminate\Support\Facades\Hash::make('joejoe123'));});
Route::post('/sanctum/token', [\App\Http\Controllers\AuthenticationController::class, 'createToken']);
Route::post('/sanctum/register', [\App\Http\Controllers\AuthenticationController::class, 'createUser']);
Route::post('/sanctum/email/verify', [\App\Http\Controllers\AuthenticationController::class, 'verifyEmail']);
Route::post('/sanctum/email/verify/resend', [\App\Http\Controllers\AuthenticationController::class, 'resendVerifyEmail']);

Route::post('/sanctum/password/reset/request', [AuthenticationController::class, 'createPasswordResetToken']);
Route::post('/sanctum/password/reset', [AuthenticationController::class, 'resetPassword']);

Route::get('/reservationispaid/{reservation}', [ReservationController::class, 'isPaid']);
Route::post('/venuepayments/mollie/webhook', [\App\Http\Controllers\Mollie\MollieWebhookController::class, 'updatePayment']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/sanctum/logout', [\App\Http\Controllers\AuthenticationController::class, 'revokeToken']);

    Route::get('/users/current', [\App\Http\Controllers\User\UserController::class, 'current']);
    Route::resource('/users', \App\Http\Controllers\User\UserController::class);
    Route::resource('users.user-venues', UserVenueController::class)->only(['index']);
    Route::post('/venues/{venue}/weeks/update', [WeekController::class, 'updateOrCreate']);

    Route::get('/venues/{venue}/statistics', [StatisticsController::class, 'index']);

    Route::get('/plans/available',                          [PlanController::class, 'available']);
    Route::resource('plans',                    PlanController::class)->except('create', 'edit');

    Route::middleware('venueSubscription')->group(function () {


        // Settings
        Route::get('/venues/{venue}/settings/general', [SettingController::class, 'getGeneralSettings']);
        Route::put('/venues/{venue}/settings/general', [SettingController::class, 'updateGeneralSettings']);
        Route::get('/venues/{venue}/settings/finance', [SettingController::class, 'getFinanceSettings']);
        Route::put('/venues/{venue}/settings/finance', [SettingController::class, 'updateFinanceSettings']);
        Route::get('/venues/{venue}/settings/reservations', [SettingController::class, 'getReservationSettings']);
        Route::put('/venues/{venue}/settings/reservations', [SettingController::class, 'updateReservationSettings']);

        // Manage
        Route::get('/users/{user}/venue/{venue}', [UserVenueController::class, 'show']);
        Route::put('/users/{user}/venue/{venue}', [UserVenueController::class, 'update']);
        Route::post('/users/{user}/venue/{venue}/portal', [UserVenueController::class, 'openCustomerPortal']);
        Route::post('/venues/{venue}/paymentlink', [VenueController::class, 'createNewPaymentLink']);

        // Payment
        Route::get('/paymentproviders/available', [PaymentProviderController::class, 'available']);
        Route::post('/venue/{venue}/payments/setup', [VenueController::class, 'setupTimerentPayments']);
        Route::post('/venue/{venue}/payments/checkandupdate', [StripeConnectController::class, 'checkOnboardedAndUpdateVenue']);

        // Reservation
        Route::post('/venues/{venue}/reservations/{reservation}/resend/confirmation', [ReservationController::class, 'resendConfirmationMail']);

        //  Member
        Route::get('/venues/{venue}/members/{member}/reservations', [MemberController::class, 'reservations']);

        // Invoice
        Route::post('/venues/{venue}/invoices/{invoice}/download', [InvoiceController::class, 'download']);

        // Units
        Route::get('/venues/{venue}/units/list', [UnitController::class, 'list']);

        // Templates
        Route::get('/venues/{venue}/templates/list', [TemplateController::class, 'list']);

        Route::resource('venues',                   VenueController::class)->except(['create', 'edit']);
        Route::resource('venues.units',             UnitController::class)->except(['create', 'edit']);
        Route::resource('venues.templates',         TemplateController::class)->except(['create', 'edit']);
        Route::resource('venues.weeks',             WeekController::class)->except(['create', 'edit']);
        Route::resource('venues.products',          ProductController::class)->except(['create', 'edit']);
        Route::resource('venues.reservations',      ReservationController::class)->except(['create', 'edit']);
        Route::resource('venues.reports',           ReportController::class)->except(['create', 'edit']);
        Route::resource('venues.users',             UserController::class)->except(['create', 'edit']);
        Route::resource('venues.members',           MemberController::class)->except(['create', 'edit']);
        Route::resource('venues.invoices',          InvoiceController::class)->except(['create', 'edit']);
        Route::resource('venues.roles',             RoleController::class)->except(['create', 'edit']);
        Route::resource('venues.groups',             GroupController::class)->except(['create', 'edit']);
        Route::resource('venues.tickets',             TicketController::class)->except(['create', 'edit']);
    });

});
