<?php

namespace Modules\CompanyEmployee\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use DateTime;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "password" =>"required|min:8|same:password_confirmation",
            'permission_period' => [
                'required_without:official_contract', function ($attribute, $value, $fail) {
                    if ($value['from'] >= $value['to']) {
                        $fail('Start date must not equal or greater than end date');
                    }

                    if (DateTime::createFromFormat('Y-m-d H:i:s', $value['from']) === false) {
                        $fail('The start date of permission period is not date.');
                    }

                    if (DateTime::createFromFormat('Y-m-d H:i:s', $value['to']) === false) {
                        $fail('The end date of permission period is not date.');
                    }
                },
            ],
            'official_contract' => [
                'required_without:permission_period', function ($attribute, $value, $fail) {
                    if ($value['from'] >= $value['to']) {
                        $fail('Start date must not equal or greater than end date');
                    }
                },
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
