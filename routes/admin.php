<?php

use App\Http\Controllers\Web\Backend\SystemUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\RoleController;
use App\Http\Controllers\Web\Backend\SiteController;
use App\Http\Controllers\Web\Backend\ProjectController;


use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

Route::group([ 'as'=>'backend.'], function () {

    require_once __DIR__.'/queue.php';

    Route::get('/', [SiteController::class,'index'])->name('dashboard.index');
    Route::resource('project', ProjectController::class)->except(['show']);

    // FAQ Management (CMS)
    Route::group(['middleware' => 'permission:cms_faq|faq_manage|faq_management', 'as'=>'feature.'], function(){
        Route::post('faq/status/{id}', [FaqController::class,'status'])->name('faq.status');
        Route::resource('faq', FaqController::class)->except(['show']);
    });

    // Product Management
    Route::group(['middleware' => 'permission:products_manage|product_manage'], function () {
        Route::post('product/status/{id}', [\App\Http\Controllers\Web\Backend\ProductController::class,'status'])->name('product.status');
        Route::resource('product', \App\Http\Controllers\Web\Backend\ProductController::class);
    });

    // Category Management
    Route::group(['middleware' => 'permission:categories_manage|category_manage'], function () {
        Route::post('category/status/{id}', [\App\Http\Controllers\Web\Backend\CategoryController::class,'status'])->name('category.status');
        Route::resource('category', \App\Http\Controllers\Web\Backend\CategoryController::class)->except(['show']);
    });

    // Brand Management
    Route::group(['middleware' => 'permission:brands_manage|brand_manage'], function () {
        Route::post('brand/status/{id}', [\App\Http\Controllers\Web\Backend\BrandController::class,'status'])->name('brand.status');
        Route::resource('brand', \App\Http\Controllers\Web\Backend\BrandController::class)->except(['show']);
    });

    // Page Management (CMS)
    Route::group(['middleware' => 'permission:cms_pages|page_manage|page_management'], function () {
        Route::post('page/status/{id}', [PageController::class,'status'])->name('page.status');
        Route::resource('page', PageController::class)->except(['show']);
    });
    
    // System Users
    Route::post('system-user/status/{id}', [SystemUserController::class,'status'])
        ->name('system-user.status')->middleware('role:super_admin');
    Route::get('system-user/{id}/permissions', [SystemUserController::class, 'getUserPermissions'])
        ->name('system-user.permissions')->middleware('role:super_admin');
    Route::post('system-user/{id}/permissions', [SystemUserController::class, 'syncUserPermissions'])
        ->name('system-user.permissions.sync')->middleware('role:super_admin');
    Route::resource('system-user', SystemUserController::class)
        ->except(['show'])->middleware('role:super_admin');

    // Onboarding Options
    Route::group(['middleware' => 'permission:onboarding_options_manage|onboarding_manage'], function () {
        Route::post('onboarding-option/status/{id}', [\App\Http\Controllers\Web\Backend\OnboardingOptionController::class,'status'])->name('onboarding-option.status');
        Route::resource('onboarding-option', \App\Http\Controllers\Web\Backend\OnboardingOptionController::class)->except(['show']);
    });
    
    // Roles
    Route::resource('role', RoleController::class)->middleware('role:super_admin');
    
    // Custom Permissions Management
    Route::get('permission', [RoleController::class, 'permissionIndex'])->name('permission.index')->middleware('role:super_admin');
    Route::post('permission', [RoleController::class, 'permissionStore'])->name('permission.store')->middleware('role:super_admin');
    Route::get('permission/{id}', [RoleController::class, 'permissionShow'])->name('permission.show')->middleware('role:super_admin');
    Route::put('permission/{id}', [RoleController::class, 'permissionUpdate'])->name('permission.update')->middleware('role:super_admin');
    Route::delete('permission/{id}', [RoleController::class, 'permissionDestroy'])->name('permission.destroy')->middleware('role:super_admin');

    // App User Management
    Route::group(['middleware' => 'permission:user_management'], function () {
        Route::post('app-user/status/{id}', [\App\Http\Controllers\Web\Backend\AppUserController::class,'status'])->name('app-user.status');
        Route::post('app-user/bulk-delete', [\App\Http\Controllers\Web\Backend\AppUserController::class,'bulkDelete'])->name('app-user.bulk-delete');
        Route::resource('app-user', \App\Http\Controllers\Web\Backend\AppUserController::class);
    });

    // Orders Management
    Route::group(['middleware' => 'permission:orders_manage|order_manage'], function () {
        Route::post('order/status/{id}', [\App\Http\Controllers\Web\Backend\OrderController::class,'updateStatus'])->name('order.status');
        Route::post('order/verify-payment/{id}', [\App\Http\Controllers\Web\Backend\OrderController::class,'verifyPayment'])->name('order.verify-payment');
        Route::resource('order', \App\Http\Controllers\Web\Backend\OrderController::class);
    });

    // Promo Codes Management
    Route::group(['middleware' => 'permission:promo_codes_manage|promo_code_manage'], function () {
        Route::post('promo-code/status/{id}', [\App\Http\Controllers\Web\Backend\PromoCodeController::class,'status'])->name('promo-code.status');
        Route::resource('promo-code', \App\Http\Controllers\Web\Backend\PromoCodeController::class);
    });



    require_once __DIR__ .'/settings.php';
});
