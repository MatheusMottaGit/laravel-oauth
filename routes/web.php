<?php

use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('test');
});

Route::get('/auth/redirect', [GoogleAuthController::class, 'redirectToGoogleAuthorization'])->name('auth.request');

Route::get('/api/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
