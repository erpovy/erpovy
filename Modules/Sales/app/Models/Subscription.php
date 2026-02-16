<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToCompany;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;

class Subscription extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'contact_id',
        'product_id',
        'name',
        'price',
        'cost',
        'billing_interval',
        'status',
        'start_date',
        'next_billing_date',
        'cancelled_at',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_billing_date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Relationship with Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship with Customer (Contact)
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Relationship with Product (Service)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
