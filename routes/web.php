<?php

use Illuminate\Support\Facades\Route;

if(env('APP_ENV') === 'local') {
    // Local routes
}

Route::get('/login', function () { return view('layouts.authentication'); })->name('login');
Route::get('/register', function () { return view('auth.authentication'); })->name('register');
Route::get('/forgot-password', function () { return view('auth.authentication'); })->name('forgot');
Route::get('/reset-password/{any}', function () { return view('auth.authentication'); })->name('reset')->where('any', '^(?!api).*$');
Route::get('/select', function () { return view('layouts.blank-page'); })->name('select');
Route::get('{any}', function () { return view('layouts.main-application'); })->where('any', '^(?!api).*$');