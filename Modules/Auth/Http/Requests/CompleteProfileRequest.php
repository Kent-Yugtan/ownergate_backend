<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CompleteProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required',
            'company_name' => 'required',
            'email' => 'required|email|unique:companies,email,'.$this->id.',owner_id',
            'username' => 'nullable|unique:companies,username,'.$this->id.',owner_id',
            'phone' => 'required|numeric',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'avatar' => 'nullable|image',
            'cover_photo' => 'nullable|image',
            'website' => 'nullable'
        ];
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

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
