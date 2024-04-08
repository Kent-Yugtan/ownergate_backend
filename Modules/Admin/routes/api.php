<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\App\Http\Controllers\AdminController;
use Modules\Admin\App\Http\Controllers\DiscoverPropertiesController;

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

Route::prefix('admin')->middleware(['auth:api'])->group(function () {
    Route::get('/companies', [AdminController::class, 'getAllCompanies']);
    Route::get('/companies/properties', [AdminController::class, 'getAllProperties']);

    Route::prefix('discover-properties')->middleware(['auth:api'])->group(function () {
        Route::get('/', [DiscoverPropertiesController::class, 'index']);
        Route::get('cities', [DiscoverPropertiesController::class, 'getAllPropertiesCities']);
        Route::post('cities', [DiscoverPropertiesController::class, 'savePropertyCities']);
        Route::post('cities/{city}/listing', [DiscoverPropertiesController::class, 'savePropertyListing']);
    });
});
Route::get('/companies/websiteProperties', [AdminController::class, 'getAllProperties']);
