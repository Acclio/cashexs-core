<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CountryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Unauthenticated Routes
Route::prefix('account')->group(function () {
    Route::post('/signin', [AccountController::class, 'signin']);
    Route::post('/signup', [AccountController::class, 'signup']);
    Route::post('/confirm-signup', [AccountController::class, 'confirmSignup']);
    Route::post('/forgot-password', [AccountController::class, 'forgotPassword']);
    Route::post('/reset-password', [AccountController::class, 'resetPassword']);
    Route::post('/resend-confirmation', [AccountController::class, 'resendConfirmationEmail']);
});

// Miscellaneous Routes
Route::prefix('misc')->group(function () {
    Route::get('/countries', [CountryController::class, 'index']);
    Route::get('/states/country/{id}', [StateController::class, 'index']);
    Route::get('/cities/state/{id}', [CityController::class, 'index']);
});

// Authenticated Routes
Route::middleware('auth:api')->group(function() {

});


