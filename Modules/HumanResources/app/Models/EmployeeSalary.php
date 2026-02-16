<?php

namespace Modules\HumanResources\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeSalary extends Model
{
    use HasFactory;

    protected $table = 'hr_salaries';

    protected $fillable = [
        'employee_id',
        'amount',
        'start_date',
        'end_date',
        'type',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
