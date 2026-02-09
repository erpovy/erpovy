<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'address',
        'manager_id',
        'type',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Depo yöneticisi
     */
    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }

    /**
     * Depoya ait stok hareketleri
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Depodaki toplam ürün sayısı
     */
    public function getTotalProductsAttribute()
    {
        return $this->stockMovements()
            ->selectRaw('product_id')
            ->distinct()
            ->count();
    }

    /**
     * Depodaki toplam stok değeri
     */
    public function getTotalStockValueAttribute()
    {
        return $this->stockMovements()
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->selectRaw('SUM(stock_movements.quantity * products.purchase_price) as total')
            ->value('total') ?? 0;
    }
}
