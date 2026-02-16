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
        if (!$this->purchase_value || !$this->useful_life_years || $this->useful_life_years <= 0 || !$this->purchase_date) {
            return [];
        }

        $schedule = [];
        $currentValue = $this->purchase_value;
        $totalDepreciation = 0;
        $normalRate = 1 / $this->useful_life_years;
        $purchaseYear = (int)$this->purchase_date->format('Y');
        $purchaseMonth = (int)$this->purchase_date->format('m');

        for ($year = 1; $year <= $this->useful_life_years; $year++) {
            $currentYear = $purchaseYear + $year - 1;
            $depreciationAmount = 0;

            if ($this->depreciation_method === 'declining_balance') {
                $rate = min(0.5, $normalRate * 2); // Azalan bakiyelerde genelde normal oranın 2 katı uygulanır, max %50.
                $depreciationAmount = $currentValue * $rate;
            } else {
                // Normal Amortisman
                $depreciationAmount = $this->purchase_value * $normalRate;
            }

            // Kıst Amortisman (Prorata) Kontrolü (Sadece ilk yıl için)
            if ($year === 1 && $this->prorata) {
                $monthsRemaining = 12 - $purchaseMonth + 1;
                $fullYearDepreciation = $depreciationAmount;
                $depreciationAmount = ($fullYearDepreciation / 12) * $monthsRemaining;
                
                // İlk yıl ayrılmayan kısım son yıla eklenir (veya amortisman süresi 1 yıl uzar)
                // Türkiye vergi mevzuatına göre son yıl bakiye sıfırlanır.
            }

            // Son yıl kontrolü (Sıfırlama garantisi)
            if ($year === $this->useful_life_years) {
                $depreciationAmount = $currentValue;
            }

            $depreciationAmount = min($depreciationAmount, $currentValue);
            $currentValue -= $depreciationAmount;
            $totalDepreciation += $depreciationAmount;

            $schedule[] = [
                'year_index' => $year,
                'calendar_year' => $currentYear,
                'depreciation_amount' => round($depreciationAmount, 2),
                'accumulated_depreciation' => round($totalDepreciation, 2),
                'book_value' => round(max(0, $currentValue), 2),
            ];

            if ($currentValue <= 0) break;
        }

        return $schedule;
    }
}
