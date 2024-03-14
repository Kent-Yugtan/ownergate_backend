<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Company\App\resources\LocationResource;
use Modules\Company\Transformers\AttachmentResource;
use Modules\CompanyProperty\App\resources\PropertyResource;

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

        $licenses = [];
        $documents = [];

        foreach ($this->attachments as $attachment) {
            if ($attachment->type === 'license' || $attachment->type === 'License') {
                $licenses[] = new AttachmentResource($attachment);
            } elseif ($attachment->type === 'document' || $attachment->type === 'Document') {
                $documents[] = new AttachmentResource($attachment);
            }
        }
        return array_merge(parent::toArray($request), [
            'managements' => ManagementResource::collection($this->managements),
            'news' => NewsResource::collection($this->news),
            'services' => ServicesResource::collection($this->services),
            'attachments' => AttachmentResource::collection($this->attachments),
            'licenses' => $licenses,
            'documents' => $documents,
            'locations' => LocationResource::collection($this->locations),
            'company_properties' => PropertyResource::collection($this->properties),
            'avatar' => !is_null($this->avatar) && $this->avatar !== 'null' ? route('storage.image', ['file' => $this->avatar]) : null,
            'cover_photo' => !is_null($this->cover_photo) && $this->cover_photo !== 'null' ? route('storage.image', ['file' => $this->cover_photo]) : null,
            'type' => new CompanyTypeResource($this->type)
        ]);
    }
}
