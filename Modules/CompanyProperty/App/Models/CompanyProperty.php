<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Company\App\Models\Company;
use Modules\CompanyProperty\App\Models\Detail;
use Modules\CompanyProperty\App\Models\Amenity;
use Modules\CompanyProperty\App\Models\Feature;
use Modules\CompanyProperty\App\Models\Utility;
use Modules\CompanyProperty\App\Models\Category;
use Modules\CompanyProperty\App\Models\Overview;
use Modules\CompanyProperty\App\Models\PropertyPlan;
use Modules\CompanyProperty\App\Models\PropertyType;
use Modules\CompanyProperty\App\Models\PropertyMedia;
use Modules\CompanyProperty\App\Models\UnitalityField;
use Modules\CompanyProperty\App\Models\CategoryTargetType;
use Modules\CompanyProperty\App\Models\PropertyWhatsNearby;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompanyProperty extends Model
{

    protected $fillable = [
        'company_id',
        'category_id',
        'type_id',
        'target_type_id',
        'source_property_id',
        'name',
        'logo',
        'poster',
        'currency',
        'value',
        'country',
        'state',
        'city',
        'area_sector_desctrict',
        'latitude',
        'longitude',
        'description',
        'full_video',
        'status',
        'notes',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function targetType()
    {
        return $this->belongsTo(CategoryTargetType::class);
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class, 'type_id');
    }

    public function overviews()
    {
        return $this->belongsToMany(Overview::class, 'property_overviews', 'property_id', 'overview_id')->withPivot(['quantity', 'visible'])->withTimestamps();
    }

    public function details()
    {
        return $this->belongsToMany(Detail::class, 'property_details', 'property_id', 'detail_id')->withPivot('value')->withTimestamps();
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'property_features', 'property_id', 'feature_id')->withTimestamps();
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities', 'property_id', 'amenity_id')->withTimestamps();
    }

    public function utilities()
    {
        return $this->belongsToMany(Utility::class, 'property_utilities', 'property_id', 'utility_id')->withTimestamps();
    }

    public function unitalities()
    {
        return $this->belongsToMany(UnitalityField::class, 'property_unitalities', 'property_id', 'field_id')->withTimestamps();
    }

    public function whatsNearbies()
    {
        return $this->hasMany(PropertyWhatsNearby::class, 'property_id');
    }

    public function plans()
    {
        return $this->hasMany(PropertyPlan::class, 'property_id');
    }

    public function media()
    {
        return $this->hasMany(PropertyMedia::class, 'property_id');
    }

    public function address()
    {
        return $this->hasMany(PropertyLocation::class, 'property_id');
    }
}
