<?php

namespace Modules\CompanyProperty\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Traits\MediaUploadingTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Company\App\Models\Company;
use Illuminate\Contracts\Support\Renderable;
use Modules\CompanyProperty\Transformers\PropertyMediaResource;
use Modules\CompanyProperty\App\Http\Requests\StoreMediaRequest;

class PropertyMediaController extends Controller
{
    use ApiResponser, MediaUploadingTrait;

    private $propertyRepository;

    public function saveFullVideo(StoreMediaRequest $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $property = $company->createOrGetProperty($request->property_id);

            if($request->media) {
                $media = $this->storeMedia($request->validated(), $property);
                
                DB::commit();

                return $this->SuccessResponse(new PropertyMediaResource($media), 'Property Full Video has been saved.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveVirtualTour(StoreMediaRequest $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $property = $company->createOrGetProperty($request->property_id);

            if($request->media) {
                $media = $this->storeMedia($request->validated(), $property);
                
                DB::commit();

                return $this->SuccessResponse(new PropertyMediaResource($media), 'Property 360 Virtual Tour has been saved.');
            }

            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveVirtualSpots(StoreMediaRequest $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $property = $company->createOrGetProperty($request->property_id);

            if($request->media) {
                $media = $this->storeMedia($request->validated(), $property);
                
                DB::commit();

                return $this->SuccessResponse(new PropertyMediaResource($media), 'Property Virtual Spots has been saved.');
            }

            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function savePhotos(StoreMediaRequest $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $property = $company->createOrGetProperty($request->property_id);

            if($request->media) {
                $media = $this->storeMedia($request->validated(), $property);
                
                DB::commit();

                return $this->SuccessResponse(new PropertyMediaResource($media), 'Property Photos has been saved.');
            }

            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
