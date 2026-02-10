<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;

class AccountingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $companyId = 1): void
    {
        $this->call(TekDuzenHesapPlaniSeeder::class, false, ['companyId' => $companyId]);
        $this->call(VatRateSeeder::class, false, ['companyId' => $companyId]);
        $this->call(AccountingTestSeeder::class, false, ['companyId' => $companyId]);
    }
}
