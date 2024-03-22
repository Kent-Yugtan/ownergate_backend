<?php

use Illuminate\Support\Facades\Route;
use Modules\CompanyPrivacy\App\Http\Controllers\CompanyPrivacyController;

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
    Route::resource('companyprivacy', CompanyPrivacyController::class)->names('companyprivacy');
});
