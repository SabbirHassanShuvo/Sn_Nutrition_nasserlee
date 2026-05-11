<?php

use App\Http\Controllers\Api\Frontend\CartController;
use App\Http\Controllers\Api\Frontend\CheckoutController;
use App\Http\Controllers\Api\Frontend\CouponController;
use App\Http\Controllers\Api\Frontend\HomeController;
use App\Http\Controllers\Api\Frontend\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sabbir Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('home')->group(function () {
    Route::get('/products', [HomeController::class, 'getAllProducts']);
    Route::get('/products/filter', [HomeController::class, 'filterProducts']);
    Route::get('/products/{id}', [HomeController::class, 'getProductDetails']);
    Route::get('/filters', [HomeController::class, 'getFilters']);
});

// Protected Routes
Route::middleware('auth:api')->group(function () {
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);
    Route::post('/wishlist/{id}', [WishlistController::class, 'remove']);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'store']);
    Route::post('/cart/update/{id}', [CartController::class, 'update']);
    Route::post('/cart/delete/{id}', [CartController::class, 'destroy']);
    // Route::post('/cart/clear', [CartController::class, 'clear']);

    // Coupon
    Route::post('/coupon/apply', [CouponController::class, 'applyCoupon']);

    // Checkout
    Route::get('/checkout/summary', [CheckoutController::class, 'getCheckoutDetails']);
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder']);
    Route::post('/checkout/bank-transfer', [CheckoutController::class, 'submitBankTransfer']);
});
