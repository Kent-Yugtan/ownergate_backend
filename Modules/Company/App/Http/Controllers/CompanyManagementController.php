<?php

namespace Modules\Company\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Company\App\Models\Company;
use Modules\Company\App\Models\CompanyManagement;
use Modules\Company\Transformers\ManagementResource;
use Modules\Company\Repositories\Interfaces\CompanyRepositoryInterface;

class CompanyManagementController extends Controller
{
    use ApiResponser;

    private $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index(Request $request, Company $company)
    {
        $perPage = $request->perPage ?? 10;

        $managements = $company->managements()->paginate($perPage);

        return ManagementResource::collection($managements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $management = $this->companyRepository->saveManagement($request, $company);
            
            DB::commit();

            if ($management instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                return ManagementResource::collection($management);
            }

            return $this->successresponse(new ManagementResource($management), 'Management has been saved.');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function destroy(Company $company, CompanyManagement $management)
    {
        try {
            if (!auth()->user()->company->is($company)) {
                abort(403, 'Unauthorized action.');
            }
            
            $management = $this->companyRepository->deleteManagement($management);

            return $this->successresponse($management, 'Management has been Successfully Deleted.');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
