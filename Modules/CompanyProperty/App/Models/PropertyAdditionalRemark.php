<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyAdditionalRemark extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'remark',
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
