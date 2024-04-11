<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\PropertyPrivacy\App\Http\Controllers\PropertyPrivacyController;

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
    Route::prefix('property/{property}/privacies')->group(function () {
        Route::get('/', [PropertyPrivacyController::class, 'index']);
        Route::post('/', [PropertyPrivacyController::class, 'store']);
        
    });
});
