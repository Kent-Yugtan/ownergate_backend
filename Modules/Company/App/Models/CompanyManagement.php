<?php

namespace Modules\Company\App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyManagement extends Model
{
    protected $table = 'company_managements';
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'name',
        'position',
        'image_path',
        'visibility',
        'phone_number'
    ];

    protected $hidden = ['created_at', 'updated_at'];
    

}
