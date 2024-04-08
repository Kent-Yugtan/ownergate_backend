<?php

namespace Modules\Admin\App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\App\Models\DiscoverProperty;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\Admin\Transformers\DiscoverPropertyResource;

class DiscoverPropertiesController extends Controller
{
    use ApiResponser;
    
    public function getAllPropertiesCities(Request $request)
    {
        $perPage = $request->perPage ?? 10;

        return CompanyProperty::where('status', 'Active')->select('id', 'city', 'country')->paginate($perPage);
    }

    public function savePropertyCities(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'city' => 'required',
                'country' => 'required',
                'image' => 'nullable',
            ]);

            if(is_file($request->image)) {
                $old_image = DiscoverProperty::where('city', $validatedData['city'])->where('country', $validatedData['country'])->first();

                if ($old_image && $old_image->image) {
                    Storage::delete($old_image->image);
                }

                $name = $request->image->hashName();
                $validatedData['image'] = $request->image->storeAs('discover-properties/', $name);
            }

            $discover_property = DiscoverProperty::updateOrCreate([
                'city' => $validatedData['city'],
                'country' => $validatedData['country']
            ], [
                'image' => $validatedData['image']
            ]);
            
            DB::commit();

            return $this->successresponse(new DiscoverPropertyResource($discover_property), 'Discover property has been saved.');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function savePropertyListing(Request $request, DiscoverProperty $city)
    {
        
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'property_type' => 'nullable',
                'target_type' => 'nullable',
                'price' => 'nullable',
                'beds_bath_kitchen' => 'nullable',
                'size' => 'nullable',
                'amenities' => 'nullable',
                'features' => 'nullable',
                'property_nearby' => 'nullable',
                'listed_by' => 'nullable',
                'property_by' => 'nullable',
                'vendor' => 'nullable'
            ]);

            if ($city->listing) {
                $listing = $city->listing()->update($validatedData);
            } else {
                $listing = $city->listing()->Create($validatedData);
            }
            
            DB::commit();

            return $this->successresponse($listing, 'Discover property listing has been saved.');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
