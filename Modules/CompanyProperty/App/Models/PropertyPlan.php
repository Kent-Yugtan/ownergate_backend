<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\CompanyProperty\App\Models\CompanyProperty;

class PropertyPlan extends Model
{
    protected $fillable = [
        'property_id',
        'name',
        'photo',
    ];

    public function property()
    {
        return $this->belongsTo(CompanyProperty::class);
    }

}
