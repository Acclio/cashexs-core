<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\UserIdentificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// User Account Routes
Route::prefix('account')->group(function () {
    Route::post('/signin', [AccountController::class, 'signin']);
    Route::post('/signup', [AccountController::class, 'signup']);
    Route::post('/confirm-signup', [AccountController::class, 'confirmSignup']);
    Route::post('/forgot-password', [AccountController::class, 'forgotPassword']);
    Route::post('/reset-password', [AccountController::class, 'resetPassword']);
    Route::post('/resend-confirmation', [AccountController::class, 'resendConfirmationEmail']);

    // Authenticated Routes
    Route::middleware('auth:api')->group(function() {
        Route::get('/profile', [AccountController::class, 'profile']);
        Route::put('/update', [AccountController::class, 'update']);
        Route::put('/change-password', [AccountController::class, 'changePassword']);
        Route::post('/identification', [AccountController::class, 'identification']);
    });
});

// Miscellaneous Routes
Route::prefix('misc')->group(function () {
    Route::get('/countries', [CountryController::class, 'index']);
    Route::get('/states/country/{id}', [StateController::class, 'index']);
    Route::get('/cities/state/{id}', [CityController::class, 'index']);

    Route::get('/id-types', [UserIdentificationController::class, 'types']);

    Route::get('/genders', [UserIdentificationController::class, 'genders']);

    Route::get('/banks', [BankController::class, 'index']);
    Route::get('/banks/country/{id}', [BankController::class, 'countryBanks']);
});

// Authenticated Routes
Route::middleware('auth:api')->group(function() {

    // Users Routes
    Route::prefix('users')->group(function () {
        Route::get('/user/{id}', [UserController::class, 'show']);
    });

    Route::apiResources([

        // Beneficiaries Routes
        'beneficiaries' => BeneficiaryController::class,
    ]);
});


