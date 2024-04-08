<?php

use Illuminate\Support\Facades\Route;
use Modules\MainScreen\App\Http\Controllers\MainScreenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('mainscreen', MainScreenController::class)->names('mainscreen');
});
