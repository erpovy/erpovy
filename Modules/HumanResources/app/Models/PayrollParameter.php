<?php

namespace Modules\HumanResources\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class PayrollParameter extends Model
{
    use BelongsToCompany;

    protected $table = 'hr_payroll_parameters';

    protected $fillable = [
        'company_id',
        'year',
        'name',
        'sgk_worker_rate',
        'unemployment_worker_rate',
        'sgk_employer_rate',
        'unemployment_employer_rate',
        'stamp_tax_rate',
        'income_tax_brackets',
        'min_wage_gross',
        'sgk_base_matrah',
        'sgk_ceiling_matrah',
    ];

    protected $casts = [
        'income_tax_brackets' => 'array',
        'sgk_worker_rate' => 'decimal:2',
        'unemployment_worker_rate' => 'decimal:2',
        'sgk_employer_rate' => 'decimal:2',
        'unemployment_employer_rate' => 'decimal:2',
        'stamp_tax_rate' => 'decimal:5',
        'min_wage_gross' => 'decimal:2',
        'sgk_base_matrah' => 'decimal:2',
        'sgk_ceiling_matrah' => 'decimal:2',
    ];
}
