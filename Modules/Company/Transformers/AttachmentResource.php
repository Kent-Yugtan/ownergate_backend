<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'download_path' => $this->path ?  route('storage.download', ['file' => $this->path]) : null,
        ]);
    }
}
