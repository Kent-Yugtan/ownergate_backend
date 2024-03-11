<?php

namespace Modules\Inventory\App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAttachment extends Model
{
    protected $fillable = [
        'inventory_id',
        'title',
        'path',
    ];
}
