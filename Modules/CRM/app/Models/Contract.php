<?php

namespace Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;

class Contract extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'subject',
        'contact_id',
        'deal_id',
        'value',
        'start_date',
        'end_date',
        'status',
        'description',
        'content',
        'signed_by',
        'customer_signer_name',
        'signed_at',
    ];

    public function contact()
    {
        return $this->belongsTo(\Modules\CRM\Models\Contact::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function signedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'signed_by');
    }
}
