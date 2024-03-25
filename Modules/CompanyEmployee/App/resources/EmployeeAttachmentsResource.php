<?php

namespace Modules\CompanyEmployee\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeAttachmentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            "download_link" => $this->path ? route('storage.download', ['file' => $this->path]) : null,
        ]);
    }
}
