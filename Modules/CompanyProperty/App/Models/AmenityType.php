<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CompanyProperty\Database\factories\AmenityTypeFactory;

class AmenityType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function amenities(){
        return $this->hasMany(Amenity::class);
    }
}
