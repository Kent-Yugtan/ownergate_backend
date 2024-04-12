<?php

namespace Modules\PropertyPrivacy\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyPrivacyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'property_id' => $this->pivot->property_id,
            'section_id' => $this->pivot->section_id,
        ];
    }
}
