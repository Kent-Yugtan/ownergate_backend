<?php

namespace Modules\MainScreen\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class MainScreenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'logo_url' => !is_null($this->logo) ? route('storage.image', ['file' => $this->logo]) : null,
            'banner_url' => !is_null($this->banner) ? route('storage.image', ['file' => $this->banner]) : null,
            'privacies' => $this->privacies->map(function ($privacy) {
                return [
                    'id' => $privacy->id,
                    'name' => $privacy->name,
                    'module_name' => $privacy->module_name,
                    'section_id' => $privacy->pivot->section_id,
                ];
            }),
        ]);
    }
}
