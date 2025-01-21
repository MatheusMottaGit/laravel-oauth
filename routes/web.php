<?php

use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect', [GoogleAuthController::class, 'redirect']);

Route::get('/auth/callback', [GoogleAuthController::class, 'callback']);
