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

// Partner Onboarding Flow
Route::group([
    'middleware' => 'api',
    'prefix' => 'partner/onboarding'
], function ($router) {
    Route::get('/options/specialties', [PartnerOnboardingController::class, 'getSpecialties']);
    Route::get('/options/certifications', [PartnerOnboardingController::class, 'getCertifications']);
    Route::post('/step-1', [PartnerOnboardingController::class, 'step1']);
    Route::post('/step-2', [PartnerOnboardingController::class, 'step2']);
    Route::post('/step-3', [PartnerOnboardingController::class, 'step3']);
    Route::post('/step-4', [PartnerOnboardingController::class, 'step4']);
});