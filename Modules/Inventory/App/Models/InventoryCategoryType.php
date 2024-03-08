<?php

namespace Modules\Inventory\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Database\factories\InventoryCategoryTypeFactory;

class InventoryCategoryType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): InventoryCategoryTypeFactory
    {
        //return InventoryCategoryTypeFactory::new();
    }
}
