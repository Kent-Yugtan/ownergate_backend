<?php

namespace Modules\MainScreen\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\App\Models\Inventory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CompanyPrivacy\App\Models\Section;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\MainScreen\Database\factories\MainScreenFactory;

class MainScreen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'main_screen';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'banner',
        'logo',
        'title'
    ];
    
    protected static function newFactory(): MainScreenFactory
    {
        //return MainScreenFactory::new();
    }

    public function privacies()
    {
        return $this->belongsToMany(Section::class, 'main_screen_privacies', 'main_screen_id', 'section_id')->withTimestamps();
    }

    // public function ads()
    // {
    //     return $this->belongsToMany(Inventory::class, 'main_screen_ads', 'main_screen_id', 'ads_id')->withTimestamps();
    // }

    public function ads()
    {
        return $this->hasMany(MainScreenAds::class, 'main_screen_id');
    }
}
