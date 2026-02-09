<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAnalytic extends Model
{
    protected $fillable = [
        'company_id',
        'product_id',
        'warehouse_id',
        'analysis_date',
        'daily_avg_sales',
        'weekly_avg_sales',
        'monthly_avg_sales',
        'sales_trend',
        'current_stock',
        'stock_value',
        'days_of_stock',
        'stock_turnover_rate',
        'predicted_stockout_date',
        'recommended_order_qty',
        'recommended_order_date',
        'stockout_risk_score',
        'overstock_risk_score',
        'obsolescence_risk_score',
        'abc_class',
        'velocity_class',
    ];

    protected $casts = [
        'analysis_date' => 'date',
        'predicted_stockout_date' => 'date',
        'recommended_order_date' => 'date',
        'daily_avg_sales' => 'decimal:2',
        'weekly_avg_sales' => 'decimal:2',
        'monthly_avg_sales' => 'decimal:2',
        'stock_value' => 'decimal:2',
        'stock_turnover_rate' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Company::class);
    }
}
