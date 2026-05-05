<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\PartnerOnboardingController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:api');

    
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/password/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/password/verify-otp', [AuthController::class, 'verifyOtp']);

    // Google Auth
    Route::post('/google', [GoogleAuthController::class, 'login']);
});

// Partner Onboarding Flow
Route::group([
    'middleware' => 'api',
    'prefix' => 'partner/onboarding'
], function ($router) {
    Route::post('/step-1', [PartnerOnboardingController::class, 'step1']);
    Route::post('/step-2', [PartnerOnboardingController::class, 'step2']);
    Route::post('/step-3', [PartnerOnboardingController::class, 'step3']);
    Route::post('/step-4', [PartnerOnboardingController::class, 'step4']);
});