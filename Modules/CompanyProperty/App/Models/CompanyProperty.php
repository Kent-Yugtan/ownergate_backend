<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Company\App\Models\Company;
use Modules\CompanyPrivacy\App\Models\Section;
use Modules\CompanyProperty\App\Models\Detail;
use Modules\CompanyProperty\App\Models\Amenity;
use Modules\CompanyProperty\App\Models\Feature;
use Modules\CompanyProperty\App\Models\Utility;
use Modules\CompanyProperty\App\Models\Category;
use Modules\CompanyProperty\App\Models\Overview;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\CompanyProperty\App\Models\PropertyPlan;
use Modules\CompanyProperty\App\Models\PropertyType;
use Modules\CompanyProperty\App\Models\PropertyMedia;
use Modules\CompanyProperty\App\Models\UnitalityField;
use Modules\CompanyEmployee\App\Models\CompanyEmployee;
use Modules\CompanyProperty\App\Models\CategoryTargetType;
use Modules\CompanyProperty\App\Models\PropertyWhatsNearby;
use Modules\CompanyProperty\App\Models\PropertyAddressDetail;
use Modules\CompanyProperty\App\Models\PropertyAdditionalRemark;

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
        return $this->belongsTo(Company::class)->withTrashed();
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
        return $this->belongsToMany(Amenity::class, 'property_amenities', 'property_id', 'amenity_id')->withTimestamps()->with('type');
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

    public function addressDetails()
    {
        return $this->hasMany(PropertyAddressDetail::class, 'property_id');
    }

    public function plans()
    {
        return $this->hasMany(PropertyPlan::class, 'property_id');
    }

    public function remark()
    {
        return $this->hasOne(PropertyAdditionalRemark::class, 'property_id');
    }

    public function medias()
    {
        return $this->hasMany(PropertyMedia::class, 'property_id');
    }

    public function address()
    {
        return $this->hasMany(PropertyLocation::class, 'property_id');
    }

    public function employee()
    {
        return $this->belongsToMany(CompanyEmployee::class, 'employee_properties', 'employee_id', 'property_id')->withPivot('access_code');
    }

    public function privacies()
    {
        return $this->belongsToMany(Section::class, 'property_privacies', 'property_id', 'section_id')->withTimestamps();
    }

    public function scopeFilterPropertyType($query, $value)
    {
        return $query->when($value, function ($query) use ($value) {
            return $query->whereHas('propertyType', function ($query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%');
            });
        });
    }

    public function scopeFilterTargetType($query, $value)
    {
        return $query->when($value, function ($query) use ($value) {
            return $query->whereHas('targetType', function ($query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%');
            });
        });
    }

    public function scopeSearchKeyword($q, $search)
    {
        return $q->where(function ($q) use ($search) {
            $q->orWhere('name', 'like', $search . '%')
            ->orWhere('description', 'like', $search . '%')
            ->orWhere(function ($q) use ($search) {
                $q->whereHas('company', function ($q) use ($search) {
                    $q->where('company_name', 'like', $search . '%');
                });
            });
        });
    }

    public function scopeSearchPrice($q, $price)
    {
        return $q->whereBetween('value', $price);
    }

    public function scopeCountry($q, $country)
    {
        return $q->where('country', 'LIKE', $country);
    }

    public function scopeCity($q, $city)
    {
        return $q->where('city', 'LIKE', $city);
    }

    public function scopeState($q, $state)
    {
        return $q->where('state', 'LIKE', $state);
    }

    public function scopeCategory($q, $category)
    {
        return $q->where('category_id', $category);
    }

    public function scopeFilterAmenity($query, $value)
    {
        return $query->when($value, function ($query) use ($value) {
            return $query->whereHas('amenities', function ($querty) use ($value) {
                $querty->where('name', 'like', '%' . $value . '%');
            });
        });
    }

    public function scopeFilterFeature($query, $value)
    {
        return $query->when($value, function ($query) use ($value) {
            return $query->whereHas('features', function ($querty) use ($value) {
                $querty->where('name', 'like', '%' . $value . '%');
            });
        });
    }

    public function scopeFilterPropertyBy($query, $value)
    {
        return $query->when($value, function ($query) use ($value) {
            return $query->whereHas('company', function ($querty) use ($value) {
                $querty->where('company_name', 'like', '%' . $value . '%');
            });
        });
    }

    public function scopeFilterSize($query, $value)
    {
        return $query->when($value, function ($query) use ($value) {
            $query->whereHas('details', function ($subQuery) use ($value) {
                $subQuery->where('value', 'like', '%' . $value . '%')
                    ->where('detail_id', 5);
            });
        });
    }

    public function scopeFilterBBK($query, $beds, $baths, $kitchens)
    {
        return $query->where(function ($subQuery) use ($beds, $baths, $kitchens) {
            if ($beds) {
                $subQuery->whereHas('details', function ($detailsQuery) use ($beds) {
                    $detailsQuery->where('value', $beds)
                                 ->where('detail_id', 11);
                });
            }
            
            if ($baths) {
                $subQuery->orWhereHas('details', function ($detailsQuery) use ($baths) {
                    $detailsQuery->where('value', $baths)
                                 ->where('detail_id', 14);
                });
            }
    
            // if ($kitchens) {
            //     $subQuery->orWhereHas('details', function ($detailsQuery) use ($kitchens) {
            //         $detailsQuery->where('value', $kitchens)
            //                      ->where('detail_id', XX); // Adjust XX with the correct detail_id for kitchens
            //     });
            // }
        });
    }
    

    public static function search($search)
    {
        return self::when($search->type, function ($q) use ($search) {
            //villa, flat, etc.
            $q->filterPropertyType($search->type);
        })->when($search->target_type, function ($q) use ($search) {
            //sale, rent ...
            $q->filterTargetType($search->target_type);
        })->when($search->keyword, function ($q) use ($search) {
            //name, description, company name
            $q->searchKeyword($search->keyword);
        })->when($search->minPrice && $search->maxPrice, function ($q) use ($search) {
            // [1000, 10000]
            $q->searchPrice([$search->minPrice, $search->maxPrice]);
        })->when($search->sort, function ($q) use ($search) {
            // ['price', 'desc'] || ['price', 'desc']
            // ['name', 'asc'] || ['name', 'desc']
            // ['created_at', 'asc'] || ['created_at', 'desc']
            $q->orderBy($search->sort[0], $search->sort[1]);
        })->when($search->country, function ($q) use ($search) {
            $q->country($search->country);
        })->when($search->state, function ($q) use ($search) {
            $q->state($search->state);
        })->when($search->city, function ($q) use ($search) {
            $q->city($search->city);
        })->when($search->category, function ($q) use ($search) {
            $q->category($search->category);
        })->when($search->amenities, function ($q) use ($search) {
            $q->filterAmenity($search->amenities);
        })->when($search->features, function ($q) use ($search) {
            $q->filterFeature($search->features);
        })->when($search->property_by, function ($q) use ($search) {
            $q->filterPropertyBy($search->property_by);
        })->when($search->size, function ($q) use ($search) {
            $q->filterSize($search->size);
        })->when($search->beds || $search->baths || $search->kitchens, function ($q) use ($search) {
            $q->filterBBK($search->beds, $search->baths, $search->kitchens);
        });
    }
}
