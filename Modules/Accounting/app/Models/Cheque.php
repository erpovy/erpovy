<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Company;
use Modules\CRM\Models\Contact;

class Cheque extends Model
{
    protected $fillable = [
        'company_id',
        'type',
        'cheque_number',
        'bank_name',
        'branch',
        'account_number',
        'drawer',
        'endorser',
        'amount',
        'currency',
        'issue_date',
        'due_date',
        'status',
        'contact_id',
        'cash_bank_account_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
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

    public function transactions(): HasMany
    {
        return $this->hasMany(ChequeNoteTransaction::class, 'transaction_id')
                    ->where('transaction_type', 'cheque');
    }

    /**
     * Scopes
     */
    public function scopeReceived($query)
    {
        return $query->where('type', 'received');
    }

    public function scopeIssued($query)
    {
        return $query->where('type', 'issued');
    }

    public function scopePortfolio($query)
    {
        return $query->where('status', 'portfolio');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->where('status', 'portfolio');
    }

    public function scopeUpcoming($query, $days = 30)
    {
        return $query->where('due_date', '>=', now())
                     ->where('due_date', '<=', now()->addDays($days))
                     ->where('status', 'portfolio');
    }

    /**
     * Accessors
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'portfolio' => 'Portföyde',
            'deposited' => 'Bankaya Yatırıldı',
            'cashed' => 'Tahsil Edildi',
            'bounced' => 'Karşılıksız',
            'transferred' => 'Ciro Edildi',
            'cancelled' => 'İptal Edildi',
            default => $this->status,
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'received' ? 'Alınan Çek' : 'Verilen Çek';
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date < now() && $this->status === 'portfolio';
    }

    public function getDaysUntilDueAttribute(): int
    {
        return now()->diffInDays($this->due_date, false);
    }
}
