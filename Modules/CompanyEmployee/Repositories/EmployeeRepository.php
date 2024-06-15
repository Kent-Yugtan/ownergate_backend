<?php

namespace Modules\CompanyEmployee\Repositories;

use Illuminate\Http\File;
use Illuminate\Support\Str;
use Modules\BaseRepository;
use App\Traits\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\CompanyEmployee\App\Models\CompanyEmployee;
use Modules\CompanyEmployee\App\Models\EmployeeAttachment;
use App\Models\User;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\CompanyEmployee\Repositories\Interfaces\EmployeeRepositoryInterface;
use Modules\CompanyEmployee\App\Http\Requests\ChangePasswordRequest;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    use ApiHelper;
    public function __construct(CompanyEmployee $model)
    {
        $this->model = $model;
    }

    public function AddNew(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users'
        ]);
        $createUser = User::create(array_merge(
            $validatedData,
            [
                'email_verified_at' => now(),
                'password' => $request->first_name . $request->last_name
            ]
        ));

        $profile = $createUser->profile()->updateOrCreate([
            'user_id' => $createUser->id,
        ], $request->all());

        $createUser->assignRole('employee');

        $employee = $createUser->employeeAccount()->updateOrCreate([
            'user_id' => $createUser->id,
        ], array_merge($request->all(), [
            'admin_id' => auth()->user()->id,
            'profile_id' => $profile->id,
            'company_id' => $request->company_id
        ]));

        $og_code = $this->generateOGCode($createUser);
        $createUser->og_code = $og_code;
        $createUser->save();

        if (isset($request->attachments)) {
            foreach ($request->attachments as $key => $val) {
                $attachment =  $employee->attachments()->create($val);

                if (isset($val['file']) && is_file($val['file'])) {
                    $path = $val['file']->store('employee/' . $employee->id);
                    $attachment->update(['path' => $path]);
                }
            }
        }

        return $employee;
    }

    public function updateInfo(Request $request, $id)
    {
        $employee = $this->model::find($id);

        if (isset($request->attachments)) {
            $this->updateAttachments($employee, $request->attachments);
        }

        // Get the fillable fields of the profile model
        $fillableFields = $employee->profile->getFillable();

        // Filter the request data to include only the fillable fields
        $profileData = $request->only($fillableFields);

        // Update profile data
        $employee->profile->fill($profileData);
        $employee->profile->save();

        // Validate user email
        $request->validate([
            'email' => 'required|unique:users,email,'.$employee->user->id
        ]);

        $fillableFieldsUser = $employee->user->getFillable();
        $userData = $request->only($fillableFieldsUser);

        // Update user data
        $employee->user->fill($userData);
        $employee->user->save();

        // Update employee data
        $employee->fill($request->all())->save();

        return $employee;
    }

    private function updateAttachments($model, $attachments)
    {
        foreach ($attachments as $key => $val) {
            if (isset($val['id'])) {
                $attachment = $model->attachments()->find($val['id']);
                if (!$attachment) {
                    continue; // Skip if attachment not found
                }

                // Check if title is set and update it
                if (isset($val['title'])) {
                    $attachment->update(['title' => $val['title']]);
                } else {
                    $attachment->update(['title' => '']);
                }

                // Check if file is set and update it
                if (isset($val['file']) && is_file($val['file'])) {
                    $path = $val['file']->store('employee/' . $model->id);
                    Storage::delete($attachment->path);
                    $attachment->update(['path' => $path]);
                }

                // Check if both title and file are empty, then delete the attachment
                if (empty($val['title']) && empty($val['file'])) {
                    $attachment->delete();
                }
            } else {
                $attachment =  $model->attachments()->create($val);
                if (isset($val['file']) && is_file($val['file'])) {
                    $path = $val['file']->store('employee/' . $model->id);
                    $attachment->update(['path' => $path]);
                }
            }
        }
    }


    public function search(Request $request)
    {
        $user = auth()->user();
        $per_page = $request->perPage ?? 10;
        $employees = $user->adminEmployees()
            ->when($request, function ($q) use ($request) {
                if (!empty($request->id)) {
                    $q->where('id', $request->id);
                }

                if (!empty($request->keyword)) {
                    $q->where(function ($subquery) use ($request) {
                        $subquery->orWhere('type', 'LIKE', '%' . $request->keyword . '%')
                            ->orWhere('status', 'LIKE', '%' . $request->keyword . '%');
                    });

                    // Search on profile model
                    $q->orWhereHas('profile', function ($subquery) use ($request) {
                        $subquery->where('first_name', 'LIKE', '%' . $request->keyword . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $request->keyword . '%');
                    });
                }

                if (!empty($request->owner_id)) {
                    // Search on Company Model
                    $q->whereHas('company', function ($companyQuery) use ($request) {
                        $companyQuery->where('owner_id', $request->owner_id);
                    });
                }

                if (!empty($request['from']) || !empty($request['to'])) {
                    if (!empty($request['from']) && !empty($request['to'])) {
                        $q->where(function ($subquery) use ($request) {
                            $subquery->where('company_contract->from', '>=', $request['from'])
                                ->where('company_contract->to', '<=', $request['to']);
                        });
                    }

                    if (!empty($request['from']) && empty($request['to'])) {
                        $q->where(function ($subquery) use ($request) {
                            $subquery->where('company_contract->from', '>=', $request['from']);
                        });
                    }

                    if (empty($request['from']) && !empty($request['to'])) {
                        $q->where(function ($subquery) use ($request) {
                            $subquery->where('company_contract->to', '<=', $params['to']);
                        });
                    }
                }
            })->paginate($per_page);

        return $employees;
    }

    public function show($id)
    {
        $data["employee_info"] = CompanyEmployee::find($id);
        return $data;
    }

    public function changePassword(ChangePasswordRequest $request, CompanyEmployee $employee)
    {
        $result = null;
        $permission_period = isset($request->permission_period) ? $request->permission_period : null;
        $official_contract = isset($request->official_contract) ? $request->official_contract : null;
        
        if (isset($request->password) && $request->password) {
            $result = $employee->user->update(['password' => $request->password]);
        }

        $employee->update([
            'permission_type' => $request->permission_type,
            'permission_period' => $permission_period,
            'official_contract' => $official_contract
        ]);

        return $result;
    }

    public function addAccess(Request $request, CompanyEmployee $employee, CompanyProperty $property)
    {
        $user = $request->user();
        $pid = $user->company->getPropertiesIdsAttribute()->toArray();

        if (!in_array($property->id, $pid)) {
            return false;
        }

        $result = $employee->properties()->attach($property->id, ['access_code' => $request->access_code]);
        return $result;
    }

    public function removeAccess(Request $request, CompanyEmployee $employee, CompanyProperty $property)
    {
        $user = $request->user();
        $pid = $user->company->getPropertiesIdsAttribute()->toArray();

        if (!in_array($property->id, $pid)) {
            return false;
        }

        $result = $employee->properties()->detach($property->id);
        return $result;
    }

    public function searchAccess(Request $request, CompanyEmployee $employee)
    {
        $per_page = $request->perPage ?? 10;
        return $employee->properties()->when($request, function ($q) use ($request) {
            if (!empty($request->keyword)) {
                $q->where(function ($subquery) use ($request) {
                    $subquery->where('name', 'LIKE', '%' . $request['keyword'] . '%')
                        ->orWhere('description', 'LIKE', '%' . $request['keyword'] . '%')
                        ->orWhere('access_code', 'LIKE', '%' . $request['keyword'] . '%');
                });
            }
        })->paginate($per_page);
    }
}
