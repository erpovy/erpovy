<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProformaInvoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'proforma_number',
        'proforma_date',
        'valid_until',
        'supplier_name',
        'supplier_address',
        'supplier_email',
        'supplier_phone',
        'items',
        'subtotal',
        'tax_amount',
        'total_amount',
        'notes',
        'terms',
        'status',
        'converted_to_invoice_id',
    ];

    protected $casts = [
        'proforma_date' => 'date',
        'valid_until' => 'date',
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function isExpired(): bool
    {
        return $this->valid_until < now();
    }

    public function isConverted(): bool
    {
        return $this->status === 'converted' && $this->converted_to_invoice_id !== null;
    }
}
