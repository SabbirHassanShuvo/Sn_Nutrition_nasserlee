<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\GoogleAuthController;

use App\Http\Controllers\API\Frontend\Cms\HomePageController;
use App\Http\Controllers\API\Frontend\Cms\CmsController;
use App\Http\Controllers\API\Frontend\Cms\FaqApiController;
use App\Http\Controllers\API\Frontend\Cms\WebSettingApiController;
use App\Http\Controllers\API\Frontend\BlogApiController;

use App\Http\Controllers\Api\Frontend\MyInformationController;
use App\Http\Controllers\Api\Frontend\UserAddressController;
use App\Http\Controllers\Api\Frontend\UserOrderController;

use App\Http\Controllers\Api\Frontend\ConsultationController;
use App\Http\Controllers\Api\Frontend\GymApiController;
use App\Http\Controllers\Api\Frontend\PharmacyApiController;
use App\Http\Controllers\Api\Frontend\UserSettingsController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/profile', [AuthController::class, 'profileRetrieval']);
        Route::post('/profile/update', [AuthController::class, 'ProfileUpdate']);
        Route::post('/password/change', [AuthController::class, 'ChangePassword']);
    });
    
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/password/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/password/resend-otp', [AuthController::class, 'resendOtp']);

    // Google Auth
    Route::post('/google', [GoogleAuthController::class, 'login']);
});

Route::group(['middleware' => 'api'], function($router){
    // Cms
    Route::get('/cms/banners', [HomePageController::class, 'getBanners']);
    Route::get('/cms/quality-control', [HomePageController::class, 'getQualityControl']);
    Route::get('/cms/about-us', [CmsController::class, 'getAboutPage']);
    Route::get('/cms/how-it-works', [CmsController::class, 'getHowItWorksPage']);
    Route::get('/cms/contact-us', [CmsController::class, 'getContactPage']);
    Route::get('/cms/faqs', [FaqApiController::class, 'getFaqs']);
    Route::get('/cms/page/{slug}', [CmsController::class, 'getPageBySlug']);
    Route::get('/cms/web-settings', [WebSettingApiController::class, 'getWebSettings']);

    // Blogs
    Route::get('/blogs', [BlogApiController::class, 'index']);
    Route::get('/blogs/{id}', [BlogApiController::class, 'show']);

});

// Specialist & Consultation APIs
Route::get('/consultations/specialists', [ConsultationController::class, 'getSpecialists']);
Route::post('/consultations/book', [ConsultationController::class, 'book']);
Route::get('/consultations/nearby-gyms', [GymApiController::class, 'getNearbyGyms']);
Route::get('/consultations/nearby-pharmacies', [PharmacyApiController::class, 'getNearbyPharmacies']);

// Authenticated User Information, Address & Orders & Consultation APIs
Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'user'
], function () {
    // My Information, Personal Profile & Fitness Profile
    Route::get('/my-information', [MyInformationController::class, 'index']);
    Route::post('/personal-info/update', [MyInformationController::class, 'updatePersonalInfo']);
    Route::post('/fitness-profile/update', [MyInformationController::class, 'updateFitnessProfile']);
    Route::get('/bmi-gauge', [MyInformationController::class, 'getBmiGauge']);
    Route::get('/supplement-intake-history', [MyInformationController::class, 'getSupplementHistory']);

    // Multi-Address Management
    Route::get('/addresses', [UserAddressController::class, 'index']);
    Route::post('/addresses', [UserAddressController::class, 'store']);
    Route::get('/addresses/{id}', [UserAddressController::class, 'show']);
    Route::post('/addresses/{id}/update', [UserAddressController::class, 'update']);
    Route::post('/addresses/{id}/delete', [UserAddressController::class, 'destroy']);
    Route::post('/addresses/{id}/set-default', [UserAddressController::class, 'setDefault']);

    // Orders Management APIs
    Route::get('/orders', [UserOrderController::class, 'index']);
    Route::get('/orders/{identifier}', [UserOrderController::class, 'show']);
    Route::get('/orders/{identifier}/invoice', [UserOrderController::class, 'invoice']);
    Route::get('/orders/{identifier}/invoice-view', [UserOrderController::class, 'invoiceView']);

    // Consultation Bookings APIs
    Route::post('/consultations/book', [ConsultationController::class, 'book']);
    Route::get('/consultations/my-bookings', [ConsultationController::class, 'myBookings']);
    Route::get('/consultations/nearby-gyms', [GymApiController::class, 'getNearbyGyms']);
    Route::get('/consultations/nearby-pharmacies', [PharmacyApiController::class, 'getNearbyPharmacies']);

    // User Settings APIs (Password, Notifications, Account Deletion)
    Route::get('/settings', [UserSettingsController::class, 'index']);
    Route::get('/settings/notifications', [UserSettingsController::class, 'getNotifications']);
    Route::post('/settings/notifications', [UserSettingsController::class, 'updateNotifications']);
    Route::post('/settings/password', [UserSettingsController::class, 'updatePassword']);
    Route::post('/settings/change-password', [UserSettingsController::class, 'updatePassword']);
    Route::post('/settings/delete-account', [UserSettingsController::class, 'deleteAccount']);



    // sabbir test 
});