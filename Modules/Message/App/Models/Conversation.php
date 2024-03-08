<?php

namespace Modules\Message\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Message\Database\factories\ConversationFactory;

class Conversation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): ConversationFactory
    {
        //return ConversationFactory::new();
    }
}
