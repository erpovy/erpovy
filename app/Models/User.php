<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Traits\BelongsToCompany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, BelongsToCompany;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('hideSuperAdmin', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->hasUser() && !auth()->user()->is_super_admin) {
                $builder->where('is_super_admin', false);
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'is_super_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    public function employee()
    {
        return $this->hasOne(\Modules\HumanResources\Models\Employee::class);
    }

    /**
     * Check if the user has access to a specific module or menu item.
     * 
     * @param string $module
     * @return bool
     */
    public function hasModuleAccess(string $module): bool
    {
        // 1. Check for inspection mode (SuperAdmin viewing a company)
        if (session()->has('is_inspecting') && session()->has('inspected_company_id')) {
            $company = \App\Models\Company::find(session('inspected_company_id'));
            if (!$company) return false;
            
            return $this->checkCompanyModuleAccess($company, $module);
        }

        // 2. Regular SuperAdmin access (not inspecting)
        if ($this->is_super_admin) {
            return true;
        }

        // 3. Regular user access
        if (!$this->company) {
            return false;
        }

        return $this->checkCompanyModuleAccess($this->company, $module);
    }

    /**
     * Internal logic for checking module access against a specific company's settings.
     */
    protected function checkCompanyModuleAccess(\App\Models\Company $company, string $module): bool
    {
        // Core modules/groups that are always accessible
        $core = ['dashboard', 'activities', 'Accounting', 'General'];
        if (in_array($module, $core)) {
            return true;
        }

        $activeModules = $company->settings['modules'] ?? [];

        // Check for direct match
        if (in_array($module, $activeModules)) {
            return true;
        }

        // Group/Prefix matching (e.g., 'Accounting' matches 'accounting.dashboard')
        $moduleLower = strtolower($module);
        foreach ($activeModules as $active) {
            if (str_starts_with(strtolower($active), $moduleLower . '.')) {
                return true;
            }
        }

        return false;
    }
}
