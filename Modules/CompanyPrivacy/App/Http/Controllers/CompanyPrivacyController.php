<?php

namespace Modules\CompanyPrivacy\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Company\App\Models\Company;
use Modules\CompanyPrivacy\Transformers\CompanyPrivacyResource;
use Modules\CompanyPrivacy\App\Http\Requests\CompanyPrivacyRequest;

class CompanyPrivacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company)
    {
        if (!auth()->user()->company->is($company)) {
            abort(403, 'Unauthorized action.');
        }

        return CompanyPrivacyResource::collection($company->privacies);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyPrivacyRequest $request, Company $company)
    {
        try {
            if (!auth()->user()->company->is($company)) {
                abort(403, 'Unauthorized action.');
            }
            DB::beginTransaction();

            $privacies = $request->save($company);

            DB::commit();

            return CompanyPrivacyResource::collection($privacies);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(null, $e->getMessage());
        }

        return $company->privacies;
    }
}
