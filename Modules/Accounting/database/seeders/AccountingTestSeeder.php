<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\FiscalPeriod;
use App\Models\Company;

class AccountingTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first(); // Demo Company
        
        // 0. Ensure Contacts and Products exist for Invoice testing
        if (class_exists(\Modules\CRM\Models\Contact::class)) {
            \Modules\CRM\Models\Contact::firstOrCreate([
                'company_id' => $company->id,
                'email' => 'musteri@test.com'
            ], [
                'type' => 'customer',
                'name' => 'Test Müşterisi Ltd. Şti.',
                'tax_number' => '1234567890',
                'address' => 'Test Adresi Istanbul'
            ]);
        }

        if (class_exists(\Modules\Inventory\Models\Product::class)) {
            \Modules\Inventory\Models\Product::firstOrCreate([
                'company_id' => $company->id,
                'code' => 'HIZMET-001'
            ], [
                'name' => 'Yazılım Danışmanlık Hizmeti',
                'type' => 'service',
                'sale_price' => 1500.00,
                'purchase_price' => 0,
                'vat_rate' => 20,
                'stock_track' => false
            ]);
        }
        
        // 1. Create Fiscal Period
        FiscalPeriod::firstOrCreate([
            'company_id' => $company->id,
            'name' => '2025 Mali Yılı'
        ], [
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'status' => 'open'
        ]);

        // 2. Create Basic Accounts (TDHP)
        $accounts = [
            ['code' => '100', 'name' => 'KASA HESABI', 'type' => 'asset'],
            ['code' => '102', 'name' => 'BANKALAR', 'type' => 'asset'],
            ['code' => '120', 'name' => 'ALICILAR', 'type' => 'asset'],
            ['code' => '320', 'name' => 'SATICILAR', 'type' => 'liability'],
            ['code' => '600', 'name' => 'YURTİÇİ SATIŞLAR', 'type' => 'income'],
            ['code' => '770', 'name' => 'GENEL YÖNETİM GİDERLERİ', 'type' => 'expense'],
        ];

        foreach ($accounts as $acc) {
            Account::firstOrCreate([
                'company_id' => $company->id,
                'code' => $acc['code']
            ], $acc);
        }
    }
}
