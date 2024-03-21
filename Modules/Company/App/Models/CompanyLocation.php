<?php

namespace Modules\Company\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Database\factories\CompanyLocationFactory;

class CompanyLocation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'office_name',
        'address',
        'latitude',
        'longitude',
        'is_default',
        'visibility',

    ];

    protected $hidden = ['created_at', 'updated_at'];
}
