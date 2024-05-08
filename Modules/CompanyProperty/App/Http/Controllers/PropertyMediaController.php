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
use Modules\CompanyProperty\App\Models\PropertyMedia;
use Modules\CompanyProperty\App\Models\PropertyMediaPath;
use Modules\CompanyProperty\Transformers\PropertyResource;
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

                return $this->SuccessResponse(new PropertyResource($property), 'Property Full Video has been saved.');
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

                return $this->SuccessResponse(new PropertyResource($property), 'Property 360 Virtual Tour has been saved.');
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

                return $this->SuccessResponse(new PropertyResource($property), 'Property Virtual Spots has been saved.');
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
            $media_id = $request['media_id'] ?? null;

            if ($media_id) {
                $property_media = $property->medias()->where('id', $media_id)->first();
                $property_media->update([
                    'name' => $request['name'] ?? null,
                    'description' => $request['description'] ?? null,
                    'area' => $request['area'] ?? null,
                    'type' => $request['type'] ?? null,
                ]);
            }

            if($request->media) {
                $media = $this->storeMedia($request->validated(), $property);
            }

            DB::commit();

            return $this->SuccessResponse(new PropertyResource($property), 'Property Photos has been saved.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function deletePhoto(Request $request, PropertyMedia $media, PropertyMediaPath $path)
    {
        try {
            DB::beginTransaction();

            $property = $media->property;
            
            
            $path->delete();
    
            DB::commit();
    
            return $this->successresponse(new PropertyResource($property), 'Successfully deleted.');
        } catch (\Exception $e) {
            DB::rollback();
    
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function deleteMedia(Request $request, PropertyMedia $media)
    {
        try {
            DB::beginTransaction();

            $property = $media->property;

            foreach ($media->paths as $path) {
                if ($path->path) {
                    Storage::delete($path->path);
                }
            }
    
            $media->delete();
    
            DB::commit();
    
            return $this->successresponse(new PropertyResource($property), 'Successfully deleted.');
        } catch (\Exception $e) {
            DB::rollback();
    
            return $this->errorResponse(null, $e->getMessage());
        }
    }

}
