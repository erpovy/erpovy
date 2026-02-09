<?php

namespace Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;

class Lead extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'title',
        'email',
        'phone',
        'company_name',
        'source',
        'status',
        'score',
        'assigned_to',
        'address',
        'notes',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    protected $casts = [
        'score' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
