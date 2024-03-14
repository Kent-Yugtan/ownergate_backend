<?php
namespace Modules\Dashboard\Repositories;

use Illuminate\Support\Str;
use Modules\BaseRepository;
use Illuminate\Http\Request;
use Modules\Dashboard\App\Http\Requests\ShortcutRequest;
use Illuminate\Support\Facades\DB;
use Modules\Dashboard\App\Models\Shortcut;
use Modules\Dashboard\Repositories\Interfaces\DashboardRepositoryInterface;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Modules\Company\App\Models\Company;
use Carbon\Carbon;
//use Modules\Shortcut\Http\Requests\ShortcutRequest;

class DashboardRepository extends BaseRepository implements DashboardRepositoryInterface
{

    public function getAnalytics()
    {
        $user = auth()->user();

        $stats = User::with(['customers' => function ($query) {
            $query->withCount('contracts');
        }])
        ->withCount([
            //'properties',
            'customers',
            'vendors',
        ])
        ->find($user->id);

        //print_r($stats);die;
        $near_expired_contracts = $this->nearExpiredContracts();

        $properties_status = $this->propertiesStatus();
        
        $data[] = $stats;
        $data[] = $near_expired_contracts;
        $data[] = $properties_status;
        return $data;
    }

    protected function propertiesStatus()
    {
        $company = auth()->user()->userCompany;
       
        return Company::withCount([
            'companyProperties as active_properties_count' => function ($query) {
                $query->where('status', 'Active');
            },
            'companyProperties as under_construction_properties_count' => function ($query) {
                $query->where('status', 'Under Construction');
            },
            'companyProperties as under_development_properties_count' => function ($query) {
                $query->where('status', 'Under Development');
            },
            'companyProperties as under_maintenance_properties_count' => function ($query) {
                $query->where('status', 'Under Maintenance');
            },
        ])
        ->find($company->id);
    }

    protected function nearExpiredContracts()
    {
        $currentDate = Carbon::now();

        $validContracts = auth()->user()->customers()
            ->join('contracts', 'users.id', '=', 'contracts.customer_id')
            ->select(
                'contracts.id as contract_id',
                'users.name',
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
    
    public function getShortcuts(){
        return auth()->user()->shortcuts;
    }

    public function addShortcut(ShortcutRequest $request)
    {
        $shortcut = $request->user()->shortcuts()->create($request->all());
        $data["shortcut_info"] = $shortcut;
        return $data;
    }

    public function updateShortcut(ShortcutRequest $request, Shortcut $shortcut)
    {
        $shortcut->update($request->all());
        return $shortcut;
    }

    public function deleteShortcut(Shortcut $shortcut)
    {
        return $shortcut->delete();
    }
    
}