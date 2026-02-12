<?php

namespace Modules\ServiceManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class ServiceRecord extends Model
{
    use HasFactory, BelongsToCompany;

    protected $table = 'sm_service_records';

    protected $fillable = [
        'company_id',
        'vehicle_id',
        'service_type',
        'service_date',
        'mileage_at_service',
        'description',
        'total_cost',
        'performed_by',
        'status',
        'next_planned_date',
        'next_planned_mileage',
        'completed_at',
    ];

    protected $casts = [
        'service_date' => 'date',
        'next_planned_date' => 'date',
        'total_cost' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
