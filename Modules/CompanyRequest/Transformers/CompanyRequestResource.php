<?php

namespace Modules\CompanyRequest\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CompanyProperty\Transformers\PropertyResource;

class CompanyRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'property' => new PropertyResource($this->property)
        ]);
    }
}
