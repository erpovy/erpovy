<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanyLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Istanbul Tech A.Ş.',
                'domain' => 'istanbul-tech',
                'status' => 'active',
                'settings' => [
                    'locale' => 'tr',
                    'city' => 'Istanbul',
                    'country' => 'Turkey',
                    'modules' => ['Accounting', 'CRM', 'Inventory']
                ]
            ],
            [
                'name' => 'Ankara Yazılım Ltd.',
                'domain' => 'ankara-yazilim',
                'status' => 'active',
                'settings' => [
                    'locale' => 'tr',
                    'city' => 'Ankara',
                    'country' => 'Turkey',
                    'modules' => ['Accounting', 'CRM']
                ]
            ],
            [
                'name' => 'İzmir Digital',
                'domain' => 'izmir-digital',
                'status' => 'active',
                'settings' => [
                    'locale' => 'tr',
                    'city' => 'Izmir',
                    'country' => 'Turkey',
                    'modules' => ['Accounting', 'Inventory']
                ]
            ],
            [
                'name' => 'Antalya Turizm A.Ş.',
                'domain' => 'antalya-turizm',
                'status' => 'active',
                'settings' => [
                    'locale' => 'tr',
                    'city' => 'Antalya',
                    'country' => 'Turkey',
                    'modules' => ['Accounting', 'CRM', 'Inventory']
                ]
            ],
            [
                'name' => 'Bursa Otomotiv',
                'domain' => 'bursa-otomotiv',
                'status' => 'active',
                'settings' => [
                    'locale' => 'tr',
                    'city' => 'Bursa',
                    'country' => 'Turkey',
                    'modules' => ['Inventory']
                ]
            ],
            [
                'name' => 'Adana Tekstil Ltd.',
                'domain' => 'adana-tekstil',
                'status' => 'active',
                'settings' => [
                    'locale' => 'tr',
                    'city' => 'Adana',
                    'country' => 'Turkey',
                    'modules' => ['Accounting', 'CRM']
                ]
            ],
            [
                'name' => 'Gaziantep Gıda A.Ş.',
                'domain' => 'gaziantep-gida',
                'status' => 'active',
                'settings' => [
                    'locale' => 'tr',
                    'city' => 'Gaziantep',
                    'country' => 'Turkey',
                    'modules' => ['Accounting', 'Inventory']
                ]
            ],
            [
                'name' => 'Konya Mobilya',
                'domain' => 'konya-mobilya',
                'status' => 'active',
                'settings' => [
                    'locale' => 'tr',
                    'city' => 'Konya',
                    'country' => 'Turkey',
                    'modules' => ['Accounting']
                ]
            ],
            [
                'name' => 'Istanbul Finans Ltd.',
                'domain' => 'istanbul-finans',
                'status' => 'active',
                'settings' => [
                    'locale' => 'tr',
                    'city' => 'Istanbul',
                    'country' => 'Turkey',
                    'modules' => ['Accounting', 'CRM']
                ]
            ],
            [
                'name' => 'Ankara Danışmanlık',
                'domain' => 'ankara-danismanlik',
                'status' => 'active',
                'settings' => [
                    'locale' => 'tr',
                    'city' => 'Ankara',
                    'country' => 'Turkey',
                    'modules' => ['CRM']
                ]
            ],
        ];

        foreach ($companies as $companyData) {
            Company::updateOrCreate(
                ['domain' => $companyData['domain']],
                $companyData
            );
        }

        $this->command->info('✅ Test companies with locations created successfully!');
    }
}
