<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyAddressDetail extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'value',
    ];
    

}
