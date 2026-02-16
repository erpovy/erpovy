<?php

namespace Modules\HumanResources\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Overtime extends Model
{
    protected $table = 'hr_overtimes';

    protected $fillable = [
        'company_id',
        'employee_id',
        'date',
        'hours',
        'rate_multiplier',
        'description',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:2',
        'rate_multiplier' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
