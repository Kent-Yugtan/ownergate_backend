<?php

namespace Modules\CompanyProperty\App\Http\Controllers;

use App\Traits\ApiHelper;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Company\App\Models\Company;
use Modules\CompanyProperty\Transformers\PropertyResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AccountPropertyController extends Controller
{
    use ApiResponser, ApiHelper;

    public function list(Request $request): AnonymousResourceCollection 
    {
        $owner = auth()->user();

        $perPage = $request->perPage ?? 10;

        $properties = $owner->company->properties()
            ->when($request->keyword, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('og_code', 'like', '%' . $request->keyword . '%')
                ->orWhere('state', 'like', '%' . $request->keyword . '%')
                ->orWhere('city', 'like', '%' . $request->keyword . '%');
            })
            ->paginate($perPage);

        return PropertyResource::collection($properties);
    }
}
