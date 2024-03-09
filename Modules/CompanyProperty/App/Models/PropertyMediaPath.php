<?php

namespace Modules\CompanyProperty\App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyMediaPath extends Model
{
    protected $fillable = [
        'media_id',
        'name',
        'path',
    ];
    

}
