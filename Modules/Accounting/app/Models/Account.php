<?php

namespace Modules\Accounting\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'description',
        'parent_id',
        'type', // asset, liability, equity, income, expense
        'level', // 1=Ana Hesap, 2=Grup, 3=Detay
        'is_active',
        'is_system', // Sistem hesabı mı? (Silinemez)
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'level' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public static function getTypes()
    {
        return [
            'asset' => 'Varlık',
            'liability' => 'Kaynak',
            'equity' => 'Özkaynak',
            'income' => 'Gelir',
            'expense' => 'Gider',
        ];
    }

    public function getTypeLabelAttribute()
    {
        return static::getTypes()[$this->type] ?? $this->type;
    }

    public function getBalanceAttribute()
    {
        $debit = $this->ledgerEntries()->sum('debit');
        $credit = $this->ledgerEntries()->sum('credit');

        if (in_array($this->type, ['asset', 'expense'])) {
            return $debit - $credit;
        }

        return $credit - $debit;
    }

    public function getCurrentBalanceAttribute()
    {
        return $this->getBalanceAttribute();
    }
}
