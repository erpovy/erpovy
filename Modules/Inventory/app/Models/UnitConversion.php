<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class UnitConversion extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'from_unit_id',
        'to_unit_id',
        'multiplier',
    ];

    protected $casts = [
        'multiplier' => 'decimal:6',
    ];

    /**
     * Kaynak birim
     */
    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'from_unit_id');
    }

    /**
     * Hedef birim
     */
    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'to_unit_id');
    }

    /**
     * Miktar çevirimi yap
     */
    public function convert($quantity)
    {
        return $quantity * $this->multiplier;
    }

    /**
     * Ters çevirim yap
     */
    public function reverseConvert($quantity)
    {
        return $quantity / $this->multiplier;
    }
}
