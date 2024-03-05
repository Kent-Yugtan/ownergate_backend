<?php

namespace Modules\Auth\Repositories\Interfaces;

use Illuminate\Http\Request;

interface AuthRepositoryInterface 
{
    public function login(Request $request);
    public function logout(Request $request);
    public function getAuthUser(Request $request);
    public function register(Request $request);
    public function verificationEmail($user);
    public function completeProfile($request);
    public function updateProfile(Request $request);
    public function findUser($params);
    public function updateUser($user, $params);
    public function forgotPassword(Request $request);
    public function forgotPasswordEmail($reset);
    public function validateResetPasswordToken($token);
    public function verifyResetPasswordCode(Request $request);
    public function resetPassword(Request $request);
    public function redirectToProvider($provider);
    public function AddSocialUser($provider);
    public function validateVerificationCode($token, $code);
}