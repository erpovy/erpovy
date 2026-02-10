<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\CRM\Models\Contact;
use App\Traits\BelongsToCompany;

class CashBankTransaction extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'cash_bank_account_id',
        'transaction_id',
        'contact_id',
        'type',
        'method',
        'amount',
        'transaction_date',
        'description',
        'reference_number',
        'target_account_id',
        'balance_after',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    // Relations
    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(CashBankAccount::class, 'cash_bank_account_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function accountingTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function targetAccount(): BelongsTo
    {
        return $this->belongsTo(CashBankAccount::class, 'target_account_id');
    }

    // Attributes
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'income' => 'Gelir',
            'expense' => 'Gider',
            'transfer' => 'Virman',
            default => $this->type,
        };
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'cash' => 'Nakit',
            'transfer' => 'Havale/EFT',
            'credit_card' => 'Kredi Kartı',
            'check' => 'Çek',
            'other' => 'Diğer',
            default => $this->method,
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2, ',', '.');
    }

    // Scopes
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeTransfer($query)
    {
        return $query->where('type', 'transfer');
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }
}
