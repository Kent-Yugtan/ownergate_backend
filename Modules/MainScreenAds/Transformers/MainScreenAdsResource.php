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
        $image = null;

        if (count($this->inventory->attachments)) {
            $image = $this->inventory->attachments->first()->path;
        }

        $request = collect(parent::toArray($request))->except(['inventory', 'created_at', 'updated_at'])->toArray();
        
        return array_merge($request, [
            'image' => !is_null($image) && $image !== 'null' ? route('storage.image', ['file' => $image]) : null,
        ]);
    }
}
