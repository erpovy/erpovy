<?php

namespace Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Modules\Inventory\Models\Product;

class QualityCheck extends Model
{
    use HasFactory, BelongsToCompany;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'product_id',
        'reference_number',
        'check_date',
        'type',
        'status',
        'checked_quantity',
        'rejected_quantity',
        'notes',
        'inspector_name',
    ];

    protected $casts = [
        'check_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
