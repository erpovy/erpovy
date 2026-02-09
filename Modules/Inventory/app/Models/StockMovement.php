<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class StockMovement extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'user_id',
        'product_id',
        'warehouse_id',
        'quantity',
        'type',
        'reference',
        'document_type',
        'document_id',
        'unit_cost',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
    ];

    /**
     * İşlemi yapan kullanıcı
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Ürün ilişkisi
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Depo ilişkisi
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Toplam maliyet hesapla
     */
    public function getTotalCostAttribute()
    {
        return $this->quantity * ($this->unit_cost ?? 0);
    }

    /**
     * İşlem türü açıklaması
     */
    public function getTypeDescriptionAttribute()
    {
        return match($this->type) {
            'in' => 'Giriş',
            'out' => 'Çıkış',
            'transfer' => 'Transfer',
            'adjustment' => 'Düzeltme',
            default => 'Bilinmeyen',
        };
    }
}
