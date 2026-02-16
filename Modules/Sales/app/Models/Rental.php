<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Modules\CRM\Models\Contact;
use Modules\Inventory\Models\Product;

class Rental extends Model
{
    use HasFactory, BelongsToCompany;

    protected $table = 'sales_rentals';

    protected $fillable = [
        'company_id',
        'contact_id',
        'product_id',
        'rental_no',
        'daily_price',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'daily_price' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getDurationAttribute()
    {
        if (!$this->end_date) return null;
        return $this->start_date->diffInDays($this->end_date);
    }

    public function getTotalAmountAttribute()
    {
        $days = $this->duration ?: 1;
        return $this->daily_price * $days;
    }
}
