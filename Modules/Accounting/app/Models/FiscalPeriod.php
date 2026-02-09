<?php

namespace Modules\Accounting\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FiscalPeriod extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'start_date',
        'end_date',
        'status', // open, closed, locked
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
