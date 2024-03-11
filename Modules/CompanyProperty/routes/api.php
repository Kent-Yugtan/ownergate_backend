<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\CompanyProperty\App\Http\Controllers\PropertyMediaController;
use Modules\CompanyProperty\App\Http\Controllers\CompanyPropertyController;

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

// Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
//     Route::get('companyproperty', fn (Request $request) => $request->user())->name('companyproperty');
// });

Route::prefix('admin')->middleware(['auth:api'])->group(function () {
    Route::prefix('company/{company}/properties')->group(function () {
        Route::post('/logo', [CompanyPropertyController::class, 'saveLogo']);
        Route::post('/poster', [CompanyPropertyController::class, 'savePoster']);
        Route::post('/value', [CompanyPropertyController::class, 'saveValue']);
        Route::post('/source', [CompanyPropertyController::class, 'saveSource']);
        Route::post('/description', [CompanyPropertyController::class, 'saveDescription']);
        Route::post('/overview', [CompanyPropertyController::class, 'saveOverview']);
        Route::post('/details', [CompanyPropertyController::class, 'saveDetails']);
        Route::post('/features', [CompanyPropertyController::class, 'saveFeatures']);
        Route::post('/amenities', [CompanyPropertyController::class, 'saveAmenities']);
        Route::post('/utilities', [CompanyPropertyController::class, 'saveUtilities']);
        Route::post('/owner-details', [CompanyPropertyController::class, 'saveUtilities']);
        Route::post('/unitality-details', [CompanyPropertyController::class, 'saveUnitalityDetails']);
        Route::post('/condition-roles-details', [CompanyPropertyController::class, 'saveUnitalityDetails']);
        Route::post('/additional-remark', [CompanyPropertyController::class, 'saveRemark']);
        Route::post('/whats-nearby', [CompanyPropertyController::class, 'saveWhatsNearby']);
        Route::post('/address-details', [CompanyPropertyController::class, 'saveWhatsNearby']);
        Route::post('/map-location', [CompanyPropertyController::class, 'saveMapLocation']);
        Route::post('/view-live', [CompanyPropertyController::class, 'saveWhatsNearby']);
        Route::post('/plans', [CompanyPropertyController::class, 'savePlans']);

        Route::post('/full-video', [PropertyMediaController::class, 'saveFullVideo']);
        Route::post('/360-virtual-tour', [PropertyMediaController::class, 'saveVirtualTour']);
        Route::post('/virtual-spots', [PropertyMediaController::class, 'saveVirtualSpots']);
        Route::post('/photos', [PropertyMediaController::class, 'savePhotos']);
    });

    Route::get('property/category-types', [CompanyPropertyController::class, 'getCategories']);
    Route::get('property/get-overviews', [CompanyPropertyController::class, 'getOverviews']);
    Route::get('property/get-details', [CompanyPropertyController::class, 'getDetails']);
    Route::get('property/get-features', [CompanyPropertyController::class, 'getFeatures']);

});