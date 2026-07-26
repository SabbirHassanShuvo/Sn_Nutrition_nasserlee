<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\API\Frontend\Cms\HomePageController;
use App\Http\Controllers\API\Frontend\Cms\CmsController;
use App\Http\Controllers\API\Frontend\Cms\FaqApiController;
use App\Http\Controllers\API\Frontend\Cms\WebSettingApiController;

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
    Route::get('/cms/contact-us', [CmsController::class, 'getContactPage']);
    Route::get('/cms/faqs', [FaqApiController::class, 'getFaqs']);
    Route::get('/cms/page/{slug}', [CmsController::class, 'getPageBySlug']);
    Route::get('/cms/web-settings', [WebSettingApiController::class, 'getWebSettings']);
});