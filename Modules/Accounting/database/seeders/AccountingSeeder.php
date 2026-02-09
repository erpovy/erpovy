<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Models\Account;

class AccountingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            // 1 DÖNEN VARLIKLAR
            // 10 Hazır Değerler
            ['code' => '100', 'name' => 'Kasa Hesabı', 'type' => 'asset'],
            ['code' => '101', 'name' => 'Alınan Çekler', 'type' => 'asset'],
            ['code' => '102', 'name' => 'Bankalar', 'type' => 'asset'],
            // 12 Ticari Alacaklar
            ['code' => '120', 'name' => 'Alıcılar', 'type' => 'asset'],
            ['code' => '121', 'name' => 'Alacak Senetleri', 'type' => 'asset'],
            // 15 Stoklar
            ['code' => '150', 'name' => 'İlk Madde ve Malzeme', 'type' => 'asset'],
            ['code' => '153', 'name' => 'Ticari Mallar', 'type' => 'asset'],
            
            // 3 KISA VADELİ YABANCI KAYNAKLAR
            // 30 Mali Borçlar
            ['code' => '300', 'name' => 'Banka Kredileri', 'type' => 'liability'],
            // 32 Ticari Borçlar
            ['code' => '320', 'name' => 'Satıcılar', 'type' => 'liability'],
            ['code' => '321', 'name' => 'Borç Senetleri', 'type' => 'liability'],
            // 36 Ödenecek Vergi ve Yükümlülükler
            ['code' => '360', 'name' => 'Ödenecek Vergiler', 'type' => 'liability'],
            ['code' => '391', 'name' => 'Hesaplanan KDV', 'type' => 'liability'],

            // 6 GELİR TABLOSU HESAPLARI
            // 60 Brüt Satışlar
            ['code' => '600', 'name' => 'Yurtiçi Satışlar', 'type' => 'income'],
            ['code' => '601', 'name' => 'Yurtdışı Satışlar', 'type' => 'income'],
            
            // 7 MALİYET HESAPLARI
            ['code' => '770', 'name' => 'Genel Yönetim Giderleri', 'type' => 'expense'],
        ];

        foreach ($accounts as $acc) {
            Account::firstOrCreate(
                ['code' => $acc['code']],
                [
                    'name' => $acc['name'],
                    'type' => $acc['type'],
                    'company_id' => 1,
                    'is_active' => true
                ]
            );
        }
    }
}
