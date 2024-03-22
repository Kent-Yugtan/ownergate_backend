<?php

namespace Modules\CompanyPrivacy\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyPrivacyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'company_id' => $this->pivot->company_id,
            'section_id' => $this->pivot->section_id,
        ];
    }
}
