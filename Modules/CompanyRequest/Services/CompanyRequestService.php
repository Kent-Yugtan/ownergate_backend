<?php

namespace Modules\CompanyRequest\Services;

use Modules\CompanyRequest\App\Models\CompanyRequest;
use Modules\CompanyProperty\Transformers\PropertyResource;

class CompanyRequestService
{
    public function companyRequests()
    {
        if (!auth()->user()->company) {
            abort(403, 'Unauthorized action.');
        }
        $status = request()->status ?? null;
        $perPage = request()->perPage ?? 10;

        if ($status) {
            return auth()->user()->company->companyRequests()->where('status', $status)->paginate($perPage);
        }
        
        return auth()->user()->company->companyRequests()->where('status', '!=', 'Pending')->paginate($perPage);
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

        $query = CompanyRequest::query();

        if (!empty($filters['request_id_code'])) {
            $query->where('request_id_code', 'like', '%' . $filters['request_id_code'] . '%');
        }
        
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $query->whereBetween('request_date', [$filters['date_from'], $filters['date_to']]);
        }

        if (!empty($filters['keyword'])) {
            $query->where('request_id_code', 'like', '%' . $filters['keyword'] . '%')
                ->orWhere('request_name', 'like', '%' . $filters['keyword'] . '%')
                ->orWhere('status', 'like', '%' . $filters['keyword'] . '%')
                ->orWhere('category', 'like', '%' . $filters['keyword'] . '%')
                ->orWhere('note', 'like', '%' . $filters['keyword'] . '%');
        }

        return $query->paginate($perPage);
    }

    public function employeeRequests()
    {
        $perPage = request()->perPage ?? 10;

        return auth()->user()->employeeRequests()->paginate($perPage);
    }

    public function storeRequest(array $data)
    {
        $user = auth()->user();

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
