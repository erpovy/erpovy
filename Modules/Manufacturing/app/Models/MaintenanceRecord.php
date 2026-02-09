<?php

namespace Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class MaintenanceRecord extends Model
{
    use HasFactory, BelongsToCompany;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'work_station_id',
        'title',
        'type',
        'status',
        'priority',
        'start_date',
        'end_date',
        'cost',
        'technician_name',
        'description',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function workStation()
    {
        return $this->belongsTo(WorkStation::class);
    }
}
