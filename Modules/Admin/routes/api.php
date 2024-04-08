<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\App\Http\Controllers\AdminController;

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
});
Route::get('/companies/websiteProperties', [AdminController::class, 'getAllProperties']);
