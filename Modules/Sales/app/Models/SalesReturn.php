<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounting\Models\Invoice;
use Modules\CRM\Models\Contact;

class SalesReturn extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'invoice_id',
        'return_number',
        'return_date',
        'total_amount',
        'reason',
        'status',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function contact()
    {
        return $this->invoice ? $this->invoice->contact() : $this->belongsTo(Contact::class);
    }
}
