<?php

namespace Modules\CompanyGallery\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CompanyGallery\Database\factories\GalleryFactory;
use Modules\CompanyProperty\App\Models\CompanyProperty;

class Gallery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['property_id','assigned_to','maintained_by','company_id'];
    
    protected static function newFactory(): GalleryFactory
    {
        //return GalleryFactory::new();
    }

    public function companyProperty()
    {
        return $this->belongsTo(CompanyProperty::class, 'property_id', 'og_code');
    }
}
