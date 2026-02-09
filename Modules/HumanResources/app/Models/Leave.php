<?php

namespace Modules\HumanResources\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    protected $fillable = [
        'company_id',
        'employee_id',
        'start_date',
        'end_date',
        'type',
        'status',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
