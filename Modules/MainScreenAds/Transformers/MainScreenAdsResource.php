<?php

namespace Modules\MainScreenAds\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MainScreenAdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
