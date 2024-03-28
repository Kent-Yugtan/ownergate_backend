<?php

namespace Modules\CompanyEmployee\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CompanyEmployee\Database\factories\EmployeePropertyFactory;
use Modules\Company\App\Models\Company;
use Modules\Auth\Entities\UserProfile;
use Modules\CompanyProperty\App\Models\CompanyProperty;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyEmployee extends Model
{
    use HasFactory;

    protected $casts = [
        'official_contract' => 'array',
        'company_contract' => 'array',
        'more_details' => 'array',
        'password_period' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'admin_id',
        'company_id',
        'user_id',
        'profile_id',
        'status',
        'type',
        'department',
        'building_name',
        'unit_number',
        'join_date',
        'position',
        'salary',
        'mobile_number_1',
        'emergency_contact_name',
        'emergency_contact_number',
        'official_contract',
        'company_contract',
        'more_details',
        'password_period',
        'street',
        'employee_number',
        'permission_type',
    ];

    public function properties()
    {
        return $this->belongsToMany(CompanyProperty::class, 'employee_properties', 'employee_id', 'property_id')->withPivot('access_code');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(EmployeeAttachment::class, "employee_id");
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, "company_id");
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, "admin_id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function profile()
    {
        return $this->belongsTo(UserProfile::class, 'profile_id');
    }
}
