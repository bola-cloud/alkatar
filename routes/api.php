<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  \App\Http\Controllers\Api\{
    ProductController,
    CategoryController,
    Auth\AuthController,
    CountryController,
    StateController,
    CityController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('otp-signin', [AuthController::class, 'otpSignInPost']);
Route::post('otp-verify', [AuthController::class, 'otpVerifyPost']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::apiResource('countries', CountryController::class);
Route::apiResource('countries.states', StateController::class)->shallow();
Route::apiResource('countries.states.cities', CityController::class)->shallow();



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
