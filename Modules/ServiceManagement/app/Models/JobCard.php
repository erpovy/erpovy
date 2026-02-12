<?php

namespace Modules\ServiceManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class JobCard extends Model
{
    use HasFactory, BelongsToCompany;

    protected $table = 'sm_job_cards';

    protected $fillable = [
        'company_id',
        'vehicle_id',
        'customer_id',
        'job_number',
        'status',
        'priority',
        'entry_date',
        'expected_completion_date',
        'actual_completion_date',
        'customer_complaint',
        'diagnosis',
        'internal_notes',
        'odometer_reading',
        'fuel_level',
        'total_parts',
        'total_labor',
        'total_amount',
    ];

    protected $casts = [
        'entry_date' => 'datetime',
        'expected_completion_date' => 'datetime',
        'actual_completion_date' => 'datetime',
        'fuel_level' => 'decimal:2',
        'total_parts' => 'decimal:2',
        'total_labor' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer()
    {
        return $this->belongsTo(\Modules\CRM\Models\Contact::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(JobCardItem::class);
    }

    // Helper to recalculate totals
    public function recalculateTotals()
    {
        $parts = $this->items()->where('type', 'part')->sum('total_price');
        $labor = $this->items()->where('type', '!=', 'part')->sum('total_price');

        $this->update([
            'total_parts' => $parts,
            'total_labor' => $labor,
            'total_amount' => $parts + $labor,
        ]);
    }
}
