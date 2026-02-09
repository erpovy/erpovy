<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'symbol',
        'type',
        'is_base_unit',
        'is_active',
    ];

    protected $casts = [
        'is_base_unit' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Birime ait ürünler
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Bu birimden diğer birimlere çevirimler
     */
    public function conversionsFrom()
    {
        return $this->hasMany(UnitConversion::class, 'from_unit_id');
    }

    /**
     * Diğer birimlerden bu birime çevirimler
     */
    public function conversionsTo()
    {
        return $this->hasMany(UnitConversion::class, 'to_unit_id');
    }

    /**
     * Belirli bir birime çevirim oranını getir
     */
    public function getConversionRate($toUnitId)
    {
        $conversion = $this->conversionsFrom()
            ->where('to_unit_id', $toUnitId)
            ->first();

        return $conversion ? $conversion->multiplier : null;
    }

    /**
     * Miktar çevirimi yap
     */
    public function convert($quantity, $toUnitId)
    {
        $rate = $this->getConversionRate($toUnitId);
        
        if ($rate === null) {
            return null;
        }

        return $quantity * $rate;
    }
}
