<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| This application has admin-only authentication.
| Students do not create accounts and do not need authentication.
|
*/

Route::middleware('guest')->group(function () {

    // Admin Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1');

});


Route::middleware('auth')->group(function () {

    // Admin Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

});