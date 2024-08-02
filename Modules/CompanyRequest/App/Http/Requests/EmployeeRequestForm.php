<?php

namespace Modules\CompanyRequest\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeRequestForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'request_name' => 'required',
            'request_number' => 'required',
            'commission_percentage' => 'required',
            'commission_notes' => 'required',
            'category' => 'required',
            'request_date' => 'required',
            'request_time_from' => 'required',
            'request_time_to' => 'required',
            'action_date' => 'nullable',
            'note' => 'nullable'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
