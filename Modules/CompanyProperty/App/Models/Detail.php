<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CompanyProperty\Database\factories\DetailFactory;

class Detail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): DetailFactory
    {
        //return DetailFactory::new();
    }
}
