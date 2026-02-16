<?php

namespace Modules\HumanResources\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\HumanResources\Models\PayrollParameter;

class PayrollParametersSeeder extends Seeder
{
    public function run()
    {
        // 2024/2025 Türkiye Yasal Parametreleri (Örnek/Gerçek Yakın Değerler)
        PayrollParameter::create([
            'company_id' => 1,
            'year' => 2026,
            'name' => '2026 Yasal Parametreleri (Taslak)',
            'sgk_worker_rate' => 14.00,
            'unemployment_worker_rate' => 1.00,
            'sgk_employer_rate' => 15.50, // %5 teşvikli
            'unemployment_employer_rate' => 2.00,
            'stamp_tax_rate' => 0.00759,
            'income_tax_brackets' => [
                ['limit' => 110000, 'rate' => 15],
                ['limit' => 230000, 'rate' => 20],
                ['limit' => 870000, 'rate' => 27],
                ['limit' => 3000000, 'rate' => 35],
                ['limit' => 99999999, 'rate' => 40],
            ],
            'min_wage_gross' => 20002.50, // 2024 değeri, 2026 için güncellenebilir
            'sgk_base_matrah' => 20002.50,
            'sgk_ceiling_matrah' => 150018.90,
        ]);
    }
}
