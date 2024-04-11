<?php

namespace Modules\PropertyPrivacy\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\PropertyPrivacy\Transformers\PropertyPrivacyResource;
use Modules\PropertyPrivacy\App\Http\Requests\PropertyPrivacyRequest;

class PropertyPrivacyController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index(CompanyProperty $property)
    {
        return PropertyPrivacyResource::collection($property->privacies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PropertyPrivacyRequest $request, CompanyProperty $property)
    {
        try {
            DB::beginTransaction();

            $privacies = $request->save($property);
            
            DB::commit();
            return PropertyPrivacyResource::collection($privacies);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
