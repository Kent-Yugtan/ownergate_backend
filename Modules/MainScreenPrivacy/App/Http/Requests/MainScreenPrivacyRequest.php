<?php

namespace Modules\MainScreenPrivacy\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\MainScreen\App\Models\MainScreen;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MainScreenPrivacyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function save(MainScreen $main_screen)
    {
        $enabled_sections = array_filter($this->sections, function ($item) {
            return $item['enable'] === true;
        });

        $disabled_sections = array_filter($this->sections, function ($item) {
            return $item['enable'] === false;
        });

        $enabled_section_ids = $this->mapSectionIds($enabled_sections);
        $disable_section_ids = $this->mapSectionIds($disabled_sections);

        
        if (count($enabled_section_ids)) {
            $main_screen->privacies()->syncWithoutDetaching($enabled_section_ids);
        }

        if (count($disable_section_ids)) {
            $main_screen->privacies()->detach($disable_section_ids);
        }
        
        return $main_screen->privacies;
    }

    private function mapSectionIds(array $array)
    {
        return array_map(function ($item) {
            return $item['section_id'];
        }, $array);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
