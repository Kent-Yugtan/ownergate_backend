<?php

namespace Modules\Admin\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\CompanyProperty\App\resources\PropertyResource;

class AdminController extends Controller
{
    public function getAllProperties(Request $request)
    {
        $perPage = $request->perPage ?? 10;
        
        $all_propertis = CompanyProperty::when($request->date_from && $request->date_to, function ($query) use ($request) {
            return $query->whereDate('created_at', '>=', $request->date_from)
                     ->whereDate('created_at', '<=', $request->date_to);
        })
        ->when($request->keywords, function ($query) use ($request) {
            return  $query->where('name', 'like', '%' . $request->keywords . '%')
                    ->orWhere('status', 'like', '%' . $request->keywords . '%');
        })
        ->paginate($perPage);

        return PropertyResource::collection($all_propertis);
    }
}
