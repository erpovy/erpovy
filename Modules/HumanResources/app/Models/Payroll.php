<?php

namespace Modules\HumanResources\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class Payroll extends Model
{
    use BelongsToCompany;

    protected $table = 'hr_payrolls';

    protected $fillable = [
        'company_id',
        'month',
        'year',
        'description',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(PayrollItem::class);
    }
}
