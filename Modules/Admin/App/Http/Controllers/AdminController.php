<?php

namespace Modules\Admin\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Company\App\Models\Company;
use Modules\CompanyPrivacy\App\Models\Section;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\CompanyProperty\Transformers\PropertyResource;

class AdminController extends Controller
{
    public function getAllProperties(Request $request)
    {
        $perPage = $request->perPage ?? 10;

        $all_propertis = CompanyProperty::when($request->owner_id, function ($query) use ($request) {
            return $query->whereHas('company', function ($query) use ($request) {
                $query->where('id', $request->owner_id);
            });
        })
            ->when($request->keywords, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->keywords . '%')
                    ->orWhereHas('propertyType', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->keywords . '%');
                    })
                    ->orWhereHas('targetType', function ($query) use ($request) {
                        $query->where('name', $request->keywords);
                    });
            })
            ->paginate($perPage);

        return PropertyResource::collection($all_propertis);
    }

    public function getAllCompanies(Request $request)
    {
        $perPage = $request->perPage ?? 10;
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;

        $companies = Company::all()->map(function ($company) {
            return [
                'id' => $company->id,
                'company_name' => $company->company_name,
                'company_email' => $company->owner->email,
                'properties' => PropertyResource::collection($company->properties)
            ];
        });

        $paginated_results = $companies->slice($offset, $perPage)->values();

        return new LengthAwarePaginator(
            $paginated_results,
            $companies->count(),
            $perPage,
            $page,
        );
    }

    public function getSections(Request $request)
    {
        $perPage = $request->perPage ?? 10;
        $module_name = $request->module_name ?? null;
        
        return Section::when($module_name, function ($query) use ($module_name) {
            return $query->where('module_name', $module_name);
        })
        ->paginate($perPage);
    }
    
}
