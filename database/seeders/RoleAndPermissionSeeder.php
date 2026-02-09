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

        // Create roles
        $roles = [
            'Admin' => 'Yönetici - Tüm yetkilere sahip',
            'Manager' => 'Müdür - Çoğu yetkilere sahip',
            'Employee' => 'Çalışan - Sınırlı yetkiler',
            'Accountant' => 'Muhasebeci - Muhasebe modülü yetkileri',
            'Sales' => 'Satış - Satış ve CRM yetkileri',
        ];

        foreach ($roles as $roleName => $description) {
            Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );
        }

        // Create basic permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Company Management
            'view companies',
            'edit companies',
            
            // Accounting
            'view accounting',
            'create invoices',
            'edit invoices',
            'delete invoices',
            'view reports',
            
            // CRM
            'view crm',
            'create contacts',
            'edit contacts',
            'delete contacts',
            
            // Inventory
            'view inventory',
            'create products',
            'edit products',
            'delete products',
            'manage stock',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        // Assign all permissions to Admin role
        $adminRole = Role::findByName('Admin');
        $adminRole->givePermissionTo(Permission::all());

        // Assign specific permissions to other roles
        $managerRole = Role::findByName('Manager');
        $managerRole->givePermissionTo([
            'view users', 'create users', 'edit users',
            'view accounting', 'create invoices', 'edit invoices', 'view reports',
            'view crm', 'create contacts', 'edit contacts',
            'view inventory', 'create products', 'edit products', 'manage stock',
        ]);

        $accountantRole = Role::findByName('Accountant');
        $accountantRole->givePermissionTo([
            'view accounting', 'create invoices', 'edit invoices', 'view reports',
        ]);

        $salesRole = Role::findByName('Sales');
        $salesRole->givePermissionTo([
            'view crm', 'create contacts', 'edit contacts',
            'view inventory',
        ]);

        $employeeRole = Role::findByName('Employee');
        $employeeRole->givePermissionTo([
            'view crm', 'view inventory',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}
