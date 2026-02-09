<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Models\Product;
// use Modules\Accounting\Database\Factories\InvoiceItemFactory;

class InvoiceItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'invoice_id',
        'product_id',
        'description',
        'quantity',
        'unit_price',
        'vat_rate', // KDV oranı (%)
        'vat_amount', // KDV tutarı
        'line_total', // Satır toplamı (KDV dahil)
        'tax_rate', // Geriye dönük uyumluluk
        'total', // Geriye dönük uyumluluk
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'line_total' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors for View Compatibility
    public function getLineTotalAttribute()
    {
        return $this->total;
    }

    // protected static function newFactory(): InvoiceItemFactory
    // {
    //     // return InvoiceItemFactory::new();
    // }
}
