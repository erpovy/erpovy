<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'category_id',
        'brand_id',
        'unit_id',
        'code',
        'barcode',
        'name',
        'product_type_id',
        'sale_price',
        'purchase_price',
        'vat_rate',
        'weight',
        'dimensions',
        'image_path',
        'description',
        'warranty_period',
        'stock_track',
        'min_stock_level',
        'critical_stock_level',
        'is_active',
        // Analytics fields
        'lead_time_days',
        'safety_stock_level',
        'reorder_point',
        'max_stock_level',
        'abc_classification',
        'last_stock_analysis_at',
    ];

    protected $casts = [
        'dimensions' => 'array',
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'weight' => 'decimal:3',
        'warranty_period' => 'integer',
        'min_stock_level' => 'integer',
        'critical_stock_level' => 'integer',
        'stock_track' => 'boolean',
        'is_active' => 'boolean',
        'lead_time_days' => 'integer',
        'safety_stock_level' => 'integer',
        'reorder_point' => 'integer',
        'max_stock_level' => 'integer',
        'last_stock_analysis_at' => 'datetime',
    ];


    /**
     * Kategori ilişkisi
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * Ürün Türü ilişkisi
     */
    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Marka ilişkisi
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Birim ilişkisi
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Varyantlar ilişkisi
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Stok hareketleri ilişkisi
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Reçete ilişkisi (Manufacturing modülü için)
     */
    public function billOfMaterials()
    {
        return $this->hasMany(\Modules\Manufacturing\Models\BillOfMaterial::class);
    }

    /**
     * Toplam stok miktarını hesapla
     */
    public function getStockAttribute()
    {
        return $this->stockMovements()->sum('quantity');
    }

    /**
     * Belirli bir depodaki stok miktarını getir
     */
    public function getWarehouseStock($warehouseId)
    {
        return $this->stockMovements()
            ->where('warehouse_id', $warehouseId)
            ->sum('quantity');
    }

    /**
     * Stok durumu kontrolü
     */
    public function getStockStatusAttribute()
    {
        if (!$this->stock_track) {
            return 'not_tracked';
        }

        $stock = $this->stock;

        if ($stock <= 0) {
            return 'out_of_stock';
        }

        if ($this->critical_stock_level && $stock <= $this->critical_stock_level) {
            return 'critical';
        }

        if ($this->min_stock_level && $stock <= $this->min_stock_level) {
            return 'low';
        }

        return 'in_stock';
    }

    /**
     * Ürün görseli URL'si
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
