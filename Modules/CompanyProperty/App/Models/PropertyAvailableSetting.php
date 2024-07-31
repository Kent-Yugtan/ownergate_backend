<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CompanyProperty\App\Models\PropertySelectedSetting;

class PropertyAvailableSetting extends Model
{
    use HasFactory;

    protected $table = 'property_available_settings';

    protected $fillable = ['name'];

    public function selected_settings(){
        $this->hasMany(PropertySelectedSetting::class, 'selected_settings_id');
    }
}
