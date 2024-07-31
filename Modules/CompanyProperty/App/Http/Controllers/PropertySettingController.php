<?php

namespace Modules\CompanyProperty\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiHelper;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Company\App\Models\Company;
use Modules\CompanyProperty\App\Models\PropertySelectedSetting;
use Modules\CompanyProperty\App\Models\PropertyAvailableSetting;
use Modules\CompanyProperty\app\Models\CompanyProperty;

use Modules\companyProperty\Transformers\AvailableSettingResource;
use Modules\companyProperty\Transformers\SelectedSettingResource;




class PropertySettingController extends Controller
{
    use ApiHelper, ApiResponser;

    public function getAvailiableSettings() {

        try {

            $avaliable_settings = PropertyAvailableSetting::all()->sortBy('name');
            return $this->successResponse(AvailableSettingResource::collection($avaliable_settings), 'Available settings have been retrieved');

        } catch(Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }

    }

    public function getPropertySettings(Request $request, CompanyProperty $property){

        try {

            $property_id = $property->id;
            $property = $property::with(['selected_settings', 'available_settings'])->find($property_id);


            $selected_settings = $property->selected_settings;

            $property->settings = PropertyAvailableSetting::all()->reject(function($setting) use ($selected_settings) {
                return $selected_settings->contains('selected_settings_id', $setting['id']);
            })->sortBy('name');

            return $this->successResponse(new SelectedSettingResource($property), 'Property settings have been retrieved');

        } catch(Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }
    }

    public function savePropertySettings(Request $request, CompanyProperty $property){

        $rules = [
            'settings' => 'required|array',
            'settings.*' => 'required|integer',
        ];

        try {

            $this->validate($request, $rules);

            $settings_ids = $request->settings;
            $property_id = $property->id;

            $data = [];
            foreach($settings_ids as $id){
                $data [] = ['property_id' => $property_id, "selected_settings_id" => $id];
            }

            $property->selected_settings()->delete();
            PropertySelectedSetting::insert($data);

            $property = CompanyProperty::with(['selected_settings', 'available_settings'])->find($property_id);
            $selected_settings = $property->selected_settings;

            $property->settings = PropertyAvailableSetting::all()->reject(function($setting) use ($selected_settings) {
                return $selected_settings->contains('selected_settings_id', $setting['id']);
            })->sortBy('name');

            return $this->successResponse(new SelectedSettingResource($property), 'Property settings have been saved');

        } catch (Exception $e) {
            return $this->errorResponse(null, $e->getMessage());
        }

    }
}
