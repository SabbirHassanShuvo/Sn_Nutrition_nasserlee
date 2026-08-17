<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\Frontend\Cms\HomePageController;
use App\Http\Controllers\Api\Frontend\Cms\CmsController;
use App\Http\Controllers\Api\Frontend\Cms\FaqApiController;
use App\Http\Controllers\Api\Frontend\Cms\WebSettingApiController;
use App\Http\Controllers\Api\Frontend\Cms\OfferApiController;
use App\Http\Controllers\Api\Frontend\BlogApiController;
use App\Http\Controllers\Api\Frontend\MyInformationController;
use App\Http\Controllers\Api\Frontend\UserAddressController;
use App\Http\Controllers\Api\Frontend\UserOrderController;
use App\Http\Controllers\Api\Frontend\ConsultationController;
use App\Http\Controllers\Api\Frontend\GymApiController;
use App\Http\Controllers\Api\Frontend\PharmacyApiController;
use App\Http\Controllers\Api\Frontend\UserSettingsController;
use App\Http\Controllers\Api\Frontend\OnboardingQuestionApiController;
use App\Http\Controllers\Api\Frontend\Profile\ProfileController;
use App\Http\Controllers\Api\Frontend\ContactSubmissionApiController;
use App\Http\Controllers\Api\Frontend\SubscriberApiController;
use App\Http\Controllers\Api\Frontend\BrandApiController;
use App\Http\Controllers\Api\Frontend\ReviewApiController;


Route::group(['middleware' => 'api'], function ($router) {

    // Auth Routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/password/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/auth/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/auth/password/resend-otp', [AuthController::class, 'resendOtp']);
    // Google Auth
    Route::post('/google', [GoogleAuthController::class, 'login']);

    // Cms
    Route::get('/cms/banners', [HomePageController::class, 'getBanners']);
    Route::get('/cms/quality-control', [HomePageController::class, 'getQualityControl']);
    Route::get('/cms/about-us', [CmsController::class, 'getAboutPage']);
    Route::get('/cms/how-it-works', [CmsController::class, 'getHowItWorksPage']);
    Route::get('/cms/contact-us', [CmsController::class, 'getContactPage']);
    Route::get('/get-reviews', [ReviewApiController::class, 'getReviews']);
    Route::get('/cms/faqs', [FaqApiController::class, 'getFaqs']);
    Route::get('/cms/page/{slug}', [CmsController::class, 'getPageBySlug']);
    Route::get('/cms/web-settings', [WebSettingApiController::class, 'getWebSettings']);
    Route::get('/offers', [OfferApiController::class, 'index']);
    Route::get('/brands', [BrandApiController::class, 'index']);

    // Blogs
    Route::get('/blogs', [BlogApiController::class, 'index']);
    Route::get('/blogs/{id}', [BlogApiController::class, 'show']);

    // Onboarding Questions & CMS Card Text
    Route::get('/onboarding/questions', [OnboardingQuestionApiController::class, 'getQuestions']);

    Route::post('/contact/submit', [ContactSubmissionApiController::class, 'submit']);
    Route::post('/subscribe', [SubscriberApiController::class, 'subscribe']);

    Route::get('/product/reviews/{productId}', [ReviewApiController::class, 'getProductReviews']);


});

Route::group(['middleware' => 'auth:api'], function ($router) {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::get('/auth/profile', [AuthController::class, 'profileRetrieval']);
    Route::post('/auth/profile/update', [AuthController::class, 'ProfileUpdate']);
    Route::post('/auth/password/change', [AuthController::class, 'ChangePassword']);

    // My Information, Personal Profile & Fitness Profile
    Route::get('/user/my-information', [MyInformationController::class, 'index']);
    Route::post('/user/personal-info/update', [MyInformationController::class, 'updatePersonalInfo']);
    Route::post('/user/fitness-profile/update', [MyInformationController::class, 'updateFitnessProfile']);
    Route::get('/user/bmi-gauge', [MyInformationController::class, 'getBmiGauge']);
    Route::get('/user/supplement-intake-history', [MyInformationController::class, 'getSupplementHistory']);

    // Multi-Address Management
    Route::get('/user/addresses', [UserAddressController::class, 'index']);
    Route::post('/user/addresses', [UserAddressController::class, 'store']);
    Route::get('/user/addresses/{id}', [UserAddressController::class, 'show']);
    Route::post('/user/addresses/{id}/update', [UserAddressController::class, 'update']);
    Route::post('/user/addresses/{id}/delete', [UserAddressController::class, 'destroy']);
    Route::post('/user/addresses/{id}/set-default', [UserAddressController::class, 'setDefault']);

    // Orders Management APIs
    Route::get('/user/orders', [UserOrderController::class, 'index']);
    Route::get('/user/orders/{identifier}', [UserOrderController::class, 'show']);
    Route::get('/user/orders/{identifier}/invoice', [UserOrderController::class, 'invoice']);
    Route::get('/user/orders/{identifier}/invoice-view', [UserOrderController::class, 'invoiceView']);

    // Consultation Bookings APIs
    Route::post('/user/consultations/book', [ConsultationController::class, 'book']);
    Route::get('/user/consultations/specialists', [ConsultationController::class, 'getSpecialists']);
    Route::get('/user/consultations/my-bookings', [ConsultationController::class, 'myBookings']);
    Route::get('/user/consultations/nearby-gyms', [GymApiController::class, 'getNearbyGyms']);
    Route::get('/user/nearby-pharmacies', [PharmacyApiController::class, 'getNearbyPharmacies']);


    // User Settings APIs (Password, Notifications, Account Deletion)
    Route::get('/user/settings', [UserSettingsController::class, 'index']);
    Route::get('/user/settings/notifications', [UserSettingsController::class, 'getNotifications']);
    Route::post('/user/settings/notifications', [UserSettingsController::class, 'updateNotifications']);
    Route::post('/user/settings/password', [UserSettingsController::class, 'updatePassword']);
    Route::post('/user/settings/change-password', [UserSettingsController::class, 'updatePassword']);
    Route::post('/user/settings/delete-account', [UserSettingsController::class, 'deleteAccount']);

    Route::get('/health-professional/profile', [ProfileController::class, 'detailsProfile']);
    Route::post('/health-professional/profile/update', [ProfileController::class, 'updateProfile']);

    Route::post('/submit-review', [ReviewApiController::class, 'submitReview']);
    Route::post('/product/submit-review', [ReviewApiController::class, 'submitProductReview']);
    
    // Onboarding Submit
    Route::post('/onboarding/submit', [OnboardingQuestionApiController::class, 'submitAnswers']);
    
});


// Sendit Webhook Route
Route::post('/webhook/sendit', [App\Http\Controllers\Api\SenditWebhookController::class, 'handle']);