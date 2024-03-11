<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Traits\ApiResponser;
use Modules\Auth\Transformers\UserResource;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use ApiResponser;

    private $userRepository;
    
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->updateProfile($request);

            if($user['errCode'] == 'incorrect-old-password'){
                return $this->errorResponse(null, 'Incorrect old password', 401);
            }
            
            DB::commit();
            return $this->successResponse(new UserResource($user), 'Profile has been saved.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
