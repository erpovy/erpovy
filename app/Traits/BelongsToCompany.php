<?php

namespace App\Traits;

use App\Models\Company;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToCompany
{
    /**
     * The "booted" method of the model.
     */
    protected static function bootBelongsToCompany(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (Auth::check()) {
                if (Auth::user()->company_id) {
                    $model->company_id = Auth::user()->company_id;
                } elseif (session()->has('is_inspecting')) {
                    $model->company_id = session('inspected_company_id');
                }
            }
        });
    }

    /**
     * Get the company that owns the model.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
