<?php

namespace Modules\MainScreen\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\MainScreen\App\Models\MainScreen;
use Modules\MainScreen\Transformers\MainScreenResource;
use Illuminate\Support\Facades\Storage;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Modules\Company\App\Models\Company;
use Modules\Company\Transformers\CompanyResource;
use Modules\CompanyProperty\App\Models\Amenity;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\CompanyProperty\Transformers\PropertyResource;
use Modules\CompanyProperty\App\Models\PropertyType;
use Modules\CompanyProperty\App\Models\CategoryTargetType;
use Modules\CompanyProperty\App\Models\Category;
use Modules\CompanyProperty\App\Models\Feature;
use Modules\CompanyProperty\App\Models\PropertyDetail;

class MainScreenController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $settings = MainScreen::orderBy('id', 'desc')->first();
            return $this->successResponse(new MainScreenResource($settings), 'Settings has been retrieved.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $settings = MainScreen::orderBy('id', 'desc')->first();
            $id = !is_null($settings) ? $settings->id : null;
            
            $settings = MainScreen::updateOrCreate(
                ['id' => $id],
                ['title' => $request->title]
            );

            $images = ['logo', 'banner'];
            foreach($images as $field){
                if($request->filled($field) || $request->hasFile($field)){
                    $this->saveImagePath($request, $settings, $field);
                }
            }

            DB::commit();
            return $this->successResponse(new MainScreenResource($settings), 'Settings has been saved.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    private function saveImagePath($request, $settings, $field){
        $path = $request[$field];

        if(is_file($request[$field])){
            $path = $request->file($field)->store('mainscreen');
        }

        $settings->update([$field => $path]);
    }

    public function getProperties(Request $request)
    {
        try {
            $perPage = $request->perPage ?? 10;
            $properties = CompanyProperty::search($request)->paginate($request->perPage);

            return PropertyResource::collection($properties);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getFilterOptions(){
        $types = PropertyType::orderBy('name')->whereNotNull('name')->get();
        $targetTypes = CategoryTargetType::select('name')->distinct()->orderBy('name')->whereNotNull('name')->get();
        $countries = CompanyProperty::select('country')->distinct()->orderBy('country')->whereNotNull('country')->get();
        $states = CompanyProperty::select('state')->distinct()->orderBy('state')->whereNotNull('state')->get();
        $cities = CompanyProperty::select('city')->distinct()->orderBy('city')->whereNotNull('city')->get();
        $categories = Category::orderBy('name')->whereNotNull('name')->get();

        //FIXES
        $size = PropertyDetail::select('value')->where('detail_id', 5)->distinct()->orderBy('value')->get();
        $amenities = Amenity::select('name')->distinct()->whereNotNull('name')->orderBy('name')->get();
        $features = Feature::select('name')->distinct()->whereNotNull('name')->orderBy('name')->get();
        // $property_nearby =
        // $listed_by =
        $property_by = Company::select('company_name')->distinct()->whereNotNull('company_name')->orderBy('company_name')->get();
        // $vendor =
        $lowestValue = intval(CompanyProperty::min('value'));
        $highestValue = intval(CompanyProperty::max('value'));

        return [
            'types' => $types,
            'target_types' => $targetTypes,
            'countries' => $countries,
            'size' => $size,
            'states' => $states,
            'cities' => $cities,
            'categories' => $categories,
            'amenities' => $amenities,
            'features' => $features,
            'property_by' => $property_by,
            'minPrice' => $lowestValue,
            'maxPrice' => $highestValue,
        ];
    }

    public function getCompanyLocations(Request $request){
        try{
            $perPage = $request->perPage ?? 10;
            $companyLocations = Company::paginate($perPage);

            return CompanyResource::collection($companyLocations);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function getPropertyHasAddress(Request $request){
        try{
            $perPage = $request->perPage ?? 10;
            $properties = CompanyProperty::has('address')->paginate($perPage);
            return PropertyResource::collection($properties);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

}
