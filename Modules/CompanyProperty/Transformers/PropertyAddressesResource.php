<?php

namespace Modules\CompanyProperty\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyAddressesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
