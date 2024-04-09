<?php

namespace Modules\MainScreen\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
}
