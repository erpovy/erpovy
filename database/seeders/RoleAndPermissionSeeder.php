<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create permissions (Global)
        $permissions = [
            // User & Access Management
            'view users', 'create users', 'edit users', 'delete users',
            'view roles', 'create roles', 'edit roles', 'delete roles',
            'view permissions', 'create permissions', 'edit permissions', 'delete permissions',
            
            // Company & System
            'view companies', 'create companies', 'edit companies', 'delete companies',
            'view settings', 'manage settings',
            
            // Accounting
            'view accounting',
            'view invoices', 'create invoices', 'edit invoices', 'delete invoices',
            'view cash-bank', 'create cash-bank', 'edit cash-bank', 'delete cash-bank',
            'view reports',
            
            // CRM
            'view crm',
            'view contacts', 'create contacts', 'edit contacts', 'delete contacts',
            
            // Human Resources
            'view hr',
            'view employees', 'create employees', 'edit employees', 'delete employees',
            'view departments', 'create departments', 'edit departments', 'delete departments',
            'view leaves', 'create leaves', 'edit leaves', 'delete leaves',
            'view fleet', 'create fleet', 'edit fleet', 'delete fleet',
            
            // Inventory
            'view inventory',
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view brands', 'create brands', 'edit brands', 'delete brands',
            'view units', 'create units', 'edit units', 'delete units',
            'view warehouses', 'create warehouses', 'edit warehouses', 'delete warehouses',
            'manage stock', 'view stock-movements', 'create stock-movements',
            
            // Sales
            'view sales',
            'view subscriptions', 'create subscriptions', 'edit subscriptions', 'delete subscriptions',
            'view rentals', 'create rentals', 'edit rentals', 'delete rentals',

            // Manufacturing
            'view manufacturing',
            'view bom', 'create bom', 'edit bom', 'delete bom',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // 2. Create roles for each company
        $roles = [
            'Admin' => 'Yönetici - Tüm yetkilere sahip',
            'Manager' => 'Müdür - Çoğu yetkilere sahip',
            'Employee' => 'Çalışan - Sınırlı yetkiler',
            'Accountant' => 'Muhasebeci - Muhasebe modülü yetkileri',
            'Sales' => 'Satış - Satış ve CRM yetkileri',
        ];

        $companies = \App\Models\Company::all();

        foreach ($companies as $company) {
            setPermissionsTeamId($company->id);

            foreach ($roles as $roleName => $description) {
                $role = Role::firstOrCreate(
                    ['name' => $roleName, 'company_id' => $company->id, 'guard_name' => 'web']
                );

                // Assign permissions based on role
                if ($roleName === 'Admin') {
                    $role->syncPermissions(Permission::all());
                } elseif ($roleName === 'Manager') {
                    $role->syncPermissions([
                        'view users', 'create users', 'edit users',
                        'view companies', 'edit companies',
                        'view accounting', 'view invoices', 'create invoices', 'edit invoices', 'view cash-bank', 'create cash-bank', 'view reports',
                        'view crm', 'view contacts', 'create contacts', 'edit contacts',
                        'view hr', 'view employees', 'create employees', 'edit employees', 'view departments', 'view leaves',
                        'view inventory', 'view products', 'create products', 'edit products', 'manage stock', 'view stock-movements',
                        'view sales', 'view subscriptions', 'create subscriptions', 'view rentals',
                        'view manufacturing', 'view bom',
                    ]);
                } elseif ($roleName === 'Accountant') {
                    $role->syncPermissions([
                        'view accounting', 'view invoices', 'create invoices', 'edit invoices', 'view cash-bank', 'create cash-bank', 'view reports',
                        'view crm', 'view contacts',
                    ]);
                } elseif ($roleName === 'Sales') {
                    $role->syncPermissions([
                        'view crm', 'view contacts', 'create contacts', 'edit contacts',
                        'view sales', 'view subscriptions', 'create subscriptions', 'view rentals', 'create rentals',
                        'view inventory', 'view products',
                    ]);
                } elseif ($roleName === 'Employee') {
                    $role->syncPermissions([
                        'view crm', 'view inventory', 'view products',
                        'view sales',
                    ]);
                }
            }
        }

        $this->command->info('Roles and permissions updated for all companies successfully!');
    }
}
