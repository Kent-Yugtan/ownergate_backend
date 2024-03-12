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
use Modules\Company\App\Models\Company;
use App\Models\User;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function getCompanyCustomers(Company $company)
    {
        $customers = $company->users()->role('customer')->get();
        return $customers;
    }

    public function updateProfile(Request $request, User $customer)
    {
        $data = $request->all();
        $profile = $customer->profile;
        $profile->update($data);
        
        if($request->has('avatar') && is_file($request->avatar)) {
            $this->uploadPhoto($profile, $request->avatar, 'avatar');
        }

        if($request->has('cover_photo') && is_file($request->cover_photo)) {
            $this->uploadPhoto($profile, $request->cover_photo, 'cover_photo');
        }

        return $customer;
    }

    private function uploadPhoto($profile, $file, $key)
    {
        $path = $file;
        if ($profile->{$key}) {
            Storage::delete($profile->{$key});
        }
        $path = $file->store('customer/' . $profile->id);
        $profile->update([$key => $path]);
    }


}
