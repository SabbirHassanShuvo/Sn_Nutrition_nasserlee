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

    Route::group(['as'=>'feature.'], function(){
        Route::post('faq/status/{id}', [FaqController::class,'status'])->name('faq.status');
        Route::resource('faq', FaqController::class)->except(['show']);
    });

    Route::post('product/status/{id}', [\App\Http\Controllers\Web\Backend\ProductController::class,'status'])->name('product.status');
    Route::resource('product', \App\Http\Controllers\Web\Backend\ProductController::class);

    Route::post('category/status/{id}', [\App\Http\Controllers\Web\Backend\CategoryController::class,'status'])->name('category.status');
    Route::resource('category', \App\Http\Controllers\Web\Backend\CategoryController::class)->except(['show']);

    Route::post('brand/status/{id}', [\App\Http\Controllers\Web\Backend\BrandController::class,'status'])->name('brand.status');
    Route::resource('brand', \App\Http\Controllers\Web\Backend\BrandController::class)->except(['show']);


    Route::post('page/status/{id}', [PageController::class,'status'])->name('page.status');
    Route::resource('page', PageController::class)->except(['show']);
    
    Route::post('system-user/status/{id}', [SystemUserController::class,'status'])
    ->name('system-user.status')->middleware('permission:user_management');
    
    Route::resource('system-user', SystemUserController::class)
    ->except(['show'])->middleware('permission:user_management');

    Route::post('onboarding-option/status/{id}', [\App\Http\Controllers\Web\Backend\OnboardingOptionController::class,'status'])->name('onboarding-option.status');
    Route::resource('onboarding-option', \App\Http\Controllers\Web\Backend\OnboardingOptionController::class)->except(['show']);
    
    Route::resource('role', RoleController::class)->middleware('permission:role_management');

    Route::post('app-user/status/{id}', [\App\Http\Controllers\Web\Backend\AppUserController::class,'status'])->name('app-user.status');
    Route::post('app-user/bulk-delete', [\App\Http\Controllers\Web\Backend\AppUserController::class,'bulkDelete'])->name('app-user.bulk-delete');
    Route::resource('app-user', \App\Http\Controllers\Web\Backend\AppUserController::class);

    require_once __DIR__ .'/settings.php';
});
