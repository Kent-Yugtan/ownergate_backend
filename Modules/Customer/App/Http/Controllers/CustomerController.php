<?php

namespace Modules\Customer\App\Http\Controllers;

use App\Traits\ApiHelper;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Customer\Transformers\CustomerResource;
use Modules\Customer\Repositories\Interfaces\CustomerRepositoryInterface;
use Modules\Company\App\Models\Company;
use App\Models\User;

class CustomerController extends Controller
{
    use ApiResponser, ApiHelper;
    private $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Company $company)
    {
        try {
            $customers = $this->customerRepository->getCompanyCustomers($company);
            return $this->successresponse($customers, 'There is no contracts.');
            
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateProfile(Request $request, User $customer)
    {
        try {
            $cus = $this->customerRepository->updateProfile($request, $customer);
            return $this->successresponse($cus, 'There is no contracts.');
            
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    /*public function store(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $customer = $this->customerRepository->create($request, $company);
            DB::commit();
            return $this->successresponse($customer, 'Customer has been added.');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }*/

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('customer::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('customer::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
