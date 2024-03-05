<?php

namespace Modules\Auth\Repositories;

use Modules\BaseRepository;
use Modules\Auth\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\Auth\Entities\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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

        if ($request->has('avatar')) {
            $this->uploadPhoto($user, $request->avatar, 'avatar');
        }
        if ($request->has('cover_photo')) {
            $this->uploadPhoto($user, $request->cover_photo, 'cover_photo');
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
        }

        $user->update([$key => $path]);
    }
}