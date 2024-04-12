<?php

namespace Modules\PropertyPrivacy\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PropertyPrivacy\Database\factories\PropertyPrivacyFactory;

class PropertyPrivacy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'property_id',
        'section_id',
    ];
    
    protected static function newFactory(): PropertyPrivacyFactory
    {
        //return PropertyPrivacyFactory::new();
    }
}
