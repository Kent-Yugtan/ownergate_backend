<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CompanyProperty\Database\factories\PropertySelectedColumnFactory;

use Modules\CompanyProperty\App\Models\CompanyProperty;
use Modules\Companyproperty\app\Models\PropertyAvailableSetting;

class PropertySelectedSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['property_id', 'selected_settings_id'];

    public function property(){
        $this->belongsTo(CompanyProperty::class, 'id');
    }

    public function available_settings(){
        $this->belongsTo(PropertyAvailableSetting::class, 'id');
    }
}
