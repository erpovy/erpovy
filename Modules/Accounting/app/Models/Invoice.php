<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Contact;
use Modules\Accounting\Models\Transaction;

class Invoice extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'contact_id',
        'transaction_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'subtotal', // KDV hariç tutar
        'vat_total', // Toplam KDV
        'grand_total', // KDV dahil genel toplam
        'vat_withholding', // KDV tevkifatı
        'total_amount', // Geriye dönük uyumluluk
        'tax_amount', // Geriye dönük uyumluluk
        'status',
        'notes',
        'ettn',
        'invoice_type',
        'invoice_scenario',
        'gib_status',
        'receiver_info',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->ettn)) {
                $invoice->ettn = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'vat_withholding' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'receiver_info' => 'array',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    
    // Accessors for View Compatibility
    public function getGrandTotalAttribute()
    {
        return $this->total_amount;
    }

    public function getVatTotalAttribute()
    {
        return $this->tax_amount;
    }

    public function getSubtotalAttribute()
    {
        // Avoid division by zero if needed, but here simple subtraction
        return $this->total_amount - $this->tax_amount;
    }

    public function getGibStatusLabelAttribute()
    {
        $statuses = [
            'draft' => 'TASLAK',
            'signed' => 'İMZALANDI',
            'sent' => 'GÖNDERİLDİ',
            'approved' => 'ONAYLANDI',
            'rejected' => 'REDDEDİLDİ',
            'error' => 'HATA',
        ];

        return $statuses[$this->gib_status] ?? 'GÖNDERİLMEDİ';
    }
    
    public function getStatusLabelAttribute()
    {
         $statuses = [
            'draft' => 'Taslak',
            'sent' => 'Gönderildi',
            'paid' => 'Ödendi',
            'cancelled' => 'İptal',
            'overdue' => 'Gecikmiş',
        ];

        if ($this->is_paid_status) {
            return 'Ödendi';
        }

        return $statuses[$this->status] ?? 'Bilinmiyor';
    }

    public function getIsPaidStatusAttribute()
    {
        if ($this->status === 'paid') {
            return true;
        }

        // If contact balance is 0 or less (meaning they don't owe us), assume paid
        if ($this->contact && $this->contact->current_balance <= 0) {
            return true;
        }

        return false;
    }
}
