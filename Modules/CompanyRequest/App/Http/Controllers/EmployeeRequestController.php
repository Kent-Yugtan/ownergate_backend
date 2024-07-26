<?php

namespace Modules\CompanyRequest\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\CompanyRequest\Services\CompanyRequestService;
use Modules\CompanyRequest\Transformers\CompanyRequestResource;

class EmployeeRequestController extends Controller
{
    use ApiResponser;

    public function __construct(CompanyRequestService $companyRequestService)
    {
        $this->companyRequestService = $companyRequestService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyRequests = $this->companyRequestService->allRequest();

        return CompanyRequestResource::Collection($companyRequests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $companyRequest = $this->companyRequestService->storeRequest($request->all());

            DB::commit();

            return $this->successresponse(new CompanyRequestResource($companyRequest), 'Request has been added.');
        } catch (Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $filters = $request->all();

            $companyRequests = $this->companyRequestService->searchRequests($filters);

            return CompanyRequestResource::Collection($companyRequests);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
