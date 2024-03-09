<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\CompanyProperty\App\Models\CategoryTargetType;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];
    
    public function targets()
    {
        return $this->hasMany(CategoryTargetType::class, "category_id");
    }

}
