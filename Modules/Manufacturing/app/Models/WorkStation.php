<?php

namespace Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class WorkStation extends Model
{
    use HasFactory, BelongsToCompany;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'code',
        'name',
        'type',
        'status',
        'hourly_rate',
        'capacity',
        'location',
    ];
}
