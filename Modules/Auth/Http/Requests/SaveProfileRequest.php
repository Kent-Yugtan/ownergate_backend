<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SaveProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|numeric',
            'password' => 'sometimes|required|string|min:8|confirmed',
            'avatar' => [
                'nullable', function ($attribute, $value, $fail) {
                    if(self::hasFile($attribute)){
                        if(!is_array(getimagesize($value))){
                            $fail('The avatar field must be an image.');
                        }                         
                    }
                    
                },
            ],
            //'avatar' => 'nullable|image'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
