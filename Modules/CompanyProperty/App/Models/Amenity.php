<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CompanyProperty\Database\factories\AmenityFactory;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'amenity_type_id',
        'name',
    ];

    public function type()
    {
        return $this->belongsTo(AmenityType::class, 'amenity_type_id');
    }
}
