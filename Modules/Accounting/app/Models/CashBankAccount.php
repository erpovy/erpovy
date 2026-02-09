<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashBankAccount extends Model
{
    protected $fillable = [
        'company_id',
        'type',
        'name',
        'currency',
        'opening_balance',
        'current_balance',
        'bank_name',
        'branch',
        'account_number',
        'iban',
        'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relations
    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CashBankTransaction::class);
    }

    // Attributes
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'cash' => 'Kasa',
            'bank' => 'Banka',
            default => $this->type,
        };
    }

    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->current_balance, 2, ',', '.') . ' ' . $this->currency;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCash($query)
    {
        return $query->where('type', 'cash');
    }

    public function scopeBank($query)
    {
        return $query->where('type', 'bank');
    }
}
