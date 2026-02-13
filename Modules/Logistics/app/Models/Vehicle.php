<?php

namespace Modules\Logistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    protected $fillable = [
        'company_id',
        'plate_number',
        'type',
        'brand',
        'model',
        'capacity_weight',
        'capacity_volume',
        'status',
    ];

    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}

