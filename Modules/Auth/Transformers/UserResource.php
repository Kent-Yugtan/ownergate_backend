<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Company\Transformers\CompanyResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $user_resource = array_merge(parent::toArray($request), [
            'name' => $this->name,
            'company' => new CompanyResource($this->company),
            'profile' => new UserProfileResource($this->profile),
            'role' => $this->getRoleNames()
        ]);

        // if($this->user_type_id === 2){
        //     $user_resource['customer_info'] = $this->customer;
        // }

        return $user_resource;
    }
}
