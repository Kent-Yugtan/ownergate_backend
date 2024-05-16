<?php

namespace Modules\Auth\Repositories;

use Modules\BaseRepository;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\Auth\Entities\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $user->profile()->updateOrCreate([
            'user_id' => $user->id
        ], $request->all());

        if ($request->has('avatar')) 
        {
            $this->uploadPhoto($user, $request->avatar, 'avatar');
        }

        if($request->missing('avatar'))
        {
            $user->profile->update(['avatar'=> null]);
        }
        
        if($request->filled('old_password') && $request->filled('password') && $request->filled('password_confirmation'))
        {
            if (!Hash::check($request->old_password, $user->password)) {
                return ['errCode' => 'incorrect-old-password', 'status' => false ]; 
            }

            $user->password = $request->password;
            $user->save();  
        }

        return $user;
    }

    private function uploadPhoto($user, $file, $key)
    {
        $path = $file;

        if(is_file($file)) {
            if ($user->{$key}) {
                Storage::delete($user->{$key});
            }

            $path = $file->store('user/' . $user->id);
            $user->profile->update([$key => $path]);
        }
    }
}