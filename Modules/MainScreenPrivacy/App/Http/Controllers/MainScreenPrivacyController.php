<?php

namespace Modules\MainScreenPrivacy\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\MainScreen\App\Models\MainScreen;
use Modules\MainScreenPrivacy\Transformers\MainScreenPrivacyResource;
use Modules\MainScreenPrivacy\App\Http\Requests\MainScreenPrivacyRequest;

class MainScreenPrivacyController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index(MainScreen $main_screen)
    {
        return MainScreenPrivacyResource::collection($main_screen->privacies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MainScreenPrivacyRequest $request, MainScreen $main_screen)
    {
        try {
            DB::beginTransaction();

            $privacies = $request->save($main_screen);
            
            DB::commit();

            return MainScreenPrivacyResource::collection($privacies);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
