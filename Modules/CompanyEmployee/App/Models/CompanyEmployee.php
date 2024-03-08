<?php

namespace Modules\CompanyEmployee\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CompanyEmployee\Database\factories\CompanyEmployeeFactory;

class CompanyEmployee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): CompanyEmployeeFactory
    {
        //return CompanyEmployeeFactory::new();
    }
}
