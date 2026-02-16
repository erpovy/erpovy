<?php

namespace Modules\Logistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use App\Models\User;

class ShipmentStatusLog extends Model
{
    use HasFactory, BelongsToCompany;

    protected $table = 'logistics_shipment_logs';

    protected $fillable = [
        'company_id',
        'shipment_id',
        'old_status',
        'new_status',
        'notes',
        'user_id',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
