<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\CompanyEmployee\App\Http\Controllers\CompanyEmployeeController;

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
    Route::prefix('employee')->group(function () {
        Route::resource('/', CompanyEmployeeController::class)->only([
            'index', 'store'
        ]);

        Route::get('remove-access/{employee}/{property}', [CompanyEmployeeController::class, 'removeAccess']);
        Route::get('search-access/{employee}', [CompanyEmployeeController::class, 'searchAccess']);

        Route::get('{employee}', [CompanyEmployeeController::class, 'show']);
        Route::post('{employee}', [CompanyEmployeeController::class, 'update']);
        Route::post('change-password/{employee}', [CompanyEmployeeController::class, 'changePassword']);
        Route::post('add-access/{employee}/{property}', [CompanyEmployeeController::class, 'addAccess']);

        Route::post('/{employee}/properties', [CompanyEmployeeController::class, 'saveProperties']);
        Route::put('/{employee}/properties/{property}', [CompanyEmployeeController::class, 'updateProperty']);
        Route::delete('/{employee}/properties/{property}', [CompanyEmployeeController::class, 'destroyProperty']);
    });
});

Route::prefix('account')
    ->middleware(['auth:api'])
    ->name('api.')
    ->group(function() {
        Route::prefix('company')->group(function(){
            Route::post('{employee}/accessProperties', [CompanyEmployeeController::class,'addAccessProperties']);
        });
    });

