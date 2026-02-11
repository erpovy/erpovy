<?php

namespace Modules\FixedAssets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Modules\HumanResources\Models\Employee;

class FixedAsset extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'category_id',
        'name',
        'code',
        'serial_number',
        'purchase_date',
        'purchase_value',
        'status',
        'description',
        'useful_life_years',
        'depreciation_method',
        'prorata',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_value' => 'decimal:2',
        'prorata' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(FixedAssetCategory::class, 'category_id');
    }

    public function assignments()
    {
        return $this->hasMany(AssetAssignment::class);
    }

    public function maintenances()
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    public function currentAssignment()
    {
        return $this->hasOne(AssetAssignment::class)->whereNull('returned_at')->latest();
    }

    public function currentHolder()
    {
        return $this->hasOneThrough(
            Employee::class,
            AssetAssignment::class,
            'fixed_asset_id',
            'id',
            'id',
            'employee_id'
        )->whereNull('asset_assignments.returned_at');
    }

    public function calculateDepreciation()
    {
        if (!$this->purchase_value || !$this->useful_life_years || $this->useful_life_years <= 0) {
            return [];
        }

        $schedule = [];
        $currentValue = $this->purchase_value;
        $yearlyDepreciation = $this->purchase_value / $this->useful_life_years;

        for ($year = 1; $year <= $this->useful_life_years; $year++) {
            $currentValue -= $yearlyDepreciation;
            $schedule[] = [
                'year' => $year,
                'depreciation_amount' => $yearlyDepreciation,
                'book_value' => max(0, $currentValue),
            ];
        }

        return $schedule;
    }
}
