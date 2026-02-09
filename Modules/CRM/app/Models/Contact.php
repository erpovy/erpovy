<?php

namespace Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Modules\CRM\Database\Factories\ContactFactory;

class Contact extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'type', // customer, vendor
        'name',
        'email',
        'phone',
        'tax_number',
        'tax_office',
        'address',
        'current_balance',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
    ];

    /**
     * Cari hesap hareketleri
     */
    public function accountTransactions()
    {
        return $this->hasMany(\Modules\Accounting\Models\AccountTransaction::class);
    }

    /**
     * Faturalar
     */
    public function invoices()
    {
        return $this->hasMany(\Modules\Accounting\Models\Invoice::class);
    }

    /**
     * Toplam borç tutarı
     */
    public function getDebitTotalAttribute()
    {
        return $this->accountTransactions()->where('type', 'debit')->sum('amount');
    }

    /**
     * Toplam alacak tutarı
     */
    public function getCreditTotalAttribute()
    {
        return $this->accountTransactions()->where('type', 'credit')->sum('amount');
    }

    /**
     * Güncel bakiye (Borç - Alacak)
     * Pozitif değer: Müşteri bizden alacaklı (biz borçluyuz)
     * Negatif değer: Müşteri bize borçlu (biz alacaklıyız)
     */
    public function getBalanceAttribute()
    {
        return $this->debit_total - $this->credit_total;
    }

    // protected static function newFactory(): ContactFactory
    // {
    //     // return ContactFactory::new();
    // }
}
