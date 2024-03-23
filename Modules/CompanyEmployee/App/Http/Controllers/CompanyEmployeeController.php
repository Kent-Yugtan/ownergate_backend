<?php

namespace Modules\CompanyEmployee\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\CompanyEmployee\App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\CompanyEmployee\App\Models\CompanyEmployee;
use Modules\CompanyEmployee\Repositories\Interfaces\EmployeeRepositoryInterface;
use Modules\CompanyEmployee\App\resources\EmployeeResource;
use Modules\CompanyEmployee\App\resources\EmployeeAttachmentsResource;


class CompanyEmployeeController extends Controller
{
    use ApiResponser;
    private $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function index(Request $request)
    {
        try {
            $employees = $this->employeeRepository->search($request);
            return EmployeeResource::collection($employees);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $employee = $this->employeeRepository->AddNew($request);
            DB::commit();
            return $this->successresponse(new EmployeeResource($employee), 'Employee has been created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $employee = $this->employeeRepository->show($id);
            if (isset($employee["employee_info"])) {
                return $this->successresponse(new EmployeeResource($employee["employee_info"]), 'Employee has been retrieved.');
            } else {
                return $this->successresponse(null, 'Employee not found.');
            }
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $employee = $this->employeeRepository->updateInfo($request, $id);

            DB::commit();
            return $this->successresponse(new EmployeeResource($employee), 'Employee has been updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function changePassword(ChangePasswordRequest $request, CompanyEmployee $employee)
    {
        DB::beginTransaction();

        try {
            $changePassword = $this->employeeRepository->changePassword($request, $employee);

            DB::commit();
            return $this->successresponse($changePassword, 'employee password has set successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function addAccess(request $request, CompanyEmployee $employee, CompanyProperty $property)
    {
        DB::beginTransaction();

        try {
            $addAccess = $this->employeeRepository->addAccess($request, $employee, $property);

            if ($addAccess === false) {
                return $this->errorResponse(null, "You don't have permission to this property");
            }
            DB::commit();
            return $this->successresponse($addAccess, 'employee access has set successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function removeAccess(request $request, CompanyEmployee $employee, CompanyProperty $property)
    {
        DB::beginTransaction();

        try {
            $addAccess = $this->employeeRepository->removeAccess($request, $employee, $property);

            if ($addAccess === false) {
                return $this->errorResponse(null, "You don't have permission to this property");
            }
            DB::commit();
            return $this->successresponse($addAccess, 'employee access has unset successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function searchAccess(request $request, CompanyEmployee $employee)
    {

        try {

            $properties = $this->employeeRepository->searchAccess($request, $employee);
            return $this->successresponse($properties);
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
