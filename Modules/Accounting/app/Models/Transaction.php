<?php

namespace Modules\Accounting\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'fiscal_period_id',
        'type', // regular, opening, closing
        'receipt_number',
        'date',
        'description',
        'is_approved',
    ];

    protected $casts = [
        'date' => 'date',
        'is_approved' => 'boolean',
    ];

    public function fiscalPeriod()
    {
        return $this->belongsTo(FiscalPeriod::class);
    }

    public function entries()
    {
        return $this->hasMany(LedgerEntry::class);
    }

    /**
     * Get the total debit amount for this transaction.
     */
    public function getTotalDebitAttribute()
    {
        return $this->entries()->sum('debit');
    }

    /**
     * Get the total credit amount for this transaction.
     */
    public function getTotalCreditAttribute()
    {
        return $this->entries()->sum('credit');
    }
    
    public function isBalanced(): bool
    {
        return $this->total_debit == $this->total_credit;
    }
}
