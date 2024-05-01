<?php

namespace Modules\MainScreenPrivacy\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MainScreenPrivacyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'main_screen_id' => $this->pivot->main_screen_id,
            'section_id' => $this->pivot->section_id,
        ];
    }
}
