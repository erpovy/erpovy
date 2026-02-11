<?php

namespace Modules\FixedAssets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HumanResources\Models\Employee;

class AssetAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fixed_asset_id',
        'employee_id',
        'assigned_at',
        'returned_at',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'date',
        'returned_at' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(FixedAsset::class, 'fixed_asset_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
