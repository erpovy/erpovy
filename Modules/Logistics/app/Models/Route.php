<?php

namespace Modules\Logistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    protected $table = 'logistics_routes';

    protected $fillable = [
        'company_id',
        'vehicle_id',
        'name',
        'planned_date',
        'status',
        'stops',
        'total_distance',
        'estimated_duration',
    ];

    protected $casts = [
        'planned_date' => 'date',
        'stops' => 'json',
    ];

    protected static function booted()
    {
        static::updated(function ($route) {
            if ($route->isDirty('status') && $route->status === 'completed') {
                $route->shipments()->update([
                    'status' => 'delivered',
                    'delivered_at' => now()
                ]);
            }
        });
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}

