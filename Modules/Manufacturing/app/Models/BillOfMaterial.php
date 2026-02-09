<?php

namespace Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Modules\Inventory\Models\Product;

class BillOfMaterial extends Model
{
    use HasFactory, BelongsToCompany;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'product_id',
        'code',
        'name',
        'version',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(BillOfMaterialItem::class);
    }
}
