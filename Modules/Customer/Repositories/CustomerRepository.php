<?php
namespace Modules\Customer\Repositories;

use Carbon\Carbon;
use Modules\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Customer\Http\Requests\CustomerRequest;
use Modules\Customer\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Models\User;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function getCustomers($request)
    {
        $perpage = $request->perPage ?? 10;
        $customers = User::withTrashed()->when($request->keyword, function($q) use($request){
            $q->whereHas('profile', function($q) use($request){
                $q->where('first_name', 'LIKE', $request->keyword.'%')->orWhere('last_name', 'LIKE', $request->keyword.'%');
            });
        })->whereHas('roles', function ($q) {
            $q->where('name', 'Customer');
        })->paginate($request->perPage);

        return $customers;
    }

    public function getCustomer($id)
    {
        $customer = User::withTrashed()->find($id);
        return $customer;
    }

    public function updateStatus($id, $request)
    {
        $customer = User::withTrashed()->find($id);
        if($request->status == 'active')
        {
            $customer->restore();
        }

        if($request->status == 'inactive')
        {
            $customer->delete();
        }
    }
}
