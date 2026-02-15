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
        'tracking_number',
        'invoice_id',
        'contact_id',
        'status',
        'shipped_at',
        'delivered_at',
        'shipping_method',
        'carrier_name',
        'carrier_tracking_no',
        'weight',
        'notes',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}

