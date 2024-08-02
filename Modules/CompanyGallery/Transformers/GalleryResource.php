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
            'assigned_to' => $this->assigned_to,
            'maintained_by' => $this->maintained_by,
            'company_id' => $this->company_id,
        ];
    } 
}
