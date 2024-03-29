<?php

namespace Modules\Inventory\App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryType extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'item_name'
    ];
    

}
