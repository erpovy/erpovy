<?php

namespace Modules\Accounting\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CRM\Models\Contact;

class AccountTransaction extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'contact_id',
        'transaction_id',
        'invoice_id',
        'type', // debit (borç) veya credit (alacak)
        'amount',
        'description',
        'transaction_date',
        'balance_after',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * İlişkili müşteri/tedarikçi
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * İlişkili muhasebe fişi
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * İlişkili fatura
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Hareket tipinin Türkçe karşılığı
     */
    public function getTypeLabelAttribute()
    {
        return $this->type === 'debit' ? 'Borç' : 'Alacak';
    }

    /**
     * Scope: Sadece borç hareketleri
     */
    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    /**
     * Scope: Sadece alacak hareketleri
     */
    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    /**
     * Scope: Tarih aralığına göre filtrele
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }
}
