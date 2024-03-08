<?php

namespace Modules\Company\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Database\factories\CompanyAttachmentFactory;

class CompanyAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): CompanyAttachmentFactory
    {
        //return CompanyAttachmentFactory::new();
    }
}
