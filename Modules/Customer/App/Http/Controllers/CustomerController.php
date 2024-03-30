<?php

namespace Modules\Customer\App\Http\Controllers;

use App\Traits\ApiHelper;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Auth\Transformers\UserResource;
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

    public function index(Request $request)
    {
        try {
            $customers = $this->customerRepository->getCustomers($request);
            return UserResource::collection($customers);
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($customer)
    {
        try {
            $customer = $this->customerRepository->getCustomer($customer);
            return $this->successResponse(new UserResource($customer), 'Customer has been retrieved');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $customer)
    {
        try {
            $this->customerRepository->updateStatus($customer, $request);
            return $this->successResponse(null, 'Status has been updated');
        } catch (\Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
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
