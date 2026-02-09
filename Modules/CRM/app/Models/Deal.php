<?php

namespace Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;

class Deal extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'title',
        'lead_id',
        'contact_id',
        'amount',
        'currency',
        'stage',
        'expected_close_date',
        'probability',
        'assigned_to',
        'lost_reason',
        'description',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function contact()
    {
        return $this->belongsTo(\Modules\CRM\Models\Contact::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }
}
