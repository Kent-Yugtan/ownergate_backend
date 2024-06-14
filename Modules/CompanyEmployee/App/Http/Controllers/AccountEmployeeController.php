<?php

namespace Modules\CompanyEmployee\App\Http\Controllers;

use App\Traits\ApiHelper;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\CompanyEmployee\App\resources\EmployeeResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AccountEmployeeController extends Controller
{
    use ApiResponser, ApiHelper;

    public function list(Request $request): AnonymousResourceCollection
    {
        $owner = auth()->user();

        $perPage = $request->perPage ?? 10;

        $employees = $owner->adminEmployees()
            ->when($request->keyword, function ($q) use ($request) {
                $q->where('status', 'like', '%' . $request->keyword . '%')
                ->orWhere('type', 'like', '%' . $request->keyword . '%');

                $q->orWhereHas('user.profile', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('last_name', 'like', '%' . $request->keyword . '%');
                });
            })
            ->paginate($perPage);
        
        return EmployeeResource::collection($employees);
    }
}
