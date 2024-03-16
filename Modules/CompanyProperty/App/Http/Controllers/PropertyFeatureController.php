<?php

namespace Modules\CompanyProperty\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\CompanyProperty\App\Models\Detail;
use Modules\CompanyProperty\App\Models\Amenity;
use Modules\CompanyProperty\App\Models\Feature;
use Modules\CompanyProperty\App\Models\Utility;
use Modules\CompanyProperty\App\Models\Category;
use Modules\CompanyProperty\App\Models\Overview;
use Modules\CompanyProperty\App\Models\PropertyType;

class PropertyFeatureController extends Controller
{
    public function getOverviews()
    {
        return Overview::get();
    }

    public function getFeatures()
    {
        return Feature::get();
    }

    public function getDetails()
    {
        return Detail::get();
    }

    public function getAmenities()
    {
        return Amenity::with('type')->get();
    }

    public function getUtilities()
    {
        return Utility::get();
    }

    public function getCategories()
    {
        // Fetch CategoryTypes
        $categoryTypes = Category::with('targets')->get();

        // Fetch PropertyTypes
        $propertyTypes = PropertyType::all();
        // Map CategoryType data
        return $categoryTypes->map(function ($category) use ($propertyTypes) {
            return [
                'category_id'    => $category->id,
                'category' => $category->name,
                'targets' => $category->targets->map(function ($type) use ($category) {
                    return [
                        'target_type_id' => $type->id,
                        'name' => $category->name . ' ' . $type->name
                    ];
                }),
                'property_types' => $propertyTypes->map(function ($propertyType) {
                    return [
                        'type_id' => $propertyType->id,
                        'name' => $propertyType->name
                    ];
                })->toArray()
            ];
        });
    }
}
