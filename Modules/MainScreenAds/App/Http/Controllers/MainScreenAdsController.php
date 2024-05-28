<?php

namespace Modules\MainScreenAds\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\MainScreen\App\Models\MainScreen;
use Modules\MainScreenAds\App\Http\Requests\MainScreenAdsRequest;

class MainScreenAdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mainscreenads::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mainscreenads::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MainScreenAdsRequest $request, MainScreen $main_screen): RedirectResponse
    {
        try {
            $request->save($main_screen);
            // if ($request->filled('bulk')) {
            //     $attachment = $this->companyRepository->uploadAttachments($company, $request);
            //     return $this->successresponse(AttachmentResource::collection($company->attachments), 'The Attachment has been uploaded successfully.');
            // } else {
            //     $payload = $request->all();
            //     $attachment = $this->companyRepository->addAttachment($company, $payload);
            //     return $this->successresponse(new AttachmentResource($attachment), 'The Attachment has been uploaded successfully.');
            // }
        } catch (Exception $e) {
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
