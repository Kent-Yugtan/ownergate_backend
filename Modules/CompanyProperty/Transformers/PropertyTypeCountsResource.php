<?php

namespace Modules\CompanyProperty\Transformers;

use App\Models\PropertyType;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyTypeCountsResource extends JsonResource
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
        // return [
        //     'id' => $this->id,
        //     'count' => $this->count,
        // ];

        // return array_merge(parent::toArray($request), [
        // 'properties' =>  PropertyResource::collection($this->properties),
        // 'property_count' => 'property_count',
        // ]);
    }
}
