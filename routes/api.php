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
    CouponController,
    TaxController,
    DeliveryController,
    CheckoutController
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
Route::get('/products-with-discount', [ProductController::class, 'productsWithDiscount']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/subcategories/{category_id}', [CategoryController::class, 'getSubCategories']);

Route::apiResource('countries', CountryController::class);
Route::apiResource('countries.states', StateController::class)->shallow();
Route::apiResource('countries.states.cities', CityController::class)->shallow();

//Route::post('checkout', [ThawaniPayController::class, 'checkout'])->name('checkout');
//Route::get('success', [ThawaniPayController::class, 'success'])->name('success');
//Route::get('fail', [ThawaniPayController::class, 'fail'])->name('fail');

Route::post('/calculate-tax', [TaxController::class, 'calculate']);
Route::post('/calculate-delivery-charge', [DeliveryController::class, 'calculateDeliveryCharge']);
Route::get('success', [CheckoutController::class, 'success'])->name('api.thawani.success');
Route::get('fail', [CheckoutController::class, 'fail'])->name('api.thawani.fail');

Route::group(['middleware' => ['auth:sanctum', 'setLanguage']], function () {
    Route::post('/coupon-apply', [CouponController::class, 'couponApply']);
    Route::post('/checkout', [CheckoutController::class, 'checkoutOrder']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
