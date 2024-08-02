<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\CompanyRequest\App\Http\Controllers\CompanyRequestController;
use Modules\CompanyRequest\App\Http\Controllers\EmployeeRequestController;

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

Route::prefix('account')
    ->middleware(['auth:api'])
    ->name('api.')
    ->group(function () {
        Route::prefix('requests')->group(function () {
            Route::get('/', [CompanyRequestController::class, 'getCompanyRequests']);

            Route::get('/pending', [CompanyRequestController::class, 'getCompanyPendingRequests']);

            Route::patch('/{companyRequest}', [CompanyRequestController::class, 'updateRequest']);

            Route::delete('/{companyRequest}', [CompanyRequestController::class, 'delete']);

            Route::get('/search', [CompanyRequestController::class, 'search']);

            Route::get('/search-pending', [CompanyRequestController::class, 'searchPending']);

            Route::prefix('employee')->group(function () {
                Route::get('/', [EmployeeRequestController::class, 'getEmployeeRequests']);

                Route::get('/preview-property', [EmployeeRequestController::class, 'previewProperty']);

                Route::get('/search', [EmployeeRequestController::class, 'search']);

                Route::post('/send-request', [EmployeeRequestController::class, 'sendRequest']);

                Route::delete('/{employeeRequest}', [EmployeeRequestController::class, 'delete']);
            });
        });
    });
