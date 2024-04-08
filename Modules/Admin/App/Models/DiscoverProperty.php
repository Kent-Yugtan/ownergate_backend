<?php

namespace Modules\Admin\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\App\Models\DiscoverPropertyListing;

class DiscoverProperty extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'city',
        'country',
        'image',
    ];
    
    public function listing()
    {
        return $this->hasOne(DiscoverPropertyListing::class);
    }
}
