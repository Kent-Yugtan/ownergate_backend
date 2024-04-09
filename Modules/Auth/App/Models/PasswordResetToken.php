<?php

namespace Modules\Auth\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Database\factories\PasswordResetTokenFactory;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $primaryKey = 'email';
    public $incrementing = false;

    protected $fillable = [
        'email',
        'token',
        'code'
    ];
    
    protected static function newFactory(): PasswordResetTokenFactory
    {
        //return PasswordResetTokenFactory::new();
    }
}
