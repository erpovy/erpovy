<?php

namespace Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Models\Product;

class BillOfMaterialItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'bill_of_material_id',
        'product_id',
        'quantity',
        'unit',
        'wastage_percent',
    ];

    public function billOfMaterial()
    {
        return $this->belongsTo(BillOfMaterial::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
