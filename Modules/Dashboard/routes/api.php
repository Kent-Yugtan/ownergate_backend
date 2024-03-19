<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Dashboard\App\Http\Controllers\DashboardController;

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

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('shortcut', [DashboardController::class, 'getShortcuts']);
        Route::post('shortcut', [DashboardController::class, 'addShortcut']);
        Route::post('shortcut/{shortcut}', [DashboardController::class, 'updateShortcut']);
        Route::delete('shortcut/{shortcut}', [DashboardController::class, 'deleteShortcut']);
    });

});
