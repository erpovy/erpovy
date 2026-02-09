<?php

namespace Modules\CRM\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\CRM\Models\Contact;

class DemoCRMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contacts = [
            [
                'type' => 'customer',
                'name' => 'Artovy Tasarım Ltd. Şti.',
                'email' => 'info@artovy.com',
                'phone' => '0212 555 1234',
                'tax_number' => '1234567890',
                'tax_office' => 'Zincirlikuyu',
                'address' => 'Levent Mah. No:1 Beşiktaş/İstanbul',
                'current_balance' => 0,
            ],
            [
                'type' => 'customer',
                'name' => 'Gökçek Lojistik A.Ş.',
                'email' => 'muhasebe@gokcelojistik.com',
                'phone' => '0216 444 9876',
                'tax_number' => '0987654321',
                'tax_office' => 'Kartal',
                'address' => 'Cevizli Mah. No:42 Kartal/İstanbul',
                'current_balance' => -12500.50,
            ],
            [
                'type' => 'customer',
                'name' => 'Beta Yazılım Çözümleri',
                'email' => 'admin@betasoft.net',
                'phone' => '0312 888 1122',
                'tax_number' => '5566778899',
                'tax_office' => 'Etimesgut',
                'address' => 'Teknokent B Blok No:6 Ankara',
                'current_balance' => 4500.00,
            ],
            [
                'type' => 'vendor',
                'name' => 'DataCenter Bulut Servisleri',
                'email' => 'sales@datacenter.com',
                'phone' => '0850 333 4455',
                'tax_number' => '1122334455',
                'tax_office' => 'Kozyatağı',
                'address' => 'İçerenköy Mah. No:112 Ataşehir/İstanbul',
                'current_balance' => 50000.00,
            ],
            [
                'type' => 'vendor',
                'name' => 'Global Kırtasiye Ltd.',
                'email' => 'siparis@globalkirtasiye.com',
                'phone' => '0212 222 3344',
                'tax_number' => '9988776655',
                'tax_office' => 'İkitelli',
                'address' => 'İOSB Galano Blok No:15 Başakşehir/İstanbul',
                'current_balance' => 0,
            ],
        ];

        foreach ($contacts as $contactData) {
            Contact::updateOrCreate(
                ['email' => $contactData['email']],
                array_merge($contactData, ['company_id' => 1])
            );
        }

        $this->command->info('CRM Demo Contacts created successfully.');
    }
}
