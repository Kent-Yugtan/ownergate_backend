<?php

namespace Modules\CompanyProperty\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

use Modules\companyProperty\Transformers\AvailableSettingResource;

class SelectedSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        //return parent::toArray($request);
        return [
            "property_id"=> $this->id,
            "selected_settings" => AvailableSettingResource::collection($this->available_settings),
            'available_settings' => AvailableSettingResource::collection($this->settings)
        ];
    }
}
