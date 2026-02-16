<?php

namespace Modules\HumanResources\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $table = 'hr_payroll_items';

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'gross_salary',
        'bonus',
        'overtime_pay',
        'sgk_worker_cut',
        'unemployment_worker_cut',
        'income_tax_base',
        'cumulative_income_tax_base',
        'calculated_income_tax',
        'calculated_stamp_tax',
        'income_tax_exemption',
        'stamp_tax_exemption',
        'net_salary',
        'other_deductions',
        'final_net_paid',
        'sgk_employer_cut',
        'unemployment_employer_cut',
        'total_employer_cost',
    ];

    protected $casts = [
        'gross_salary' => 'decimal:2',
        'bonus' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'sgk_worker_cut' => 'decimal:2',
        'unemployment_worker_cut' => 'decimal:2',
        'income_tax_base' => 'decimal:2',
        'cumulative_income_tax_base' => 'decimal:2',
        'calculated_income_tax' => 'decimal:2',
        'calculated_stamp_tax' => 'decimal:2',
        'income_tax_exemption' => 'decimal:2',
        'stamp_tax_exemption' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'final_net_paid' => 'decimal:2',
        'sgk_employer_cut' => 'decimal:2',
        'unemployment_employer_cut' => 'decimal:2',
        'total_employer_cost' => 'decimal:2',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
