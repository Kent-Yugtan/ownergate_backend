<?php

namespace Modules\CompanyProperty\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return array_merge(parent::toArray($request), [
            'property_type' => new PropertyTypeResource($this->property_type),
            'addresses'     => new PropertyAddressesResource($this->address()->first()),
            'owner' => new PropertyUserResource($this->owners),
            'amenities_group' => $this->getGroupAmenities(),
            'amenities' => new PropertyAmenityResource($this->amenities),
            'medias' => $this->medias(),
            'download_company_logo' => $this->company_logo ? route('storage.download', ['file' => $this->company_logo]) : null,
            'download_poster' => $this->poster ? route('storage.download', ['file' => $this->poster]) : null,
            'target_type' => $this->target_type ? $this->target_type->name : "",
            'property_overview' => $this->property_overview ,
            'property_detail' => $this->property_detail ,
        ]);
    }

    private function medias()
    {
        return PropertyMediaResource::collection($this->medias);
    }

    private function getGroupAmenities()
    {
        return $this->amenities->groupBy('type.name');
    }
}
