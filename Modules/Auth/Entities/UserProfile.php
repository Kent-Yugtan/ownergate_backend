<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Database\factories\UserProfileFactory;

class UserProfile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'fax',
        'gender',
        'dob',
        'nationality',
        'national_id_no',
        'license_no',
        'license_expiry',
        'postal_code',
        'addmail',
        'account_no',
        'country',
        'state',
        'city',
        'address',
        'description',
        'avatar',
        'cover_photo',
        'latitude',
        'longitude',
        'social_id',
        'social_type',
        'website',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    protected static function newFactory(): UserProfileFactory
    {
        //return UserProfileFactory::new();
    }
}
