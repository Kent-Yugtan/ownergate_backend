<?php

namespace Modules\Company\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Company\Transformers\LocationResource;
use Modules\Company\Transformers\AttachmentResource;
use Modules\CompanyProperty\Transformers\PropertyResource;

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
            'company_ogcode' => $this->owner->og_code,
            'managements' => $this->managements()->paginate(10),
            'news' => $this->news()->paginate(10),
            'services' => $this->services()->paginate(10),
            'locations' => $this->locations()->paginate(10),
            'attachments' => AttachmentResource::collection($this->attachments),
            'licenses' => $licenses,
            'documents' => $documents,
            'locations' => LocationResource::collection($this->locations),
            'company_properties' => PropertyResource::collection($this->properties),
            'avatar' => !is_null($this->profile_picture) && $this->profile_picture !== 'null' ? route('storage.image', ['file' => $this->profile_picture]) : null,
            'cover_photo' => !is_null($this->profile_poster) && $this->profile_poster !== 'null' ? route('storage.image', ['file' => $this->profile_poster]) : null,
            'type' => new CompanyTypeResource($this->type),
            'owner' => $this->owner,
            'owner_profile' => $this->owner->profile,
            'type' => $this->owner->getRoleNames()->first(),
            'privacies' => $this->privacies->map(function ($privacy) {
                return [
                    'id' => $privacy->id,
                    'name' => $privacy->name,
                    'module_name' => $privacy->module_name,
                    'section_id' => $privacy->pivot->section_id,
                ];
            }),
        ]);
    }
}
