<?php

namespace Modules\FixedAssets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class FixedAssetCategory extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'description',
    ];

    public function assets()
    {
        return $this->hasMany(FixedAsset::class, 'category_id');
    }
}
