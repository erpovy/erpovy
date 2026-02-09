<?php

namespace Modules\Manufacturing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Models\Product;

class WorkOrder extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'product_id',
        'employee_id',
        'order_number',
        'quantity',
        'status',
        'start_date',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function responsibleEmployee()
    {
        return $this->belongsTo(\Modules\HumanResources\Models\Employee::class, 'employee_id');
    }
}
