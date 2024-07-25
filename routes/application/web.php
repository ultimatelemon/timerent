<?php

use App\Http\Controllers\Application\ApplicationCallbackController;
use Illuminate\Support\Facades\Route;

Route::get('/', function() { return view('application.blank');});
Route::get('/login', function() { return view('application.blank');});
Route::get('/callback/success', [ApplicationCallbackController::class, 'success'])->name('application.callback.success');
Route::get('/email/verify', function () { return view('layouts.authentication'); })->name('verification.verify');