<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Models\Contact;

class Quote extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    protected $fillable = [
        'company_id',
        'contact_id',
        'quote_number',
        'date',
        'expiry_date',
        'total_amount',
        'tax_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'expiry_date' => 'date',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }
}
