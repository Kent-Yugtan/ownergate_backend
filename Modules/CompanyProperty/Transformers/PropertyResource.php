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
        $groupedAmenities = $this->amenities->groupBy('amenity_type_id')->map(function ($amenities) {
            return [
                'amenity_type_id' => $amenities[0]->amenity_type_id,
                'amenity_type_name' => $amenities[0]->type->name,
                'amenities_details' => $amenities->map(function ($amenity) {
                    return [
                        'amenity_id' => $amenity->id,
                        'amenity_name' => $amenity->name
                    ];
                })
            ];
        })->values();

        return array_merge(parent::toArray($request), [
            'property_id' => $this->id,
            'category' => $this->category,
            'company' => $this->company,
            'company_user' => $this->company->owner->profile,
            'company_name' => $this->company->company_name,
            'company_ogcode' => $this->company->owner->og_code,
            'property_type' => $this->propertyType,
            'privacies' => $this->privacies->map(function ($privacy) {
                return [
                    'id' => $privacy->id,
                    'name' => $privacy->name,
                    'module_name' => $privacy->module_name,
                    'section_id' => $privacy->pivot->section_id,
                ];
            }),
            'target_type' => $this->targetType,
            'remark' => $this->remark,
            'logo' => $this->logo,
            'source_og_code' => $this->sourceProperty->og_code,
            'source_property_poster' => $this->sourceProperty->poster,
            'source_property_poster_download' => $this->sourceProperty->poster ? route('storage.download', ['file' => $this->sourceProperty->poster]) : null,
            'download_logo' => $this->logo ? route('storage.download', ['file' => $this->logo]) : null,
            'poster' => $this->poster,
            'download_poster' => $this->poster ? route('storage.download', ['file' => $this->poster]) : null,
            'overviews' => $this->overviews->map(function ($overview) {
                return [
                    'id' => $overview->id,
                    'name' => $overview->name,
                    'quantity' => $overview->pivot->quantity,
                    'visible' => $overview->pivot->visible,
                ];
            }),
            'details' => $this->details->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'name' => $detail->name,
                    'value' => $detail->pivot->value,
                ];
            }),
            'features' => $this->features->map(function ($feature) {
                return [
                    'id' => $feature->id,
                    'name' => $feature->name,
                    'feature_id' => $feature->pivot->feature_id,
                ];
            }),
            'amenities' => $groupedAmenities,
            'utilities' => $this->utilities,
            'unitalities' => $this->unitalities,
            'whats_nearbies' => $this->whatsNearbies,
            'address_details' => $this->addressDetails,
            'plans' => $this->plans->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'photo' => $plan->photo,
                    'download_photo' => $plan->photo ? route('storage.download', ['file' => $plan->photo]) : null,
                ];
            }),
            'addresses'     => new PropertyAddressesResource($this->address()->first()),
            'owner' => new PropertyUserResource($this->owners),
            'medias' => $this->medias(),
            'employee' => $this->when($this->pivot && isset($this->pivot->employee_id), function () {
                return [
                    'employee_property_id' => $this->pivot->id,
                    'access_code' => $this->pivot->access_code ?? null,
                ];
            }),
            'status' => $this->status,
            'notes' => $this->notes,
            // 'download_company_logo' => $this->company_logo ? route('storage.download', ['file' => $this->company_logo]) : null,
            // 'download_poster' => $this->poster ? route('storage.download', ['file' => $this->poster]) : null,
            // 'amenities_group' => $this->getGroupAmenities(),
            // 'target_type' => $this->target_type ? $this->target_type->name : "",
            // 'property_overview' => $this->property_overview ,
            // 'property_detail' => $this->property_detail ,
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
