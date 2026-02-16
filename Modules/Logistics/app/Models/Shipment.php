<?php

namespace Modules\Logistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Accounting\Models\Invoice;
use Modules\CRM\Models\Contact;

class Shipment extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    protected $table = 'logistics_shipments';

    protected $fillable = [
        'company_id',
        'route_id',
        'tracking_number',
        'invoice_id',
        'contact_id',
        'origin',
        'destination',
        'status',
        'shipped_at',
        'delivered_at',
        'shipping_method',
        'carrier_name',
        'carrier_tracking_no',
        'weight',
        'weight_kg',
        'volume_m3',
        'estimated_delivery',
        'notes',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'estimated_delivery' => 'date',
    ];

    protected static function booted()
    {
        static::updated(function ($shipment) {
            if ($shipment->isDirty('status')) {
                ShipmentStatusLog::create([
                    'company_id' => $shipment->company_id,
                    'shipment_id' => $shipment->id,
                    'old_status' => $shipment->getOriginal('status'),
                    'new_status' => $shipment->status,
                    'user_id' => auth()->id(),
                    'notes' => 'Sistem tarafından otomatik güncellendi.',
                ]);
            }
        });
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}

