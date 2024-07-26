<?php

namespace Modules\Company\App\Models;

use App\Models\User;
use App\Traits\ApiHelper;
use Illuminate\Database\Eloquent\Model;
use Modules\Company\App\Models\CompanyNews;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Company\App\Models\CompanyService;
use Modules\CompanyPrivacy\App\Models\Section;
use Modules\Company\App\Models\CompanyLocation;
use Modules\Company\App\Models\CompanyAttachment;
use Modules\Company\App\Models\CompanyManagement;
use Modules\CompanyPrivacy\App\Models\CompanyPrivacy;
use Modules\CompanyRequest\App\Models\CompanyRequest;
use Modules\CompanyProperty\App\Models\CompanyProperty;

class Company extends Model
{
    use SoftDeletes, ApiHelper;

    protected $dates = ['deleted_at'];

    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'owner_id',
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
        'mission_visibility',
        'vission_visibility',
        'values_visibility',
        'note'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
    

    public function createOrGetProperty($property_id) : CompanyProperty
    {
        if ($property_id) {
            $property = $this->properties()->where('id', $property_id)->first();

            if (!$property) {
                abort(403, 'Unauthorized action.');
            }
        } else {
            $property = $this->properties()->create();

            $this->updateOgCode($property);
            
            $sections = Section::where('module_name', 'Property')->pluck('id')->toArray();
            $property->privacies()->syncWithoutDetaching($sections);
        }

        return $property;
    }

    public function properties()
    {
        return $this->hasMany(CompanyProperty::class);
    }

    public function companyRequests()
    {
        return $this->hasMany(CompanyRequest::class);
    }

    public function getPropertiesIdsAttribute()
    {
        return $this->properties->pluck('id');
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
        return $this->hasMany(CompanyAttachment::class, 'company_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users', 'company_id', 'user_id')->withPivot('is_admin');
    }

    public function privacies()
    {
        return $this->belongsToMany(Section::class, 'company_privacies', 'company_id', 'section_id');
    }

    public function updateOgCode($property): void
    {
        $prefix = 'OG';
        $uniqueCode = $this->generateUniqueCode($prefix, 6);

        while (CompanyProperty::where('og_code', $uniqueCode)->exists()) {
            $uniqueCode = $this->generateUniqueCode($prefix, 6);
        }

        $property->update(['og_code' => $uniqueCode]);
    }
}
