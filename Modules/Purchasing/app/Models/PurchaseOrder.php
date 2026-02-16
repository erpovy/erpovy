<?php

namespace Modules\Purchasing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Purchasing\Database\Factories\PurchaseOrderFactory;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    protected $table = 'purchasing_orders';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'supplier_id',
        'invoice_id',
        'order_number',
        'order_date',
        'status',
        'total_amount',
        'tax_amount',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(\Modules\CRM\Models\Contact::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
