<?php

namespace Modules\Dashboard\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardAnalyticResource extends JsonResource
{
    protected $near_expired_contracts;
    protected $properties_status;

    public function __construct($resource, $near_expired_contracts, $properties_status)
    {
        $this->near_expired_contracts = $near_expired_contracts;
        $this->properties_status = $properties_status;
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        $total_contracts = 0;

        foreach ($this->customers as $customer) {
            $total_contracts += $customer->contracts_count;
        }

        return [
            'total_properties' => $this->properties_count,
            'total_customers' => $this->customers_count,
            'total_contracts' => $total_contracts,
            'total_vendors' => $this->vendors_count,
            'total_active_ads' => 0,
            'total_banking' => 0,
            'near_expired_contracts' => $this->near_expired_contracts,
            'properties_status' => [
                'active' => $this->properties_status->active_properties_count,
                'under_construction' => $this->properties_status->under_construction_properties_count,
                'under_development' => $this->properties_status->under_development_properties_count,
                'under_maintenance' => $this->properties_status->under_maintenance_properties_count,
            ],
        ];
    }
}
