<?php

namespace Modules\Auth\Repositories;

use Socialite;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\ApiHelper;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use Modules\BaseRepository;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Illuminate\Support\Facades\Storage;
use Modules\Auth\Emails\VerifictionEmail;
use Modules\Auth\Emails\ForgotPasswordEmail;
use Modules\Auth\App\Models\PasswordResetToken;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\CompanyEmployee\App\Models\CompanyEmployee;
use Modules\Auth\Repositories\Interfaces\AuthRepositoryInterface;
 
class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    use ApiHelper;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function login(Request $request)
    {
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'og_code';
        $account = $request->email;

        if($field == 'og_code'){
            $account = $this->formatOGCode($request->email);
        }

        $credentials = [$field => $account, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            if (!$this->validateEmployee($request)) {
                return false;
            }

            $token = Auth::user()->createToken('Auth Token')->accessToken;
            return $token;
        }

        return false;
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
    }

    public function getAuthUser(Request $request)
    {
        return $request->user();
    }

    public function register($request)
    {
        $user = $this->model->create(array_merge($request->all(), [
            'verification_token' => Str::uuid(),
            'verification_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'verification_code' => $this->generateRandomNumbers(6)
        ]));

        $user->profile()->create($request->only('first_name', 'last_name', 'phone'));
        $user->assignRole($request->role);

        if($request->role != 'Customer'){
            $company = $user->company()->create([
                'company_name' => $request->company_name,
                'phone' => $request->company_phone,
                'website' => $request->company_website,
                'status' => 'inactive'
            ]);

            $company->users()->attach($user->id);
        }

        $code = $this->generateOGCode($user);
        $user->update(['og_code' => $code]);

        return $user;
    }

    public function verificationEmail($user)
    {
        $to = $user->email;
        $token = $user->verification_token;
        $code = $user->verification_code;

        $mail = Mail::to($to)->send((new VerifictionEmail(['name' => $user->first_name, 'token' => $token, 'code' => $code])));
        return $mail;
    }

    public function validateVerificationToken($token)
    {
        $verification = $this->model->where('verification_token', $token)->first();
        
        if(is_null($verification) || $verification->email_verified_at) {
            return false;
        }

        return $verification;
    }

    public function validateVerificationCode($token, $code)
    {
        $verification = $this->model->where(['verification_token' => $token, 'verification_code' => $code])->first();

        if(is_null($verification) || $verification->email_verified_at) {
            return false;
        }

        // VERIFY USER AND GENERATE TOKEN
        $token = $verification->createToken('Auth Token')->accessToken;
        $verification->markEmailAsVerified();

        return ['user' => $verification, 'token' => $token];
    }

    private function uploadImage($type, $file, $company)
    {
        $dir = 'company/' . $company->id . '/' . $type;
        $name = $file->hashName();
        $path = $file->storeAs($dir, $name);

        if($type == 'avatar') {
            $company->avatar = $path;
        }

        if($type == 'cover_photo') {
            $company->cover_photo = $path;
        }

        $company->save();
    }

    public function completeProfile($request)
    {
        $user = $this->model->find($request->id);
        
        if(!is_null($user) && is_null($user->email_verified_at)) {
            $isTokenValid = $user->verification_token == $request->verification_token;

            if(!$isTokenValid) {
                return false;
            }

            // SAVE COMPANY INFO
            $company = $user->company()->create($request->except([
                'id',
                'verification_token',
                'user_phone',
                'avatar',
                'cover_photo'
            ]));

            // SYNC USER ID FOR COMPANY
            $company->companyUsers()->attach($user->id);

            // VERIFY USER AND GENERATE TOKEN
            $token = $user->createToken('Auth Token')->accessToken;
            $user->markEmailAsVerified();

            // UPLOAD AVATAR
            if($request->hasFile('avatar')) {
                $this->uploadImage('avatar', $request->file('avatar'), $company);
            }

            // UPLOAD COVER PHOTO
            if($request->hasFile('cover_photo')) {
                $this->uploadImage('cover_photo', $request->file('cover_photo'), $company);
            }

            return ['user' => $user, 'token' => $token];
        }

        return false;
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $user->update($request->all());

        // UPLOAD AVATAR
        if($request->hasFile('avatar')) {
            
            $file = $request->file('avatar');
            $name = $file->hashName();

            $path = $file->storeAs('user/avatars', $name);

            $user->avatar = $path;
            $user->save();
        }

        return $user;
    }

    #========================================================================================#
    //Social Login Functions
    #========================================================================================#

    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function AddSocialUser($provider, $token=null)
    {
        if(isset($token)) {
            $socialUser = Socialite::driver($provider);
                
            if($provider == 'facebook') {
                $socialUser = $socialUser->fields(['name', 'first_name', 'last_name', 'email']);
            }
            
            $socialUser = $socialUser->stateless()->userFromToken($token);
 
            $names = [
                'google' => [
                    'first_name' => 'given_name',
                    'last_name' => 'family_name'
                ],
                'facebook' => [
                    'first_name' => 'first_name',
                    'last_name' => 'last_name'
                ]
            ];
            
            $user = User::where('email', $socialUser['email']);
 
            if ($user->exists()) {
                $existuser = $user->first();
                return $existuser;
            } else {
                $userCreated = $this->model->create(
                    [
                        'email' => $socialUser['email'],
                        'name' => $socialUser['name'],
                        'first_name' => $socialUser->user[$names[$provider]['first_name']],
                        'last_name' => $socialUser->user[$names[$provider]['last_name']],
                        'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'verification_token' => Str::uuid(),
                        'verification_date' => Carbon::now()->format('Y-m-d H:i:s'),
                        'password' => Str::uuid()
                    ]
                );
 
                return $userCreated;
            }
        }
        
        return false;
    }
    //check provider
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return response()->json(['error' => 'Please login using facebook, google'], 422);
        }
    }
    
    public function findUser($params)
    {
        $user = User::where($params)->first();
        
        if(!is_null($user)) {
            return $user;
        }

        return false;
    }

    public function updateUser($user, $params)
    {
        $user->update($params);
        return $user;
    }

    public function forgotPassword(Request $request)
    {
        $user = User::where('email', $request->email);

        if($user->doesntExist()) {
            return ['errCode' => 'user-not-found', 'status' => false ];
        }

        $reset = PasswordResetToken::updateOrCreate([
            'email' => $request->email,
        ], [
            'token' => Str::uuid(),
            'code' => $this->generateRandomNumbers()
        ]);

        return ['data' => $reset, 'status' => true];
    }

    public function forgotPasswordEmail($reset)
    {

        $user = User::where('email', $reset->email)->first();

        $to = $reset->email;
        $token = $reset->token;
        $code = $reset->code;


        $mail = Mail::to($to)->send((new ForgotPasswordEmail(['code' => $code, 'token' => $token, 'name' => $user->first_name])));
        return $mail;
    }

    private function generateRandomNumbers($length = 4)
    {
        $randomNumbers = [];

        for ($i = 0; $i < $length; $i++) {
            $randomNumbers[] = rand(0, 9);
        }

        return implode('', $randomNumbers);
    }

    private function findResetPassword($params)
    {
        return PasswordResetToken::where($params);
    }

    private function isResetPasswordExpired($reset)
    {
        $reset = $reset->first();
        $diff = Carbon::parse($reset->updated_at)->floatDiffInMinutes(Carbon::now());
        $expiry = config('app.password_reset_expiry');

        if($diff >= $expiry) {
            return true;
        }

        return false;
    }

    public function validateResetPasswordToken($token)
    {
        $reset = $this->findResetPassword(['token' => $token]);

        if($reset->doesntExist()) {
            return ['errCode' => 'reset-password-token-err', 'status' => false];
        }

        if($this->isResetPasswordExpired($reset)) {
            return ['errCode' => 'reset-password-expired', 'status' => false];
        }

        return ['data' => $reset->first(), 'status' => true];
    }

    public function verifyResetPasswordCode(Request $request)
    {
        $reset = $this->findResetPassword(['token' => $request->token, 'code' => $request->code ]);

        if($reset->doesntExist()) {
            return ['errCode' => 'reset-password-verification-err', 'status' => false];
        }

        if($this->isResetPasswordExpired($reset)) {
            return ['errCode' => 'reset-password-expired', 'status' => false];
        }

        return ['data' => $reset->first(), 'status' => true];
    }

    public function resetPassword(Request $request)
    {
        $reset = $this->findResetPassword(['token' => $request->token, 'code' => $request->code ]);

        if($reset->doesntExist()) {
            return ['errCode' => 'reset-password-verification-err', 'status' => false];
        }

        $reset = $reset->first();
        $user = $this->findUser(['email' => $reset->email]);

        if(!$user) {
            return ['errCode' => 'user-not-found', 'status' => false];
        }

        $user->update([
            'password' => $request->password
        ]);

        return ['data' => $user, 'status' => true];
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $user->update([
            'password' => $request->password
        ]);

        return ['data' => $user, 'status' => true];
    }

    private function validateEmployee($request)
    {
        if (Auth::user()->hasRole('Employee') && !$request->filled('company_addmail')) {
            return false;
        }

        if ($request->filled('company_addmail')) {
            $user = Auth::user();

            $employee = CompanyEmployee::whereHas('company.owner', function($q) use ($request) {
                $q->where('og_code', $request->company_addmail);
            })
            ->where('user_id', $user->id)
            ->first();

            if (!$employee) {
                return false;
            }
        }

        return true;
    }
}
