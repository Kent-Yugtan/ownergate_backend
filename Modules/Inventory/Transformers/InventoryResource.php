<?php

namespace Modules\Inventory\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'attachments' => $this->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'title' => $attachment->title,
                    'path' => $attachment->path,
                    'download_attachment' => $attachment->path ?  route('storage.download', ['file' => $attachment->path]) : null,
                ];
            }),
        ]);
    }
}
