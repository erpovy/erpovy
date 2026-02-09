<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'sku',
        'variant_attributes',
        'price_adjustment',
        'stock',
        'barcode',
        'is_active',
    ];

    protected $casts = [
        'variant_attributes' => 'array',
        'price_adjustment' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Ana ürün ilişkisi
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Varyant satış fiyatını hesapla
     */
    public function getPriceAttribute()
    {
        return $this->product->sale_price + $this->price_adjustment;
    }

    /**
     * Varyant özelliklerini string olarak getir
     */
    public function getAttributesStringAttribute()
    {
        if (!$this->variant_attributes) {
            return '';
        }

        return collect($this->variant_attributes)
            ->map(function ($value, $key) {
                return ucfirst($key) . ': ' . $value;
            })
            ->implode(', ');
    }

    /**
     * Tam ürün adı (Ana ürün + varyant özellikleri)
     */
    public function getFullNameAttribute()
    {
        return $this->product->name . ' (' . $this->attributes_string . ')';
    }
}
