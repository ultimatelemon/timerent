<?php

use App\Http\Controllers\Application\ApplicationCallbackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() { return view('application.authentication');});

Route::get('/dashboard', function() { return view('application.dashboard');});
Route::get('/dashboard/{any}', function () { return view('application.dashboard'); })->where('any', '^(?!api).*$');

Route::get('/login', function() { return view('application.authentication');});
Route::get('/password-forgot', function() { return view('application.authentication');});
Route::get('/password-reset', function() { return view('application.authentication');});
Route::get('/callback/success', [ApplicationCallbackController::class, 'success'])->name('application.callback.success');
Route::get('/email/verify', function () { return view('layouts.authentication'); })->name('verification.verify');