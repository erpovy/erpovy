<?php

namespace Modules\ServiceManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class Vehicle extends Model
{
    use HasFactory, BelongsToCompany;

    protected $table = 'sm_vehicles';

    protected $fillable = [
        'company_id',
        'customer_id', // Added
        'plate_number',
        'brand',
        'model',
        'year',
        'vin',
        'chassis_number', // Added
        'color',
        'current_mileage',
        'status',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(\Modules\CRM\Models\Contact::class, 'customer_id');
    }

    public function jobCards()
    {
        return $this->hasMany(JobCard::class);
    }

    public function serviceRecords()
    {
        return $this->hasMany(ServiceRecord::class);
    }

    public function getNextServiceRecordAttribute()
    {
        return $this->serviceRecords()
            ->where(function($q) {
                $q->whereNotNull('next_planned_date')
                  ->orWhereNotNull('next_planned_mileage');
            })
            ->where('status', 'completed')
            ->latest('service_date')
            ->first();
    }

    public function getMaintenanceStatusAttribute()
    {
        $next = $this->next_service_record;
        
        if (!$next) return 'unknown';

        $isOverdue = false;
        $isUpcoming = false;

        if ($next->next_planned_date) {
            if ($next->next_planned_date->isPast()) {
                $isOverdue = true;
            } elseif ($next->next_planned_date->diffInDays(now()) <= 15) {
                $isUpcoming = true;
            }
        }

        if ($next->next_planned_mileage) {
            if ($this->current_mileage >= $next->next_planned_mileage) {
                $isOverdue = true;
            } elseif ($next->next_planned_mileage - $this->current_mileage <= 1000) {
                $isUpcoming = true;
            }
        }

        if ($isOverdue) return 'overdue';
        if ($isUpcoming) return 'upcoming';
        
        return 'healthy';
    }
}
