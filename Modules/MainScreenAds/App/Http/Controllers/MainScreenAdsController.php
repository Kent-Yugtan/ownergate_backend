<?php

namespace Modules\MainScreenAds\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Inventory\App\Models\Inventory;
use Modules\MainScreen\App\Models\MainScreen;
use Modules\MainScreenAds\Transformers\MainScreenAdsResource;
use Modules\MainScreenAds\App\Http\Requests\MainScreenAdsRequest;

class MainScreenAdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, MainScreen $main_screen)
    {
        return MainScreenAdsResource::collection($main_screen->ads);
    }

    public function getAllAvailableAds(MainScreen $main_screen)
    {
        return Inventory::whereHas('type', function ($query) {
            $query->where('item_name', 'Ads');
        })
        ->where(function ($query) {
            $query->whereHas('ads', function ($query) {
                $query->where('status', 'inactive');
            })
            ->orWhereDoesntHave('ads');
        })
        ->get()
        ->map(function ($item) {
            return [
                'ads_id' => $item->id,
                'item_id' => $item->item_id
            ];
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MainScreenAdsRequest $request, MainScreen $main_screen)
    {
        try {
            DB::beginTransaction();

            $ads = $request->save($main_screen);
            
            DB::commit();

            return MainScreenAdsResource::collection($main_screen->ads);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('mainscreenads::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('mainscreenads::edit');
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
