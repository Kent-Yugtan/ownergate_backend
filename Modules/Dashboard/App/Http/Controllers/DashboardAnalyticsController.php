<?php

namespace Modules\Dashboard\App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Dashboard\Transformers\DashboardAnalyticResource;

class DashboardAnalyticsController extends Controller
{
    use ApiResponser;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $stats = User::with(['customers' => function ($query) {
            $query->withCount('contracts');
        }])
        ->withCount([
            'customers',
        ])
        ->find($user->id);
        dd($stats);
        $near_expired_contracts = $this->nearExpiredContracts();
        
        $properties_status = $this->propertiesStatus();

        return $this->successresponse(new DashboardAnalyticResource($stats, $near_expired_contracts, $properties_status));
    }

    protected function propertiesStatus()
    {
        $user = auth()->user();

        return User::withCount([
            'properties as active_properties_count' => function ($query) {
                $query->where('status', 'Active');
            },
            'properties as under_construction_properties_count' => function ($query) {
                $query->where('status', 'Under Construction');
            },
            'properties as under_development_properties_count' => function ($query) {
                $query->where('status', 'Under Development');
            },
            'properties as under_maintenance_properties_count' => function ($query) {
                $query->where('status', 'Under Maintenance');
            },
        ])
        ->find($user->id);
    }

    protected function nearExpiredContracts()
    {
        $currentDate = Carbon::now();

        $validContracts = auth()->user()->customers()
            ->join('contracts', 'customers.id', '=', 'contracts.customer_id')
            ->select(
                'contracts.id as contract_id',
                'customers.name',
                'contracts.customer_id',
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.start_date")) AS start_date'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.end_date")) AS end_date'),
                DB::raw('DATEDIFF(JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.end_date")), JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.start_date"))) AS total_duration_days'),
                DB::raw('DATEDIFF(JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.end_date")), NOW()) AS remaining_days'),
                DB::raw('CAST((DATEDIFF(JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.end_date")), JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.start_date"))) - DATEDIFF(JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.end_date")), NOW())) / DATEDIFF(JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.end_date")), JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.start_date"))) * 100 AS UNSIGNED) AS progress')
            )
            ->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(contract_details, "$.end_date")) > ?', [$currentDate->toDateString()])
            ->where('status', 'Active')
            ->orderBy('end_date') // Order by end date to get contracts closest to expiration
            ->limit(3) // Limit the result to the first 3 contracts
            ->get();

        // Ensure progress is capped at 100% and not negative
        $validContracts->transform(function ($contract) {
            $contract->progress = intval(max(0, min(100, $contract->progress))); // Cap progress between 0 and 100
            return $contract;
        });

        return $validContracts;
    }
}
