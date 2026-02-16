<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Ecommerce\Database\Factories\EcommerceMappingFactory;

class EcommerceMapping extends Model
{
    use HasFactory, \App\Traits\BelongsToCompany;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'ecommerce_platform_id',
        'mappable_id',
        'mappable_type',
        'external_id',
        'remote_data',
    ];

    protected $casts = [
        'remote_data' => 'array',
    ];

    /**
     * Get the parent mappable model (Product, etc.).
     */
    public function mappable()
    {
        return $this->morphTo();
    }

    /**
     * Get the platform that owns the mapping.
     */
    public function platform()
    {
        return $this->belongsTo(EcommercePlatform::class, 'ecommerce_platform_id');
    }
}
