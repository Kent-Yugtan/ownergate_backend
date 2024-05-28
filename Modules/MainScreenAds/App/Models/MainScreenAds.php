<?php

namespace Modules\MainScreenAds\App\Models;

use Illuminate\Database\Eloquent\Model;

class MainScreenAds extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'main_screen_id',
        'ads_id',
        'type',
        'status'
    ];
    
  
}
