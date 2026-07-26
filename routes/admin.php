<?php

use App\Http\Controllers\Web\Backend\SystemUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\RoleController;
use App\Http\Controllers\Web\Backend\SiteController;
use App\Http\Controllers\Web\Backend\ProjectController;
use App\Http\Controllers\Web\Backend\Cms\BannerSectionController;
use App\Http\Controllers\Web\Backend\Cms\HomePageController;
use App\Http\Controllers\Web\Backend\Cms\FaqCategoryController;
use App\Http\Controllers\Web\Backend\Cms\ContactPageController;


use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

Route::group([ 'as'=>'backend.'], function () {

    require_once __DIR__.'/queue.php';

    Route::get('/', [SiteController::class,'index'])->name('dashboard.index');
    Route::resource('project', ProjectController::class)->except(['show']);



    // Product Management
    Route::group(['middleware' => 'permission:products_manage|product_manage'], function () {
        Route::delete('product/bulk-destroy', [\App\Http\Controllers\Web\Backend\ProductController::class,'bulkDestroy'])->name('product.bulk-destroy');
        Route::post('product/status/{id}', [\App\Http\Controllers\Web\Backend\ProductController::class,'status'])->name('product.status');
        Route::resource('product', \App\Http\Controllers\Web\Backend\ProductController::class);
    });

    // Category Management
    Route::group(['middleware' => 'permission:categories_manage|category_manage'], function () {
        Route::delete('category/bulk-destroy', [\App\Http\Controllers\Web\Backend\CategoryController::class,'bulkDestroy'])->name('category.bulk-destroy');
        Route::post('category/status/{id}', [\App\Http\Controllers\Web\Backend\CategoryController::class,'status'])->name('category.status');
        Route::resource('category', \App\Http\Controllers\Web\Backend\CategoryController::class)->except(['show']);
    });

    // Brand Management
    Route::group(['middleware' => 'permission:brands_manage|brand_manage'], function () {
        Route::delete('brand/bulk-destroy', [\App\Http\Controllers\Web\Backend\BrandController::class,'bulkDestroy'])->name('brand.bulk-destroy');
        Route::post('brand/status/{id}', [\App\Http\Controllers\Web\Backend\BrandController::class,'status'])->name('brand.status');
        Route::resource('brand', \App\Http\Controllers\Web\Backend\BrandController::class)->except(['show']);
    });

    // Page Management (CMS)
    Route::group(['middleware' => 'permission:cms_pages|page_manage|page_management'], function () {
        Route::post('page/status/{id}', [PageController::class,'status'])->name('page.status');
        Route::resource('page', PageController::class);
    });
    
    // System Users
    Route::delete('system-user/bulk-destroy', [SystemUserController::class,'bulkDestroy'])
        ->name('system-user.bulk-destroy')->middleware('role:super_admin');
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
        Route::delete('onboarding-option/bulk-destroy', [\App\Http\Controllers\Web\Backend\OnboardingOptionController::class,'bulkDestroy'])->name('onboarding-option.bulk-destroy');
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
        Route::delete('order/bulk-destroy', [\App\Http\Controllers\Web\Backend\OrderController::class,'bulkDestroy'])->name('order.bulk-destroy');
        Route::post('order/status/{id}', [\App\Http\Controllers\Web\Backend\OrderController::class,'updateStatus'])->name('order.status');
        Route::post('order/verify-payment/{id}', [\App\Http\Controllers\Web\Backend\OrderController::class,'verifyPayment'])->name('order.verify-payment');
        Route::resource('order', \App\Http\Controllers\Web\Backend\OrderController::class);
    });

    // Promo Codes Management
    Route::group(['middleware' => 'permission:promo_codes_manage|promo_code_manage'], function () {
        Route::delete('promo-code/bulk-destroy', [\App\Http\Controllers\Web\Backend\PromoCodeController::class,'bulkDestroy'])->name('promo-code.bulk-destroy');
        Route::post('promo-code/status/{id}', [\App\Http\Controllers\Web\Backend\PromoCodeController::class,'status'])->name('promo-code.status');
        Route::resource('promo-code', \App\Http\Controllers\Web\Backend\PromoCodeController::class);
    });


    // Cms 
    Route::group(['middleware' => 'permission:cms_banner|banner_manage'], function () {
        Route::delete('banner-section/bulk-destroy', [BannerSectionController::class,'bulkDestroy'])->name('banner-section.bulk-destroy');
        Route::post('banner-section/status/{id}', [BannerSectionController::class,'status'])->name('banner-section.status');
        Route::resource('banner-section', BannerSectionController::class)->except(['show']);
    });

    // CMS — Home Page sections
    Route::prefix('cms/home-page')->name('home-page.')->group(function () {
        Route::get('quality-control', [HomePageController::class, 'qualityControlEdit'])->name('quality-control.edit');
        Route::put('quality-control', [HomePageController::class, 'qualityControlUpdate'])->name('quality-control.update');
    });

    // CMS — About Page sections
    Route::group(['middleware' => 'permission:cms_about_page'], function () {
        Route::get('cms/about-us', [\App\Http\Controllers\Web\Backend\Cms\AboutPageController::class, 'edit'])->name('about-us.edit');
        Route::put('cms/about-us', [\App\Http\Controllers\Web\Backend\Cms\AboutPageController::class, 'update'])->name('about-us.update');
    });

    // CMS — Contact Page sections
    Route::group(['middleware' => 'permission:cms_contact_page'], function () {
        Route::get('cms/contact-us', [ContactPageController::class, 'edit'])->name('contact-us.edit');
        Route::put('cms/contact-us', [ContactPageController::class, 'update'])->name('contact-us.update');
    });

    // CMS — FAQ Category Management
    Route::group(['middleware' => 'permission:cms_faq|faq_manage|faq_management'], function () {
        Route::delete('faq-category/bulk-destroy', [FaqCategoryController::class, 'bulkDestroy'])->name('faq-category.bulk-destroy');
        Route::post('faq-category/status/{id}', [FaqCategoryController::class, 'status'])->name('faq-category.status');
        Route::resource('faq-category', FaqCategoryController::class)->except(['show']);
    });

    // CMS — FAQ Management
    Route::group(['middleware' => 'permission:cms_faq|faq_manage|faq_management', 'as'=>'feature.'], function(){
        Route::delete('faq/bulk-destroy', [FaqController::class,'bulkDestroy'])->name('faq.bulk-destroy');
        Route::post('faq/status/{id}', [FaqController::class,'status'])->name('faq.status');
        Route::resource('faq', FaqController::class)->except(['show']);
    });


    require_once __DIR__ .'/settings.php';
});
