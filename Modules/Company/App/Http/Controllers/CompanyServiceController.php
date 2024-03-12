<?php

namespace Modules\Company\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Company\App\Models\Company;
use Modules\Company\App\Models\CompanyService;
use Modules\Company\Transformers\ServicesResource;
use Modules\Company\Repositories\Interfaces\CompanyRepositoryInterface;

class CompanyServiceController extends Controller
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

        $services = $company->services()->paginate($perPage);

        return ServicesResource::collection($services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $service = $this->companyRepository->saveServices($request, $company);

            DB::commit();

            if ($service instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                return ServicesResource::collection($service);
            }

            return $this->successresponse(new ServicesResource($service), 'Service has been saved.');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function destroy(Company $company, CompanyService $service)
    {
        try {
            if (!auth()->user()->company->is($company)) {
                abort(403, 'Unauthorized action.');
            }

            $service = $this->companyRepository->deleteService($service);

            return $this->successresponse($service, 'Service has been Successfully Deleted.');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
