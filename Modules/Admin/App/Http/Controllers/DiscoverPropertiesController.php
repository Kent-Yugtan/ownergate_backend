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

    public function index(Request $request)
    {
        $data = [];
        $cities = DiscoverProperty::with('listing')->get();

        foreach ($cities as $city) {
            $res= [];
            $total_listings = 0;

            if ($city->listing) {
                $total_listings = CompanyProperty::where('city', $city->city)->where('country', $city->country)
                ->filterPropertyType($city->listing->property_type)
                ->filterTargetType($city->listing->target_type)
                ->count();
            }
            $res['id'] = $city->id;
            $res['country'] = $city->country;
            $res['city'] = $city->city;
            $res['image'] = $city->image;
            $res['download_image'] = $city->image ? route('storage.download', ['file' => $city->image]) : null;
            $res['listing'] = $total_listings;

            $data[] = $res;
        }

        return $this->successresponse($data, 'Discover Properties');
    }

    public function deleteCity(Request $request, $city) {
        try {
            DB::beginTransaction();
    
            $cityData = DiscoverProperty::find($city);
            
            if (!$cityData) {
                throw new \Exception("City not found");
            }
    
            Storage::delete($cityData->image);
            $cityData->delete();
    
            DB::commit();
    
            return $this->successresponse($cityData, 'Discover Property has been successfully deleted.');
        } catch (\Exception $e) {
            DB::rollback();
    
            return $this->errorResponse(null, $e->getMessage());
        }
    }
    
    
    public function getAllPropertiesCities(Request $request)
    {
        return CompanyProperty::where('status', 'Active')
            ->select('country', \DB::raw('GROUP_CONCAT(city) as cities'))
            ->groupBy('country')
            ->get()
            ->map(function ($item) {
                $item['cities'] = explode(',', $item['cities']);
                return $item;
            });
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
                $city->listing()->update($validatedData);
            } else {
                $city->listing()->create($validatedData);
            }

            $total_listings = CompanyProperty::where('city', $city->city)->where('country', $city->country)
                ->filterPropertyType($validatedData['property_type'])
                ->filterTargetType($validatedData['target_type'])
                ->count();
            
            DB::commit();

            return $this->successresponse([
                'total_listings' => $total_listings
            ], 'Discover property listing has been saved.');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
