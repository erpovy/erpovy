<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeasonalPattern extends Model
{
    protected $fillable = [
        'company_id',
        'product_id',
        'month',
        'seasonal_index',
        'confidence_level',
    ];

    protected $casts = [
        'seasonal_index' => 'decimal:2',
        'confidence_level' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Company::class);
    }
}
