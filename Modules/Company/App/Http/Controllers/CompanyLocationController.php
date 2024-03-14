<?php

namespace Modules\Company\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Company\App\Models\Company;
use Modules\Company\App\Models\CompanyLocation;
use Modules\Company\Repositories\Interfaces\CompanyRepositoryInterface;

class CompanyLocationController extends Controller
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

        return $company->locations()->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $locations = $this->companyRepository->saveLocations($request, $company);

            DB::commit();

            if ($locations instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                return $locations;
            }

            return $this->successresponse($locations, 'Locations has been saved.');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function destroy(Company $company, CompanyLocation $location)
    {
        try {
            if (!auth()->user()->company->is($company)) {
                abort(403, 'Unauthorized action.');
            }

            $location = $this->companyRepository->deleteLocation($location);

            return $this->successresponse($location, 'Location has been Successfully Deleted.');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
