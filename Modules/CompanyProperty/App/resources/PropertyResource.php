<?php

namespace Modules\CompanyProperty\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'property_id' => $this->id,
            'category' => $this->category,
            'property_type' => $this->propertyType,
            'target_type' => $this->targetType,
            'logo' => $this->logo,
            'download_logo' => $this->logo ? route('storage.download', ['file' => $this->logo]) : null,
            'poster' => $this->poster,
            'download_poster' => $this->poster ? route('storage.download', ['file' => $this->poster]) : null,
            'overviews' => $this->overviews,
            'details' => $this->details,
            'features' => $this->features,
            'amenities' => $this->amenities,
            'utilities' => $this->utilities,
            'unitalities' => $this->unitalities,
            'whats_nearbies' => $this->whatsNearbies,
            'plans' => $this->plans->map(function ($plan) {
                return [
                    'name' => $plan->name,
                    'photo' => $plan->photo,
                    'download_photo' => $plan->photo ? route('storage.download', ['file' => $plan->photo]) : null,
                ];
            })
        ]);
    }
}
