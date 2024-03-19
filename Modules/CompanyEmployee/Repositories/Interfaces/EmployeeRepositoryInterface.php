<?php

namespace Modules\CompanyEmployee\Repositories\Interfaces;

use Illuminate\Http\Request;
use Modules\CompanyEmployee\App\Models\CompanyEmployee;
use Modules\CompanyEmployee\App\Http\Requests\ChangePasswordRequest;
use Modules\CompanyProperty\App\Models\CompanyProperty;

interface EmployeeRepositoryInterface
{
    public function AddNew(Request $request);
    public function updateInfo(Request $request, $id);
    public function updateAttachments(Request $request, CompanyEmployee $employee);
    public function search(Request $request);
    public function changePassword(ChangePasswordRequest $request, CompanyEmployee $employee);
    public function show($id);
    public function addAccess(Request $request, CompanyEmployee $employee, CompanyProperty $property);
    public function removeAccess(Request $request, CompanyEmployee $employee, CompanyProperty $property);
    public function searchAccess(Request $request, CompanyEmployee $employee);

}
