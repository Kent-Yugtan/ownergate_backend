<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Company\App\Http\Controllers\CompanyController;
use Modules\Company\App\Http\Controllers\CompanyNewsController;
use Modules\Company\App\Http\Controllers\CompanyServiceController;
use Modules\Company\App\Http\Controllers\CompanyLocationController;
use Modules\Company\App\Http\Controllers\CompanyManagementController;

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
        Route::apiResource('/{company}/profile', CompanyController::class)->only([
            'index', 'store'
        ]);
        
        Route::apiResource('/{company}/management', CompanyManagementController::class);

        Route::apiResource('/{company}/news', CompanyNewsController::class)->scoped([
            'companies' => 'company:id',
        ]);
        Route::apiResource('/{company}/services', CompanyServiceController::class)->scoped([
            'companies' => 'company:id',
        ]);
        Route::apiResource('/{company}/locations', CompanyLocationController::class)->scoped([
            'companies' => 'company:id',
        ]);

        Route::post('store', [CompanyController::class, 'saveCompany']);
        Route::get('lists', [CompanyController::class, 'lists']);
        Route::get('{company}/get-properties/', [CompanyController::class, 'getProperties']);
        Route::get('{company}/get-attachments/', [CompanyController::class, 'getAttachments']);
        Route::post('{company}/add-attachment/', [CompanyController::class, 'addAttachment']);
        Route::delete('{company}/remove-attachment/{attachment}', [CompanyController::class, 'removeAttachment']);

        Route::get('{company}/team/', [CompanyController::class, 'getTeam']);
        Route::post('{company}/add-member/', [CompanyController::class, 'addMember']);
        Route::post('{company}/update-member/{member}', [CompanyController::class, 'updateMember']);
        Route::delete('{company}/remove_member/{member}', [CompanyController::class, 'removeMember']);

        Route::get('{company}/accounts-list', [CompanyController::class, 'accountsList']);
        Route::post('{company}/add-property/{property}', [CompanyController::class, 'addProperty']);
        Route::post('{company}/change-status', [CompanyController::class, 'updateStatus']);
        Route::post('{company}/privacy', [CompanyController::class, 'changePrivacy']);
    });
});

Route::prefix('admin')->group(function () {
    Route::apiResource('company-types', CompanyTypeController::class);
});
