<?php

namespace Modules\Dashboard\App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Inventory\App\Models\Inventory;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\Dashboard\Transformers\DashboardAnalyticResource;

class DashboardAnalyticsController extends Controller
{
    use ApiResponser;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = [];

        $stats['total_properties'] = CompanyProperty::count();
        $stats['total_customers'] = User::roleCount('Customer');
        $stats['total_vendors'] = User::roleCount('Vendor');
        $stats['total_ads'] = Inventory::whereHas('type', function ($query) {
            $query->where('item_name', 'Ads');
        })->count();

        $stats['recent_properties'] = $this->recentProperties();
        $stats['properties_status'] = $this->propertiesStatus();

        return $this->successresponse(new DashboardAnalyticResource($stats));
    }

    protected function recentProperties()
    {
        $properties = CompanyProperty::orderBy('created_at', 'desc')->take(3)->get();

        $properties = $properties->map(function ($property) {
            return [
                'id' => $property->id,
                'owner' => $property->company->company_name . ' - #' . $property->company->owner->og_code,
                'created_at' => Carbon::parse($property->created_at)->diffForHumans(),
            ];
        });

        return $properties;
    }

    protected function propertiesStatus()
    {
        $total = [];
        $statuses = ['Active', 'Inactive', 'Under Construction', 'Under Development', 'Under Maintenance'];
        $total_properties = CompanyProperty::count();

        $propertyCounts = CompanyProperty::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        foreach ($propertyCounts as $count) {
            foreach ($statuses as $status) {
                if ($count->status === $status) {
                    $stat = ($total_properties > 0) ? ($count->count / $total_properties) * 100 : 0;
                    $stat = ($stat == intval($stat)) ? intval($stat) : number_format($stat, 1);
                    $total[strToLower($status)] = $stat . '%';
                }
            }
        }

        return $total;
    }

}
