<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Models\VatRate;

class VatRateSeeder extends Seeder
{
    /**
     * Türkiye'deki standart KDV oranlarını seed eder.
     */
    public function run(): void
    {
        $companyId = 1; // İlk şirket için (production'da dinamik olmalı)

        $rates = VatRate::getTurkeyStandardRates();

        foreach ($rates as $rateData) {
            VatRate::updateOrCreate(
                [
                    'company_id' => $companyId,
                    'rate' => $rateData['rate'],
                ],
                [
                    'name' => $rateData['name'],
                    'description' => $rateData['description'],
                    'is_active' => true,
                    'effective_from' => now(),
                ]
            );
        }

        $this->command->info('Türkiye KDV oranları başarıyla yüklendi! (' . count($rates) . ' oran)');
    }
}
