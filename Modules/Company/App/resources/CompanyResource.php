<?php

namespace Modules\Company\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'managements' => $this->managements()->paginate(10),
            'news' => $this->news()->paginate(10),
            'services' => $this->services()->paginate(10),
            'locations' => $this->locations()->paginate(10),
            'attachments' => AttachmentResource::collection($this->attachments),
            'avatar' => !is_null($this->avatar) && $this->avatar !== 'null' ? route('storage.image', ['file' => $this->avatar]) : null,
            'cover_photo' => !is_null($this->cover_photo) && $this->cover_photo !== 'null' ? route('storage.image', ['file' => $this->cover_photo]) : null,
            'type' => new CompanyTypeResource($this->type)
        ]);
    }
}
