<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\CompanyRequest\App\Http\Controllers\CompanyRequestController;

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
        Route::apiResource('/requests', CompanyRequestController::class)->only([
            'index', 'store', 'update'
        ]);

        Route::get('/requests/search', [CompanyRequestController::class, 'search']);

        Route::get('/requests/employee', [CompanyRequestController::class, 'getEmployeeRequests']);

        
    });
