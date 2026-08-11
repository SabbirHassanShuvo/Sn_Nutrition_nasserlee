<?php

use App\Http\Controllers\Web\Backend\Settings\MailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\Settings\SystemController;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\Settings\WebSettingController;

Route::group(["prefix"=> "settings", "as"=> "settings."], function () {
    Route::controller(ProfileController::class)->name('profile.')->middleware('permission:setting_profile')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('upload-avatar','avatar')->name('avatar.upload');
        Route::post('upload-banner','banner')->name('banner.upload');
        Route::patch('update-profile', 'update')->name('update');
    });

    Route::controller(SystemController::class)->prefix('system/')->name('system.')->middleware('permission:setting_system')->group(function(){
        Route::get('', 'index')->name('index');
        Route::put('update', 'update')->name('update');
    });

    Route::controller(WebSettingController::class)->prefix('web-setting/')->name('web-setting.')->middleware('permission:setting_system')->group(function(){
        Route::get('', 'index')->name('index');
        Route::put('update', 'update')->name('update');
    });

    Route::controller(MailController::class)->prefix('mail/')->name('mail.')->middleware('permission:setting_mail')->group(function(){
        Route::get('', 'index')->name('index');
        Route::put('update', 'update')->name('update');
    });
});