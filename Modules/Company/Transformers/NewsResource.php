<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'image_path' => $this->image_path ?  route('storage.download', ['file' => $this->image_path]) : null,
        ]);
    }
}
