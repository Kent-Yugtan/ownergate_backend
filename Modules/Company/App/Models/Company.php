<?php

namespace Modules\Company\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Company\App\Models\CompanyNews;
use Modules\Company\App\Models\CompanyService;
use Modules\Company\App\Models\CompanyLocation;
use Modules\Company\App\Models\CompanyAttachment;
use Modules\Company\App\Models\CompanyManagement;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use App\Models\User;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'owner_id',
        'addmail',
        'company_name',
        'status',
        'profile_picture',
        'profile_poster',
        'about',
        'notes',
        'mission',
        'vission',
        'values',
        'website',
        'whatsapp_url',
        'instagram_url',
        'facebook_url',
        'twitter_url',
        'youtube_url',
        'wechat_url',
        'telegram_url',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
    

    public function createOrGetProperty($property_id) : CompanyProperty
    {
        $property = $this->properties()->where('id', $property_id)->first();
        
        if (! $property) {
            $property = $this->properties()->create();
        }

        return $property;
    }

    public function properties()
    {
        return $this->hasMany(CompanyProperty::class);
    }

    public function managements()
    {
        return $this->hasMany(CompanyManagement::class, 'company_id');
    }

    public function news()
    {
        return $this->hasMany(CompanyNews::class);
    }

    public function services()
    {
        return $this->hasMany(CompanyService::class);
    }

    public function locations()
    {
        return $this->hasMany(CompanyLocation::class);
    }

    public function attachments()
    {
        return $this->hasMany(CompanyAttachment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users', 'company_id', 'user_id')->withPivot('is_admin');
    }
}
