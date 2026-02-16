<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcommercePlatform extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'name',
        'type',
        'store_url',
        'consumer_key',
        'consumer_secret',
        'status',
        'settings',
        'last_sync_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'last_sync_at' => 'datetime',
    ];

    protected $hidden = [
        'consumer_key',
        'consumer_secret',
    ];
}
