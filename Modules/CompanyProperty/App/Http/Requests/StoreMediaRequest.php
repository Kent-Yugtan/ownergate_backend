<?php

namespace Modules\CompanyProperty\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMediaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'property_id' => 'nullable',
            'media_id' => 'nullable',
            'name' => 'nullable',
            'description' => 'nullable',
            'area' => 'nullable',
            'type' => 'required',
            'media.*.media_path_id'  => 'nullable',
        ];

        if ($this->type === 'Full Video') {
            $rules['media.*.file'] = 'required|file|mimes:mp4,avi,mov,wmv';
        } else {
            $rules['media.*.file'] = 'required|file|mimes:png,jpeg,jpg';
        }

        return $rules;

        return [
            'property_id' => 'nullable',
            'type' => 'required',
            'media' => 'required',
            'media.*.media_path_id'  => 'nullable',
            'media.*.file'  => 'required|file|mimes:png,jpeg,jpg'
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
