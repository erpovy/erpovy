<?php

namespace Modules\Accounting\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VatRate extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'rate',
        'is_active',
        'effective_from',
        'description',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
        'effective_from' => 'date',
    ];

    /**
     * Türkiye'deki standart KDV oranları
     */
    public static function getTurkeyStandardRates()
    {
        return [
            ['name' => 'KDV %1', 'rate' => 1, 'description' => 'Temel gıda maddeleri (ekmek, süt, peynir vb.)'],
            ['name' => 'KDV %8', 'rate' => 8, 'description' => 'Temel tüketim ürünleri (kıyafet, konaklama vb.)'],
            ['name' => 'KDV %10', 'rate' => 10, 'description' => 'Özel tıbbi ürünler ve beşeri tıbbi etkin maddeler'],
            ['name' => 'KDV %20', 'rate' => 20, 'description' => 'Genel oran (çoğu mal ve hizmet)'],
        ];
    }
}
