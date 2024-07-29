<?php

namespace Modules\CompanyRequest\Services;

use Modules\CompanyRequest\App\Models\CompanyRequest;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\CompanyProperty\Transformers\PropertyResource;

class CompanyRequestService
{
    public function companyRequests()
    {
        if (!auth()->user()->company) {
            abort(403, 'Unauthorized action.');
        }

        $perPage = request()->perPage ?? 10;
        
        return auth()->user()->company->companyRequests()->where('status', '!=', 'Pending')->paginate($perPage);
    }

    public function companyPendingRequests()
    {
        $user = auth()->user();
        
        if (!$user->company || !$user->hasRole('Owner')) {
            abort(403, 'Unauthorized action.');
        }

        $perPage = request()->perPage ?? 10;

        return $user->company->companyRequests()->where('status', 'Pending')->paginate($perPage);
    }

    public function previewProperty()
    {
        $og_code = request()->og_code ?? null;

        return auth()->user()->employeeAccount->company->properties()->where('og_code', $og_code)->first();
    }

    public function updateRequest($companyRequest, array $data)
    {
        $user = auth()->user();

        if (!$user->company->companyRequests->contains($companyRequest)) {
            // The company owns the request
            abort(403, 'Unauthorized action.');
        }
   
        $companyRequest->update(['status' => $data['status']]);

        return $companyRequest;
    }

    public function deleteRequest($companyRequest)
    {
        $user = auth()->user();

        $companyRequest = $companyRequest->where('status', 'Pending')->first();

        if (!$user->company->companyRequests->contains($companyRequest) || !$companyRequest) {
            // The company owns the request
            abort(403, 'Unauthorized action.');
        }

        $companyRequest->delete();
    }

    public function searchRequests(array $filters)
    {
        $perPage = request()->perPage ?? 10;
        $user =auth()->user();
        
        $companyRequestQuery  = null;

        if ($user->hasRole('Employee')) {
            $companyRequestQuery  = $user->employeeAccount->company->companyRequests();
        } elseif ($user->hasRole('Owner')) {
            $companyRequestQuery  = $user->company->companyRequests();
        }

        if (!empty($filters['request_id_code'])) {
            $companyRequestQuery->where('request_id_code', 'like', '%' . $filters['request_id_code'] . '%');
        }
        
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $companyRequestQuery->whereBetween('request_date', [$filters['date_from'], $filters['date_to']]);
        }

        if (!empty($filters['keyword'])) {
            $companyRequestQuery->where('request_id_code', 'like', '%' . $filters['keyword'] . '%')
                ->orWhere('request_name', 'like', '%' . $filters['keyword'] . '%')
                ->orWhere('status', 'like', '%' . $filters['keyword'] . '%')
                ->orWhere('category', 'like', '%' . $filters['keyword'] . '%')
                ->orWhere('note', 'like', '%' . $filters['keyword'] . '%');
        }

        return $companyRequestQuery->paginate($perPage);
    }

    public function employeeRequests()
    {
        $perPage = request()->perPage ?? 10;

        return auth()->user()->employeeRequests()->paginate($perPage);
    }

    public function storeRequest(array $data)
    {
        $user = auth()->user();
        $property = CompanyProperty::findOrFail($data['property_id']);
        $company = $user->employeeAccount->company;

        if (!auth()->user()->hasRole('Employee')) {
            abort(403, 'Unauthorized action.');
        }

        if (!$company->properties->contains($property)) {
            abort(403, 'Unauthorized action.');
        }

        $data['request_id_code'] = $this->generateUniqueCode('OGRE');
        $data['company_id'] = $user->employeeAccount->company_id;
        $data['status'] = 'Pending';

        return $user->employeeRequests()->create($data);
    }

    public function generateUniqueCode($prefix, $length = 5)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    
        return $prefix . ' ' . $randomString;
    }
}
