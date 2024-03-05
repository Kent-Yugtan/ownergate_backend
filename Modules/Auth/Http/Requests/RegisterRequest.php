<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $fields = [
            'user_type' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users|confirmed',
            'name' => 'nullable',
            'phone' => 'required',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required|same:password',
            'email_confirmation' => 'required|same:email'
        ];

        if($this->user_type == 'Admin') {
            $fields = array_merge($fields, [
                'company_name' => 'required',
                'company_email' => 'required|email',
                'company_phone' => 'nullable',
                'company_type_id' => 'required',
                'company_website' => 'nullable',
            ]);
        }

        return $fields;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
