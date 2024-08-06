<?php

namespace Modules\CompanyGallery\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'og_code' => $this->og_code,
            'name' => $this->name,
            'logo' =>$this->logo,
            'poster' => $this->poster,
            'currency' => $this->currency,
            'value' => $this->value,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'area_sector_desctrict' => $this->area_sector_desctrict,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'description' => $this->description,
            'full_video' => $this->full_video,
            'status' => $this->status,
            'notes' => $this->notes,
            'company_id' => $this->company_id,
            'company' => $this->company,
            'category_id' => $this->category_id,
            'category' => $this->category,
            'type_id' => $this->type_id,
            'type' => $this->propertyType,
            'target_type_id' => $this->target_type_id,
            'target_type' =>  $this->targetType,
            'source_property_id' => $this->source_property_id
        ];
        
        
    } 
}
