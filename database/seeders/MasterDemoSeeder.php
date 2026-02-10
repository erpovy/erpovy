<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Database\Seeders\AccountingDatabaseSeeder;
use Modules\CRM\Database\Seeders\DemoCRMSeeder;
use Modules\Inventory\Database\Seeders\DemoInventorySeeder;

class MasterDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Master Demo Seeding...');

        // 1. Update User
        $this->call(DemoUserUpdateSeeder::class);

        // 2. Populate Inventory (Creates ProductTypes, Categories, etc.)
        $this->call(DemoInventorySeeder::class);

        // 3. Setup Base Accounting (Chart of Accounts, VAT Rates)
        $this->call(AccountingDatabaseSeeder::class);

        // 4. Populate CRM
        $this->call(DemoCRMSeeder::class);

        $this->command->info('Master Demo Seeding completed successfully!');
    }
}
