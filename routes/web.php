<?php

use Illuminate\Support\Facades\Route;

if(env('APP_ENV') === 'local') {
    // Local routes
}
Route::get('/', function (\Illuminate\Support\Facades\Request $request) {
    if(request()->getHost() === env('MAIN_DOMAIN')) {
        return redirect('/select');
    } else {
        return view('application.blank');
    }
})->name('index');

Route::get('/login', function () { return view('layouts.authentication'); })->name('login');
Route::get('/register', function () { return view('layouts.authentication'); })->name('register');
Route::get('/email/verify', function () { return view('layouts.authentication'); })->name('verification.verify');

Route::get('/timerentpayments/success', function() {return view('layouts.authentication');})->name('timerent.payments.success');

Route::get('/callback/success', [\App\Http\Controllers\Stripe\StripeCallbackController::class, 'success'])->name('callback.success');
Route::get('/mollie/callback/success/{reservation}', [\App\Http\Controllers\Mollie\MollieWebhookController::class, 'updatePayment'])->name('mollie.callback.success');
Route::get('/confirmation/{any}', function() { return view('layouts.blank-page'); })->name('confirmation');

Route::get('/password-forgot', function() { return view('layouts.authentication');});
Route::get('/password-reset', function() { return view('layouts.authentication');});
Route::get('/select', function () { return view('layouts.dashboard'); })->name('select');
Route::get('/settings', function () { return view('layouts.dashboard'); })->name('settings');
Route::get('/manage/{any}', function () { return view('layouts.dashboard'); })->name('manage');

Route::middleware('venueSubscription')->group(function () {
    Route::get('/store/{any}', function () { return view('layouts.main-application'); })->where('any', '^(?!api).*$');
});

Route::get('{any}', function () { return view('layouts.main-application'); })->where('any', '^(?!api).*$');