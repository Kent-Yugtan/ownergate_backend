<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'avatar' => !is_null($this->avatar) && $this->avatar !== 'null' ? route('storage.image', ['file' => $this->avatar]) : null,
        ]);
    }
}
