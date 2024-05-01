<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\MainScreenPrivacy\App\Http\Controllers\MainScreenPrivacyController;

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
    Route::prefix('main-screen/{main_screen}/privacies')->group(function () {
        Route::get('/', [MainScreenPrivacyController::class, 'index']);
        Route::post('/', [MainScreenPrivacyController::class, 'store']);
    });
});
