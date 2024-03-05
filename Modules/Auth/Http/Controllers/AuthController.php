<?php

namespace Modules\Auth\Http\Controllers;

use Carbon\Carbon;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Modules\Auth\Transformers\UserResource;
use Illuminate\Contracts\Support\Renderable;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Http\Requests\SaveProfileRequest;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Modules\Auth\Http\Requests\CompleteProfileRequest;
use Modules\Auth\Http\Requests\ChangePasswordRequest;
use Modules\Auth\Repositories\Interfaces\AuthRepositoryInterface;

class AuthController extends Controller
{
    use ApiResponser;

    private $authRepository;
    private $errors = [
        'reset-password-token-err' => 'Reset Password Token is invalid.',
        'reset-password-expired' => 'Reset Password Request has been expired.',
        'reset-password-verification-err' => 'Reset Password Verification Code is invalid.',
        'user-not-found' => 'User not found.',
    ];

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(LoginRequest $request)
    {
        try {
            $token = $this->authRepository->login($request);
            
            if($token) {

                if(!$request->user()->email_verified_at) {
                    return $this->errorResponse([
                        'errCode' => 'email-not-verified',
                        'verificationToken' => $request->user()->verification_token
                    ], 'Email has not yet verified.', 403);
                }

                return $this->successResponse($token, 'Credentials has been validated.');
            }

            return $this->errorResponse(null, 'Invalid credentials.', 403);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function user(Request $request)
    {
        try {
            return $this->successResponse(new UserResource($this->authRepository->getAuthUser($request)));
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authRepository->logout($request);
            return $this->successResponse(null, 'Logout successfully.');
        } catch(\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->authRepository->register($request);
            
            DB::commit();

            $this->authRepository->verificationEmail($user);
            return $this->successResponse(new UserResource($user), 'Registered Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function validateVerificationToken($token)
    {
        try {
            $verification = $this->authRepository->validateVerificationToken($token);
            
            if(!$verification) {
                return $this->errorResponse(null, 'Verification Token is invalid', 403);
            }

            return $this->successResponse(new UserResource($verification), 'Verification Token has been verified');

        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function validateVerificationCode($token, Request $request)
    {
        try {
            $verification = $this->authRepository->validateVerificationCode($token, $request->code);
            
            if(!$verification) {
                return $this->errorResponse(null, 'Verification Code is invalid', 403);
            }

            return $this->successResponse([
                'user' => new UserResource($verification['user']),
                'token' => $verification['token']
            ], 'Verification Code has been verified');

        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function completeProfile(CompleteProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->authRepository->completeProfile($request);
            
            if(!$data) {
                return $this->errorResponse(null, 'Unable to complete the profile.');
            }

            DB::commit();
            return $this->successResponse([
                'user' => new UserResource($data['user']),
                'token' => $data['token']
            ], 'Profile has been completed.');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateProfile(SaveProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->authRepository->updateProfile($request);
            
            DB::commit();

            return $this->successResponse(new UserResource($user), 'User profile has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    #========================================================================================#
    //Social Login Functions
    #========================================================================================#

    public function socialLoginRedirectToProvider($provider)
    {
        $provider = $this->authRepository->redirectToProvider($provider);
        return $provider;
    }
    
    public function socialLoginHandleProviderCallback($provider,$token=null)
    {
        DB::beginTransaction();
        try {
           
            $userCreated = $this->authRepository->AddSocialUser($provider,$token);
 
            if(!$userCreated){
                DB::rollBack();
                return $this->errorResponse(null, 'Invalid Token.', 403);
            }
 
            $token = $userCreated->createToken('token');
            DB::commit();
                
            return $this->successResponse([
                'token' => $token->accessToken,
                'user' => new UserResource($userCreated)
            ], 'Credentials has been validated.');
 
        } catch (ClientException $exception) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    #========================================================================================#
    #========================================================================================#
    public function resendVerification(Request $request)
    {
        try {
            $user = $this->authRepository->findUser(['verification_token' => $request->token ]);

            if(!$user) {
                return $this->errorResponse(null, 'Verification Token is invalid', 403);
            }

            if($user->email_verified_at) {
                return $this->errorResponse(null, 'User is verified already.', 403);
            }

            $diff = Carbon::parse($user->verification_date)->floatDiffInMinutes(Carbon::now());
            $expiry = config('app.resend_verification_email_limit');

            if($diff <= $expiry) {
                return $this->errorResponse(
                    [ 'errCode' => 'exceed-limit-resend-verification-email'],
                    'You can resend the verification email after a ' . $expiry . '-minute cooldown period.',
                    403
                );
            }

            $this->authRepository->verificationEmail($user);
            $this->authRepository->updateUser($user, ['verification_date' => Carbon::now()->format('Y-m-d H:i:s')]);
            
            return $this->successResponse(new UserResource($user), 'Verification email has been sent.');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function forgotPassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $reset = $this->authRepository->forgotPassword($request);

            if(!$reset['status']) {
                return $this->errorResponse(['errCode' => $reset['errCode']], $this->errors[$reset['errCode']]);
            }
            
            $this->authRepository->forgotPasswordEmail($reset['data']);

            DB::commit();
            return $this->successResponse($reset['data'], 'Forgot password has been sent.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function validateResetPasswordToken(Request $request, $token)
    {
        try {
            $reset = $this->authRepository->validateResetPasswordToken($token);
            
            if(!$reset['status']) {
                return $this->errorResponse(['errCode' => $reset['errCode']], $this->errors[$reset['errCode']]);
            }

            return $this->successResponse($reset['data'], 'Reset password token has been validated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function verifyResetPasswordCode(Request $request)
    {
        try {
            $reset = $this->authRepository->verifyResetPasswordCode($request);
            
            if(!$reset['status']) {
                return $this->errorResponse(['errCode' => $reset['errCode']], $this->errors[$reset['errCode']]);
            }

            return $this->successResponse($reset['data'], 'Reset password code has been validated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->authRepository->resetPassword($request);
            
            if(!$user['status']) {
                return $this->errorResponse(['errCode' => $user['errCode']], $this->errors[$user['errCode']]);
            }

            DB::commit();
            return $this->successResponse(new UserResource($user['data']), 'User password has been reset.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function listRoles()
    {
        return Role::all()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
            ];
        });
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->authRepository->changePassword($request);
            DB::commit();
            return $this->successResponse(new UserResource($user['data']), 'User password has been changed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
