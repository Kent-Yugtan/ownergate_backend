<?php

namespace Modules\MainScreenAds\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\App\Models\Inventory;

class MainScreenAds extends Model
{
    protected $table = 'main_screen_ads';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'main_screen_id',
        'ads_id',
        'type',
        'status'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'ads_id');
    }
    

}
