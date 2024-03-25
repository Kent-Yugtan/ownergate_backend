<?php

namespace Modules\Inventory\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\App\Models\InventoryType;
use Modules\Inventory\App\Models\InventoryAttachment;

class Inventory extends Model
{
    protected $fillable = [
        'user_id',
        'inventory_category_id',
        'inventory_type_id',
        'item_id',
        'description_name',
        'level',
        'full_description',
        'country',
        'city',
        'area',
        'discount',
        'current_date',
        'account_id',
        'opening_balance',
        'vendor_id',
        'start_date',
        'end_date',
        'item_id_details',
        'is_active',
    ];
    
    protected $hidden = ['created_at', 'updated_at'];

    public function attachments()
    {
        return $this->hasMany(InventoryAttachment::class);
    }

    public function type()
    {
        return $this->belongsTo(InventoryType::class, 'inventory_type_id');
    }
}
