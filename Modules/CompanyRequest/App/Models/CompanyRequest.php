<?php

namespace Modules\CompanyRequest\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CompanyProperty\App\Models\CompanyProperty;

class CompanyRequest extends Model
{

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'employee_id',
        'property_id',
        'request_id_code',
        'request_name',
        'request_number',
        'status',
        'commission_percentage',
        'commission_notes',
        'category',
        'request_date',
        'request_time_from',
        'request_time_to',
        'action_date',
        'note'
    ];

    public function property()
    {
        return $this->belongsTo(CompanyProperty::class, "property_id");
    }
}
