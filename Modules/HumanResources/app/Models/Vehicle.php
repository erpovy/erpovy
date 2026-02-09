<?php

namespace Modules\HumanResources\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'company_id',
        'employee_id',
        'plate_number',
        'make',
        'model',
        'year',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(VehicleExpense::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->plate_number} - {$this->make} {$this->model}";
    }
}
