<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\MainScreen\App\Http\Controllers\MainScreenController;
/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/
Route::prefix('admin')->group(function () {
    Route::resource('main-screen', MainScreenController::class)->only(['index', 'store'])->middleware(['auth:api']);
});

Route::prefix('public')->group(function() {
    Route::prefix('main-screen')->group(function(){
        Route::get('/', [MainScreenController::class, 'index']);
        Route::get('/filter-options', [MainScreenController::class, 'getFilterOptions']);
        Route::get('/properties', [MainScreenController::class, 'getProperties']);
    });
});