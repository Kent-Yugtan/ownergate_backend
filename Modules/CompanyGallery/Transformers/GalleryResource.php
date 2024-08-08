<?php

namespace Modules\CompanyGallery\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CompanyProperty\Transformers\PropertyResource;

class GalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'assigned_to' => $this->getAssignTo,
            'maintained_by' => $this->getMaintainBy,
            'company_id' => $this->company_id,
            'property' => $this->companyProperty ? new PropertyResource($this->companyProperty) : null,

        ];
        
        
    } 
}
