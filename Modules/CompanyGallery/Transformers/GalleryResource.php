<?php

namespace Modules\CompanyGallery\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'property_id' => $this->property_id,
            'property_name' => $this->companyProperty?->name,
            'type' => $this->companyProperty?->propertyType?->name, // Null-safe access to nested property
            'assigned_to' => $this->getAssignTo?->og_code,
            'maintained_by' => $this->getMaintainBy?->og_code,
            'assigned_to_id' => $this->getAssignTo?->id,
            'maintained_by_id' => $this->getMaintainBy?->id,
            'company_id' => $this->company_id,
        ];
        
        
    } 
}
