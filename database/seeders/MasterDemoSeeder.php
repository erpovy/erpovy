<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Modules\Accounting\Database\Seeders\AccountingDatabaseSeeder;
use Modules\CRM\Database\Seeders\DemoCRMSeeder;
use Modules\Inventory\Database\Seeders\DemoInventorySeeder;
use Modules\Accounting\Database\Seeders\DemoInvoiceSeeder;
use Modules\Sales\Database\Seeders\DemoQuoteSeeder;

class MasterDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Master Demo Seeding for demo@erpovy.com...');

        // 1. First, ensure the user is updated to demo@erpovy.com
        $this->call(DemoUserUpdateSeeder::class);

        // 2. Find the demo user and its company_id
        $user = User::where('email', 'demo@erpovy.com')->first();
        
        if (!$user) {
            $this->command->error('User demo@erpovy.com not found! Aborting.');
            return;
        }

        $companyId = $user->company_id;
        $this->command->info("Detected Company ID: $companyId for User: {$user->email}");

        // 3. Populate Inventory
        $this->call(DemoInventorySeeder::class, false, ['companyId' => $companyId]);

        // 4. Setup Base Accounting
        // AccountingDatabaseSeeder calls TekDuzenHesapPlaniSeeder which uses Company::first()
        // We might want to run it specifically for this company if it's not ID 1
        $this->call(AccountingDatabaseSeeder::class);

        // 5. Populate CRM
        $this->call(DemoCRMSeeder::class, false, ['companyId' => $companyId]);

        // 6. Populate Invoices (Accounting + Stock)
        $this->call(DemoInvoiceSeeder::class, false, ['companyId' => $companyId]);

        // 7. Populate Sales Quotes
        $this->call(DemoQuoteSeeder::class, false, ['companyId' => $companyId]);

        $this->command->info('Master Demo Seeding completed successfully for ' . $user->email);
    }
}
