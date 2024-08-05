<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\CompanyGallery\App\Http\Controllers\CompanyGalleryController;


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
    Route::prefix('company')->group(function () {
        Route::post('gallery/create', [CompanyGalleryController::class, 'save']);
        Route::get('gallery/list', [CompanyGalleryController::class, 'list']);

        Route::get('gallery/edit/{id}', [CompanyGalleryController::class, 'edit']);
        Route::post('gallery/update/{id}', [CompanyGalleryController::class, 'update']);
        Route::delete('gallery/delete/{id}', [CompanyGalleryController::class, 'destroy']);

        Route::post('gallery/search', [CompanyGalleryController::class, 'search']);

    });
});