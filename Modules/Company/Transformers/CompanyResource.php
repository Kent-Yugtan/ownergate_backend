<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Company\Transformers\AttachmentResource;

class CompanyResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'managements' => ManagementResource::collection($this->managements),
            'news' => NewsResource::collection($this->news),
            'services' => ServicesResource::collection($this->services),
            'attachments' => AttachmentResource::collection($this->attachments),
            'avatar' => !is_null($this->avatar) && $this->avatar !== 'null' ? route('storage.image', ['file' => $this->avatar]) : null,
            'cover_photo' => !is_null($this->cover_photo) && $this->cover_photo !== 'null' ? route('storage.image', ['file' => $this->cover_photo]) : null,
            'type' => new CompanyTypeResource($this->type)
        ]);
    }
}
