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
            // 'avatar' => !is_null($this->avatar) && $this->avatar !== 'null' ? route('storage.image', ['file' => $this->avatar]) : null,
            // 'cover_photo' => !is_null($this->cover_photo) && $this->cover_photo !== 'null' ? route('storage.image', ['file' => $this->cover_photo]) : null,
            // 'geolocation' => [
            //     'lat' => (double) $this->latitude,
            //     'lng' =>  (double) $this->longitude
            // ],
            // 'company' => new CompanyResource($this->company),
            // 'user_types' => $this->userType,
            // 'profile' => new UserProfileResource($this->profile),
            'role' => $this->getRoleNames()
        ]);

        // if($this->user_type_id === 2){
        //     $user_resource['customer_info'] = $this->customer;
        // }

        return $user_resource;
    }
}
