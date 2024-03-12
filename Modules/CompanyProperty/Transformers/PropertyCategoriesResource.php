<?php

namespace Modules\CompanyProperty\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyCategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
        /*return array_merge(parent::toArray($request), [
            'target_types' => $this->targets
        ]);*/
    }
}
