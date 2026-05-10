<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Frontend\HomeController;

/*
|--------------------------------------------------------------------------
| Sabbir Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('home')->group(function () {
    Route::get('/products', [HomeController::class, 'getAllProducts']);
    Route::get('/products/filter', [HomeController::class, 'filterProducts']);
    Route::get('/filters', [HomeController::class, 'getFilters']);
});
