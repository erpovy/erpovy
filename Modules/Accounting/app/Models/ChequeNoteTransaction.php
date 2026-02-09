<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Company;
use App\Models\User;
use Modules\CRM\Models\Contact;

class ChequeNoteTransaction extends Model
{
    protected $fillable = [
        'company_id',
        'transaction_type',
        'transaction_id',
        'action',
        'transaction_date',
        'amount',
        'contact_id',
        'cash_bank_account_id',
        'description',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * İlişkiler
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function cashBankAccount(): BelongsTo
    {
        return $this->belongsTo(CashBankAccount::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cheque(): BelongsTo
    {
        return $this->belongsTo(Cheque::class, 'transaction_id')
                    ->where('transaction_type', 'cheque');
    }

    public function promissoryNote(): BelongsTo
    {
        return $this->belongsTo(PromissoryNote::class, 'transaction_id')
                    ->where('transaction_type', 'promissory_note');
    }

    /**
     * Polymorphic relation
     */
    public function transactionable()
    {
        if ($this->transaction_type === 'cheque') {
            return $this->cheque();
        }
        return $this->promissoryNote();
    }

    /**
     * Accessors
     */
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'received' => 'Alındı',
            'issued' => 'Verildi',
            'deposited' => 'Bankaya Yatırıldı',
            'cashed' => 'Tahsil Edildi',
            'transferred' => 'Ciro Edildi',
            'bounced' => 'Karşılıksız',
            'protested' => 'Protesto Edildi',
            'cancelled' => 'İptal Edildi',
            default => $this->action,
        };
    }
}
