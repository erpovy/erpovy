<?php

namespace Modules\Logistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    protected $fillable = [
        'company_id',
        'vehicle_id',
        'name',
        'planned_date',
        'status',
        'stops',
        'total_distance',
        'estimated_duration',
    ];

    protected $casts = [
        'planned_date' => 'date',
        'stops' => 'json',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

