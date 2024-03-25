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
    Route::post('employee/{employee}', [CompanyEmployeeController::class, 'update']);
    Route::post('employee/change-password/{employee}', [CompanyEmployeeController::class, 'changePassword']);
    Route::post('employee/add-access/{employee}/{property}', [CompanyEmployeeController::class, 'addAccess']);
    Route::get('employee/remove-access/{employee}/{property}', [CompanyEmployeeController::class, 'removeAccess']);
    Route::get('employee/search-access/{employee}', [CompanyEmployeeController::class, 'searchAccess']);
    Route::resource('employee', CompanyEmployeeController::class)->only([
        'index', 'store', 'show'
    ]);
});