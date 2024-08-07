<?php
namespace Modules\CompanyGallery\Transformers;
use Illuminate\Http\Resources\Json\JsonResource;
class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->user->profile->first_name,
            'last_name' => $this->user->profile->last_name,
            'user_id' => $this->user->id
        ];
    }
}