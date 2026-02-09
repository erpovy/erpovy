<?php

namespace Modules\HumanResources\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleExpense extends Model
{
    protected $fillable = [
        'company_id',
        'vehicle_id',
        'type',
        'amount',
        'date',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
