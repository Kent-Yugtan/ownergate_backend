<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyMedia extends Model
{
    protected $fillable = [
        'property_id',
        'name',
        'type',
        'description',
        'area',
    ];

    public function paths()
    {
        return $this->hasMany(PropertyMediaPath::class, 'media_id');
    }

    public function property()
    {
        return $this->belongsTo(CompanyProperty::class, 'property_id');
    }
    

}
