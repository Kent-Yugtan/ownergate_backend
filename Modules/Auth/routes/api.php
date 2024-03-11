<?php

use Illuminate\Http\Request;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\UserController;
use Nnjeim\World\World;

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

Route::prefix('auth')->group(function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('validate-verification-token/{token}', [AuthController::class, 'validateVerificationToken']);
    Route::post('validate-verification-code/{token}', [AuthController::class, 'validateVerificationCode']);
    Route::post('complete-profile', [AuthController::class, 'completeProfile']);
    Route::post('resend-verification', [AuthController::class, 'resendVerification']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::get('reset-password-validate-token/{token}', [AuthController::class, 'validateResetPasswordToken']);
    Route::post('reset-password-verify', [AuthController::class, 'verifyResetPasswordCode']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('user/update', [AuthController::class, 'updateProfile']);
        Route::post('user/change-password', [AuthController::class, 'changePassword']);
        Route::get('roles', [AuthController::class, 'listRoles']);
    });

    #========================================================================================#
    //Social Login routes
    #========================================================================================#
    Route::middleware('guest')->group(function () {
        Route::get('/{provider}', [AuthController::class, 'socialLoginRedirectToProvider']);
        Route::get('/{provider}/callback/{token?}', [AuthController::class, 'socialLoginHandleProviderCallback']);
    });
    #========================================================================================#
    #========================================================================================#

    Route::get('hashPassword', function () {
        return bcrypt(12345678);
    });
});

Route::prefix('user')->middleware(['auth:api'])->group(function () {
    Route::post('update', [UserController::class, 'updateProfile']);
});

Route::get('get_countries', function (Request $request) {
    $action =  World::countries(['fields' => $request->fields]);

    // ['fields' => 'states,cities']
    if ($action->success) {
        $countries = $action->data;
    }
    return $countries;
});

Route::get('get_currencies', function (Request $request) {
    $action =  World::currencies($request->filters);

    // ['fields' => 'states,cities']
    if ($action->success) {
        $currencies = $action->data;
    }
    return $currencies;
});

Route::get('world/cities', function (Request $request) {
    $filters = (array) json_decode($request->filters, true);
    $action =  World::cities($filters);
    if ($action->success) {
        $cities = $action->data;
    }
    return $cities;
});

Route::get('world/states', function (Request $request) {
    $filters = (array) json_decode($request->filters, true);
    $action =  World::states($filters);
    if ($action->success) {
        $states = $action->data;
    }
    return $states;
});
