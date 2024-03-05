<?php

namespace Modules\Auth\Repositories\Interfaces;

use Illuminate\Http\Request;

interface UserRepositoryInterface 
{
    public function updateProfile(Request $request);
}