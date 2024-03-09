<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyPlan extends Model
{
    protected $fillable = [
        'property_id',
        'name',
        'photo',
    ];
}
