<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DefaultCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default Company
        $company = Company::create([
            'name' => 'Demo Şirketi A.Ş.',
            'domain' => 'demo',
            'status' => 'active',
            'settings' => ['locale' => 'tr'],
        ]);

        // 2. Create Roles for this Company
        // Note: With "teams" enabled, roles are scoped to company_id
        $adminRole = Role::create(['name' => 'Admin', 'company_id' => $company->id]);
        $accountantRole = Role::create(['name' => 'Accountant', 'company_id' => $company->id]);
        $userRole = Role::create(['name' => 'USER', 'company_id' => $company->id]);

        // 3. Create Super Admin User
        $user = User::create([
            'company_id' => $company->id,
            'name' => 'Super Admin',
            'email' => 'admin@erpovy.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // 4. Assign Role
        // setPermissionsTeamId is important when assigning roles in a seeded context
        setPermissionsTeamId($company->id);
        $user->assignRole($adminRole);

        $this->command->info('Default company and admin user created successfully.');
        $this->command->info('User: admin@erpovy.com / password');
    }
}
