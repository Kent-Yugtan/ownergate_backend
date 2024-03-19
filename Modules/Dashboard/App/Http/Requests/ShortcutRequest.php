<?php

namespace Modules\Dashboard\App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShortcutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        //$this->rules = config('ValidationRule');
        $this->rules = [
            'link' => [
                'required', 'url', function ($attribute, $value, $fail) {
                    $urlParse = parse_url($value);

                    if(is_numeric(strpos($urlParse['host'], 'www.'))){
                        
                        if(!($urlParse['host'] == 'www.ownergate.com' || $urlParse['host'] == 'www.salinadev.com' || $urlParse['host'] == 'www.ui.salinadev.com' || $urlParse['host'] == 'www.localhost' || $urlParse['host'] == 'www.127.0.0.1')){
                            $fail('The host of link must be (ownergate.com)');
                        }

                    }else{
                        if(!($urlParse['host'] == 'ownergate.com' || $urlParse['host'] == 'salinadev.com' || $urlParse['host'] == 'https://ui.salinadev.com' || $urlParse['host'] == 'localhost' || $urlParse['host'] == '127.0.0.1')){
                            $fail('The host of link must be (ownergate.com)');
                        }

                    }
                    
                },
            ],
            'name' => 'required',
        ];

        return $this->rules;
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