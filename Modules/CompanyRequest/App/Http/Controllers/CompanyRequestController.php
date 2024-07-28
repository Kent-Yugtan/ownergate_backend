<?php

namespace Modules\CompanyRequest\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\CompanyRequest\App\Models\CompanyRequest;
use Modules\CompanyRequest\Services\CompanyRequestService;
use Modules\CompanyRequest\Transformers\CompanyRequestResource;
use Modules\CompanyRequest\App\Http\Requests\CompanyRequestForm;
use App\Exceptions\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;

class CompanyRequestController extends Controller
{
    use ApiResponser;

    public function __construct(CompanyRequestService $companyRequestService)
    {
        $this->companyRequestService = $companyRequestService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function getCompanyRequests()
    {
        $companyRequests = $this->companyRequestService->companyRequests();

        return CompanyRequestResource::Collection($companyRequests);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('companyrequest::show');
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function updateRequest(Request $request, CompanyRequest $companyRequest)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                '_method' => 'required',
                'status' => 'required'
            ]);

            $companyRequest = $this->companyRequestService->updateRequest($companyRequest, $validatedData);

            DB::commit();
            
            return new CompanyRequestResource($companyRequest);
        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

   
    public function delete(CompanyRequest $companyRequest)
    {
        try {
            DB::beginTransaction();

            $this->companyRequestService->deleteRequest($companyRequest);

            DB::commit();
            
            return new CompanyRequestResource($companyRequest);
        } catch (Exception $e) {
            dd('ss');
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
