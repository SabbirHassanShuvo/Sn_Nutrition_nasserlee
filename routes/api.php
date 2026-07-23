<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\Frontend\MyInformationController;
use App\Http\Controllers\Api\Frontend\UserAddressController;
use App\Http\Controllers\Api\Frontend\UserOrderController;

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

// Authenticated User Information, Address & Orders APIs (GET & POST Only)
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

    // Standalone Multi-Address Management
    Route::get('/addresses', [UserAddressController::class, 'index']);
    Route::post('/addresses', [UserAddressController::class, 'store']);
    Route::get('/addresses/{id}', [UserAddressController::class, 'show']);
    Route::post('/addresses/{id}/update', [UserAddressController::class, 'update']);
    Route::post('/addresses/{id}/delete', [UserAddressController::class, 'destroy']);
    Route::post('/addresses/{id}/set-default', [UserAddressController::class, 'setDefault']);

    // Orders Tab APIs
    Route::get('/orders', [UserOrderController::class, 'index']);
    Route::get('/orders/{identifier}', [UserOrderController::class, 'show']);
    Route::get('/orders/{identifier}/invoice', [UserOrderController::class, 'invoice']);
    Route::get('/orders/{identifier}/invoice-view', [UserOrderController::class, 'invoiceView']);
});