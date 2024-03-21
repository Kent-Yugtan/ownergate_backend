<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Modules\Agent\Entities\Agent;
use Modules\Owner\Entities\Owner;
use Laravel\Passport\HasApiTokens;
use Modules\Vendor\Entities\Vendor;
use Modules\Auth\Entities\UserProfile;
use Spatie\Permission\Traits\HasRoles;
use Modules\Company\App\Models\Company;
use Modules\Customer\Entities\Customer;
use Illuminate\Notifications\Notifiable;
use Modules\Shortcut\App\Models\Shortcut;
use Modules\Inventory\App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;
use Modules\Customer\Entities\CustomerDocument;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property int $id
 * @property int $user_type_id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property UserType $user_type
 * @property Collection|Property[] $properties
 * @property Collection|PropertyDocument[] $property_documents
 * @property Collection|PropertyPost[] $property_posts
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'users';
    protected $guard_name = 'api';
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'og_code',
        'role_id',
        'username',
        'email',
        'name',
        'email_verified_at',
        'password',
        'remember_token',
        'verification_token',
        'verification_date',
        'verification_code',
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'owner_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function UserType()
    {
        return $this->belongsTo(UserType::class);
    }


    public function Vendors()
    {
        return $this->hasMany(Vendor::class);
    }

    public function agents()
    {
        return $this->hasMany(Agent::class, 'owner_id');
    }

    public function CustomerDocuments()
    {
        return $this->hasMany(CustomerDocument::class, 'customer_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'user_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_id');
    }

    public function owners()
    {
        return $this->hasMany(Owner::class, 'user_id');
    }

    public function getPropertiesIdsAttribute()
    {
        return $this->properties->pluck('id');
    }

    public function Shortcuts()
    {
        return $this->hasMany(Shortcut::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function userCompanies()
    {
        return $this->belongsToMany(Company::class, 'company_users', 'user_id', 'company_id')->withPivot('is_admin');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'user_id');
    }


}
