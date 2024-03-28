<?php

namespace Modules\CompanyEmployee\App\Http\Controllers;

use App\Traits\ApiHelper;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Company\App\Models\Company;
use Modules\CompanyEmployee\App\Models\CompanyEmployee;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\CompanyEmployee\App\Models\EmployeeProperty;
use Modules\CompanyProperty\Transformers\PropertyResource;
use Modules\CompanyEmployee\App\resources\EmployeeResource;
use Modules\CompanyEmployee\App\Http\Requests\ChangePasswordRequest;
use Modules\CompanyEmployee\App\resources\EmployeeAttachmentsResource;
use Modules\CompanyEmployee\Repositories\Interfaces\EmployeeRepositoryInterface;

class CompanyEmployeeController extends Controller
{
    use ApiResponser, ApiHelper;
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
    public function show($employee)
    {
        try {
            $employee = $this->employeeRepository->show($employee);
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

    public function saveProperties(Request $request, CompanyEmployee $employee)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'company_id' => 'required',
                'properties' => 'required',
                'properties.*.id' => 'required|exists:company_properties,id,company_id,' . $request->company_id,
                'properties.*.access_code' => 'nullable',
            ]);

            $formatted_data = [];
            $perPage = $request->perPage ?? 10;

            foreach ($validatedData['properties'] as $item) {
                $formatted_data[$item['id']] = ['access_code' => $item['access_code']];
            }

            $employee->properties()->sync($formatted_data);

            $employee->update([
                'company_id' => $request->company_id
            ]);

            $og_code = $this->generateOGCode($employee->user);
            
            $employee->user()->update([
                'og_code' => $og_code
            ]);

            $employee_properties = $employee->properties()->paginate($perPage);

            DB::commit();

            return PropertyResource::collection($employee_properties);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function destroyProperty(Request $request, CompanyEmployee $employee, EmployeeProperty $property)
    {
        try {
            DB::beginTransaction();

            $access = $property->delete();

            DB::commit();
            return $this->successresponse($access, 'Access has been Successfully Deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
