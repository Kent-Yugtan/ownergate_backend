<?php

namespace Modules\CompanyEmployee\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'attachments'=>EmployeeAttachmentsResource::collection($this->attachments),
            'user'=>$this->user,
            'admin'=>$this->admin,
            'company'=>$this->company

        ]);
    }
}
