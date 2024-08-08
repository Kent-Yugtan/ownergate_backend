<?php

namespace Modules\CompanyGallery\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CompanyGallery\Database\factories\GalleryFactory;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\Auth\Entities\UserProfile;
use App\Models\User;

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
        return $this->belongsTo(CompanyProperty::class, 'property_id', 'id');
    }

    public function getAssignTo()
    {
        return $this->belongsTo(UserProfile::class, 'assigned_to', 'user_id');
    }

    public function getMaintainBy()
    {
        return $this->belongsTo(UserProfile::class, 'maintained_by', 'user_id');
    }

    
    public function getSearchAssignTo()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function getSearchMaintainBy()
    {
        return $this->belongsTo(User::class, 'maintained_by', 'id');
    }
}
