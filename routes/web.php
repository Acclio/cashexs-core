<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Mail Templates
|--------------------------------------------------------------------------
|
| Here is where all mail templates go. Please comment out these routes in
| production
|
*/

Route::get('/mailable', function () {
    $user = App\Models\User::find(1);
    return new App\Mail\EmailConfirmation($user, "00000");
});
