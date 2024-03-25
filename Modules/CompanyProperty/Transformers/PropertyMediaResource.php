<?php

namespace Modules\CompanyProperty\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request): array
    {
        $this->makeHidden(['created_at', 'updated_at']);

        return array_merge(parent::toArray($request), [
            'paths' => $this->paths->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'name' => $attachment->name,
                    'path' => $attachment->path,
                    'download_path' => $attachment->path ?  route('storage.download', ['file' => $attachment->path]) : null,
                ];
            })
        ]);
    }
}
