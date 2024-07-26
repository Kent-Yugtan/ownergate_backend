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
    public function index()
    {
        $companyRequests = $this->companyRequestService->allRequest();

        return CompanyRequestResource::Collection($companyRequests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequestForm $request)
    {
        try {
            DB::beginTransaction();
            
            $companyRequest = $this->companyRequestService->storeRequest($request->all());

            DB::commit();

            return $this->successresponse(new CompanyRequestResource($companyRequest), 'Request has been added.');
        } catch (QueryException $exception) {
            // Check for unique constraint violation (SQLSTATE 23000, Error Code 1062)
            if ($exception->errorInfo[1] == 1062) {
                throw new UniqueConstraintViolationException('Duplicate entry detected for the given keys.');
            }

            // Handle other query exceptions
            throw new UniqueConstraintViolationException('Database error.', 500);
        }
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
    public function update(CompanyRequestForm $dataRequest, CompanyRequest $request)
    {
        try {
            DB::beginTransaction();

            $companyRequest = $this->companyRequestService->updateRequest($request, $dataRequest->all());

            DB::commit();
            
            return new CompanyRequestResource($companyRequest);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
