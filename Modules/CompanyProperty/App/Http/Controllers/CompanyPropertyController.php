<?php

namespace Modules\CompanyProperty\App\Http\Controllers;

use App\Traits\ApiHelper;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Modules\Company\App\Models\Company;
use Modules\CompanyProperty\App\Models\Detail;
use Modules\CompanyProperty\App\Models\Amenity;
use Modules\CompanyProperty\App\Models\Feature;
use Modules\CompanyProperty\App\Models\Utility;
use Modules\CompanyProperty\App\Models\Category;
use Modules\CompanyProperty\App\Models\Overview;
use Modules\CompanyProperty\App\Models\PropertyType;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\CompanyProperty\App\resources\PropertyResource;

class CompanyPropertyController extends Controller
{
    use ApiResponser, ApiHelper;

    public function index(Request $request, Company $company)
    {
        $perPage = $request->perPage ?? 10;

        $properties = $company->properties()->paginate($perPage);

        return PropertyResource::collection($properties);
    }

    public function show(Request $request, Company $company, CompanyProperty $property)
    {
        return new PropertyResource($property);
    }

    public function saveStatus(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'status' => 'required',
                'notes' => 'nullable',
            ]);

            $property = $company->properties()->updateOrCreate(
                [
                    'id' => $request->property_id
                ],
                $validatedData
            );

            return $this->successresponse(new PropertyResource($property), 'Property company logo has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }


    public function saveLogo(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            if ($request->hasFile('logo')) {
                $property = $company->createOrGetProperty($request->property_id);

                if ($property && $property->logo) {
                    Storage::delete($property->logo);
                }

                $path = $request->file('logo')->store('company/' . $company->id . '/properties/' . $property->id . '/logo');

                $property->update(['logo' => $path]);

                DB::commit();
            }

            return $this->successresponse(new PropertyResource($property), 'Property company logo has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function savePoster(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            if ($request->hasFile('poster')) {
                $property = $company->createOrGetProperty($request->property_id);

                if ($property && $property->poster) {
                    Storage::delete($property->poster);
                }

                $path = $request->file('poster')->store('company/' . $company->id . '/properties/' . $property->id . '/poster');

                $property->update(['poster' => $path]);

                DB::commit();
            }

            return $this->successresponse(new PropertyResource($property), 'Property company poster has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveValue(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'category_id' => 'required',
                'type_id' => 'required',
                'target_type_id' => 'required',
                'source_property_id' => 'nullable',
                'currency' => 'required',
                'value' => 'required',
                'name' => 'required',
            ]);

            $property = $company->properties()->updateOrCreate(
                [
                    'id' => $request->property_id
                ],
                $validatedData
            );

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property value has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveSource(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'area_sector_desctrict' => 'required',
                'source_property_id' => 'nullable',
                'building' => 'nullable',
                'unit' => 'nullable',
                'spot' => 'nullable',
            ]);

            $property = $company->properties()->updateOrCreate(
                [
                    'id' => $request->property_id
                ],
                $validatedData
            );

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property source has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveDescription(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'description' => 'required',
            ]);

            $property = $company->properties()->updateOrCreate(
                [
                    'id' => $request->property_id
                ],
                $validatedData
            );

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property description has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveOverview(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'property_id' => 'nullable',
                'overviews.*.id' => 'required|exists:overviews,id',
                'overviews.*.quantity' => 'nullable',
                'overviews.*.visible' => 'nullable',
            ]);

            $formattedData = $this->formatDataForSync($validatedData['overviews']);

            $property = $company->createOrGetProperty($request->property_id);

            $property->overviews()->sync($formattedData);

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property overview has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveDetails(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'property_id' => 'nullable',
                'details.*.id' => 'required|exists:details,id',
                'details.*.value' => 'nullable',
            ]);

            $formattedData = $this->formatDataForSync($validatedData['details']);

            $property = $company->createOrGetProperty($request->property_id);

            $property->details()->sync($formattedData);

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property details has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveFeatures(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'property_id' => 'nullable',
                'features.*' => 'required|exists:features,id',
            ]);

            $property = $company->createOrGetProperty($request->property_id);

            $property->features()->sync($validatedData['features']);

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property Features has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveAmenities(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'property_id' => 'nullable',
                'amenities.*' => 'required|exists:amenities,id',
            ]);

            $property = $company->createOrGetProperty($request->property_id);

            $property->amenities()->sync($validatedData['amenities']);

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property Amenities has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveUtilities(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'property_id' => 'nullable',
                'utilities.*' => 'required|exists:utilities,id',
            ]);

            $property = $company->createOrGetProperty($request->property_id);

            $property->utilities()->sync($validatedData['utilities']);

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property Utilities has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveUnitalityDetails(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'property_id' => 'nullable',
                'unitalities.*.field_id' => 'required|exists:unitality_fields,id',
                'unitalities.*.value' => 'nullable',
            ]);


            $formattedData = $this->formatDataForSync($validatedData['unitalities'], 'field_id');

            $property = $company->createOrGetProperty($request->property_id);

            $property->unitalities()->sync($formattedData);

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property Unitalities has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveRemark(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            // $validatedData = $request->validate([
            //     'property_id' => 'nullable',
            //     'unitalities.*.field_id' => 'required|exists:unitality_fields,id',
            //     'unitalities.*.value' => 'nullable',
            // ]);


            // $formattedData = $this->formatDataForSync($validatedData['unitalities'], 'field_id');

            // $property = $company->createOrGetProperty($request->property_id);

            // $property->unitalities()->sync($formattedData);

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property remark has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveWhatsNearby(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'property_id' => 'nullable',
                'nearbies.*.name' => 'required',
                'nearbies.*.km' => 'required',
            ]);

            $property = $company->createOrGetProperty($request->property_id);

            foreach ($validatedData['nearbies'] as $value) {
                $property->whatsNearbies()->updateOrCreate([
                    'property_id' => $request->property_id,
                    'name' => $value['name'],
                ], [
                    'km' => $value['km']
                ]);
            }

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property Whats Nearby has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function saveMapLocation(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'property_id' => 'nullable',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            $property = $company->createOrGetProperty($request->property_id);

            $property->update([
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude']
            ]);

            DB::commit();

            return $this->successresponse(new PropertyResource($property), 'Property location has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function savePlans(Request $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'property_id' => 'nullable',
                'plans.*.name' => 'required',
                'plans.*.photo' => 'required',
            ]);

            $property = $company->createOrGetProperty($request->property_id);

            foreach ($validatedData['plans'] as $data) {
                if (is_file($data['photo'])) {

                    $plan = $property->plans()->where('name', $data['name'])->whereNotNull('photo')->first();

                    if ($plan && $plan->photo) {
                        Storage::delete($plan->photo);
                    }

                    $path = $data['photo']->store('company/' . $company->id . '/properties/' . $property->id . '/plans');

                    $property->plans()->updateOrCreate([
                        'property_id' => $request->property_id,
                        'name' => $data['name'],
                    ], [
                        'photo' => $path
                    ]);

                    DB::commit();
                }
            }

            return $this->successresponse(new PropertyResource($property), 'Property plans has been updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(null, $e->getMessage());
        }
    }
}
