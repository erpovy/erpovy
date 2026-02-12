<?php

namespace Modules\ServiceManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobCardItem extends Model
{
    use HasFactory;

    protected $table = 'sm_job_card_items';

    protected $fillable = [
        'job_card_id',
        'product_id',
        'type',
        'name',
        'description',
        'quantity',
        'unit_price',
        'tax_rate',
        'total_price',
        'is_stock_deducted',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'total_price' => 'decimal:2',
        'is_stock_deducted' => 'boolean',
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function product()
    {
        return $this->belongsTo(\Modules\Inventory\Models\Product::class);
    }

    // Boot method to auto-calculate total_price before saving could go here
    protected static function booted()
    {
        static::saving(function ($item) {
            $item->total_price = $item->quantity * $item->unit_price; 
            // Tax logic can be added here if needed, keeping it simple for now
        });

        static::saved(function ($item) {
            $item->jobCard->recalculateTotals();
        });

        static::deleted(function ($item) {
            $item->jobCard->recalculateTotals();
        });
    }
}
