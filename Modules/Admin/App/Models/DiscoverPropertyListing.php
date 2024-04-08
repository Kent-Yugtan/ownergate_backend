<?php

namespace Modules\Admin\App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscoverPropertyListing extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'discover_property_id',
        'property_type',
        'target_type',
        'price',
        'beds_bath_kitchen',
        'size',
        'amenities',
        'features',
        'property_nearby',
        'listed_by',
        'property_by',
        'vendor'
    ];
    

}
