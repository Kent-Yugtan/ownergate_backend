<?php

namespace Modules\CompanyRequest\Services;

use Modules\CompanyRequest\App\Models\CompanyRequest;

class CompanyRequestService
{
    public function allRequest()
    {
        $perPage = request()->perPage ?? 10;

        return auth()->user()->company->companyRequests()->paginate($perPage);
    }

    public function storeRequest(array $data)
    {
        return auth()->user()->company->companyRequests()->create($data);
    }

    public function getRequestById($id)
    {
        return CompanyRequest::findOrFail($id);
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

    public function deleteRequest($id)
    {
        $request = CompanyRequest::findOrFail($id);
        $request->delete();
        return $request;
    }

    public function searchRequests(array $filters)
    {
        $perPage = request()->perPage ?? 10;

        $query = CompanyRequest::query();

        if (!empty($filters['request_id'])) {
            $query->where('request_number', 'like', '%' . $filters['request_id'] . '%');
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
}
