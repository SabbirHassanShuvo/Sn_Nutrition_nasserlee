<?php

use App\Http\Controllers\Api\Frontend\CartController;
use App\Http\Controllers\Api\Frontend\CheckoutController;
use App\Http\Controllers\Api\Frontend\CouponController;
use App\Http\Controllers\Api\Frontend\HomeController;
use App\Http\Controllers\Api\Frontend\WishlistController;
use App\Http\Controllers\Api\Frontend\ComparisonController;
use App\Http\Controllers\Api\Frontend\AffiliateController;
use App\Http\Controllers\Api\PartnerOnboardingController;
use App\Http\Controllers\Api\CategoryController;
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

Route::prefix('home')->middleware('affiliate.track')->group(function () {
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

    // Coupon
    Route::post('/coupon/apply', [CouponController::class, 'applyCoupon']);

    // Checkout
    Route::get('/checkout/summary', [CheckoutController::class, 'getCheckoutDetails']);
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder']);
    Route::post('/checkout/bank-transfer', [CheckoutController::class, 'submitBankTransfer']);

    // Comparison
    Route::get('/compare', [ComparisonController::class, 'index']);
    Route::post('/compare/add', [ComparisonController::class, 'store']);
    Route::post('/compare/remove/{id}', [ComparisonController::class, 'destroy']);
    Route::post('/compare/clear', [ComparisonController::class, 'clear']);

    // Affiliate System
    Route::prefix('affiliate')->group(function () {
        Route::post('/generate-link', [AffiliateController::class, 'generateLink']);
        Route::get('/stats', [AffiliateController::class, 'getDashboardStats']);
        Route::get('/links', [AffiliateController::class, 'getLinks']);
        Route::get('/orders', [AffiliateController::class, 'getOrders']);
        Route::get('/payout-history', [AffiliateController::class, 'getPayoutHistory']);
        Route::get('/tiers', [AffiliateController::class, 'getTiersInfo']);
        Route::get('/earnings-chart', [AffiliateController::class, 'getEarningsChart']);
    });
});

// Public Data Routes
Route::get('/categories', [CategoryController::class, 'index']);

// Partner Onboarding Flow
Route::group([
    'prefix' => 'partner/onboarding'
], function ($router) {
    Route::get('/options/specialties', [PartnerOnboardingController::class, 'getSpecialties']);
    Route::get('/options/certifications', [PartnerOnboardingController::class, 'getCertifications']);
    Route::post('/step-1', [PartnerOnboardingController::class, 'step1']);
    Route::post('/step-2', [PartnerOnboardingController::class, 'step2']);
    Route::post('/step-3', [PartnerOnboardingController::class, 'step3']);
    Route::post('/step-4', [PartnerOnboardingController::class, 'step4']);
});
