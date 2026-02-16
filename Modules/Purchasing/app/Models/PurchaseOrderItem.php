<?php

namespace Modules\Purchasing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Purchasing\Database\Factories\PurchaseOrderItemFactory;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $table = 'purchasing_order_items';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'unit_price',
        'tax_rate',
        'tax_amount',
        'total_amount',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(\Modules\Inventory\Models\Product::class);
    }
}
