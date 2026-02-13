<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Traits\BelongsToCompany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, BelongsToCompany, HasRoles;

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
        'theme',
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
     * Check if the user is a demo user.
     */
    public function isDemoUser(): bool
    {
        return $this->email === 'demo@erpovy.com';
    }

    /**
     * Check if the user has access to a specific module or menu item.
     * 
     * @param string $module
     * @return bool
     */
    public function hasModuleAccess(string $module): bool
    {
        // 0. SuperAdmins always have full access
        if ($this->is_super_admin) {
            return true;
        }

        // 1. Check for inspection mode (SuperAdmin viewing a company)
        if (session()->has('is_inspecting') && session()->has('inspected_company_id')) {
            $company = \App\Models\Company::find(session('inspected_company_id'));
            if (!$company) return false;
            
            return $this->checkCompanyModuleAccess($company, $module);
        }

        // 2. Demo User access (Broad access but exclude SuperAdmin management)
        if ($this->isDemoUser()) {
            // Exclude SuperAdmin management routes
            $superAdminRestricted = ['superadmin.companies', 'superadmin.index', 'superadmin.stop-inspection'];
            foreach ($superAdminRestricted as $restricted) {
                if ($module === $restricted || str_starts_with($module, $restricted . '.')) {
                    return false;
                }
            }
            return true;
        }

        // 3. Regular user access (including SuperAdmin associated with a company)
        if (!$this->company) {
            // SuperAdmins without a company see all business modules 
            // Regular users without a company see nothing
            return $this->is_super_admin;
        }

        return $this->checkCompanyModuleAccess($this->company, $module);
    }

    /**
     * Internal logic for checking module access against a specific company's settings.
     */
    protected function checkCompanyModuleAccess(\App\Models\Company $company, string $module): bool
    {
        $moduleLower = strtolower($module);

        // Items that are always accessible to company users/admins
        if ($moduleLower === 'market.index') {
            return true;
        }

        // Core modules accessible to all Admins
        $coreModules = ['accounting', 'crm', 'inventory', 'humanresources', 'sales', 'dashboard', 'reports'];
        $moduleBase = explode('.', $moduleLower)[0];
        
        if (in_array($moduleBase, $coreModules)) {
            // Case-insensitive role check
            if ($this->hasAnyRole(['Admin', 'admin', 'Yönetici', 'yönetici'])) {
                return true;
            }
        }



        $activeModules = collect($company->settings['modules'] ?? [])
            ->map(fn($m) => strtolower($m))
            ->toArray();

        // Check for direct match
        if (in_array($moduleLower, $activeModules)) {
            return true;
        }

        // Group/Prefix matching (e.g., 'Accounting' matches 'accounting.dashboard')
        foreach ($activeModules as $active) {
            if (str_starts_with($active, $moduleLower . '.')) {
                return true;
            }
        }

        return false;
    }

}
