<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BidController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\UserIdentificationController;

/*
|--------------------------------------------------------------------------
| Unauthenticated API Routes
|--------------------------------------------------------------------------
*/
// Miscellaneous Routes
Route::prefix('misc')->group(function () {
    Route::get('/countries', [CountryController::class, 'index']);
    Route::get('/states/country/{id}', [StateController::class, 'index']);
    Route::get('/cities/state/{id}', [CityController::class, 'index']);

    Route::get('/currencies', [CountryController::class, 'currencies']);

    Route::get('/id-types', [UserIdentificationController::class, 'types']);

    Route::get('/genders', [UserIdentificationController::class, 'genders']);

    Route::get('/banks', [BankController::class, 'index']);
    Route::get('/banks/country/{id}', [BankController::class, 'countryBanks']);
});

// User Account Routes
Route::prefix('account')->group(function () {
    Route::post('/signin', [AccountController::class, 'signin']);
    Route::post('/signup', [AccountController::class, 'signup']);
    Route::post('/confirm-signup', [AccountController::class, 'confirmSignup']);
    Route::post('/forgot-password', [AccountController::class, 'forgotPassword']);
    Route::post('/reset-password', [AccountController::class, 'resetPassword']);
    Route::post('/resend-confirmation', [AccountController::class, 'resendConfirmationEmail']);
});


/*
|--------------------------------------------------------------------------
| Authenticated API Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function() {

    // User Account Routes
    Route::get('/account/profile', [AccountController::class, 'profile']);
    Route::put('/account/update', [AccountController::class, 'update']);
    Route::put('/account/change-password', [AccountController::class, 'changePassword']);
    Route::post('/account/identification', [AccountController::class, 'identification']);

    // Users Routes
    Route::get('/users/{id}', [UserController::class, 'show']);

    // Beneficiaries Routes
    Route::get('/beneficiaries/all/{page_size?}', [BeneficiaryController::class, 'index']);
    Route::get('/beneficiaries/{id}', [BeneficiaryController::class, 'show']);
    Route::post('/beneficiaries', [BeneficiaryController::class, 'store']);
    Route::put('/beneficiaries/{id}', [BeneficiaryController::class, 'update']);
    Route::delete('/beneficiaries/{id}', [BeneficiaryController::class, 'destroy']);

    // Bids Routes
    Route::get('/bids/all/{page_size?}', [BidController::class, 'index']);
    Route::get('/bids/my-bids/{page_size?}', [BidController::class, 'mine']);
    Route::get('/bids/interested-bids/{page_size}', [BidController::class, 'interested']);
    Route::get('/bids/{id}', [BidController::class, 'show']);
    Route::post('/bids', [BidController::class, 'store']);
    Route::put('/bids/{id}', [BidController::class, 'update']);
    Route::delete('/bids/{id}', [BidController::class, 'destroy']);

    // Offers Routes
    Route::get('/offers/bids-offers/{page_size}', [OfferController::class, 'bidsOffers']);
    Route::get('/offers/bid-offers/{id}/{page_size}', [OfferController::class, 'bidOffers']);
    Route::get('/offers/tendered-offers/{page_size}', [OfferController::class, 'tenderedOffers']);
    Route::get('/offers/{id}', [OfferController::class, 'show']);
    Route::post('/offers', [OfferController::class, 'store']);
    Route::put('/offers/{id}', [OfferController::class, 'update']);
    Route::delete('/offers/{id}', [OfferController::class, 'destroy']);

});


