<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Models\Account;

class TekDuzenHesapPlaniSeeder extends Seeder
{
    /**
     * Tek Düzen Hesap Planı'nın temel hesaplarını seed eder.
     * Türkiye muhasebe standartlarına göre 1xx-9xx arası hesaplar.
     */
    public function run(int $companyId = 1): void
    {

        $accounts = [
            // 1XX - DÖNEN VARLIKLAR
            ['code' => '100', 'name' => 'KASA', 'type' => 'asset', 'level' => 1, 'description' => 'Nakit para'],
            ['code' => '101', 'name' => 'ALINAN ÇEKLER', 'type' => 'asset', 'level' => 1, 'description' => 'Müşterilerden alınan çekler'],
            ['code' => '102', 'name' => 'BANKALAR', 'type' => 'asset', 'level' => 1, 'description' => 'Banka hesapları'],
            ['code' => '103', 'name' => 'VERİLEN ÇEKLER VE ÖDEME EMİRLERİ (-)', 'type' => 'asset', 'level' => 1, 'description' => 'Düzenlenen çekler'],
            
            ['code' => '120', 'name' => 'ALICILAR', 'type' => 'asset', 'level' => 1, 'description' => 'Ticari alacaklar'],
            ['code' => '121', 'name' => 'ALACAK SENETLERİ', 'type' => 'asset', 'level' => 1, 'description' => 'Alınan senetler'],
            ['code' => '122', 'name' => 'ALACAK SENETLERİ REESKONTU (-)', 'type' => 'asset', 'level' => 1, 'description' => 'Senet reeskontu'],
            ['code' => '126', 'name' => 'VERİLEN DEPOZİTO VE TEMİNATLAR', 'type' => 'asset', 'level' => 1, 'description' => 'Verilen depozitolar'],
            ['code' => '128', 'name' => 'ŞÜPHELİ TİCARİ ALACAKLAR', 'type' => 'asset', 'level' => 1, 'description' => 'Şüpheli alacaklar'],
            ['code' => '129', 'name' => 'ŞÜPHELİ TİCARİ ALACAKLAR KARŞILIĞI (-)', 'type' => 'asset', 'level' => 1, 'description' => 'Şüpheli alacak karşılığı'],
            
            ['code' => '150', 'name' => 'İLK MADDE VE MALZEME', 'type' => 'asset', 'level' => 1, 'description' => 'Hammadde stokları'],
            ['code' => '151', 'name' => 'YARI MAMULLER - ÜRETİM', 'type' => 'asset', 'level' => 1, 'description' => 'Yarı mamul stokları'],
            ['code' => '152', 'name' => 'MAMULLER', 'type' => 'asset', 'level' => 1, 'description' => 'Mamul stokları'],
            ['code' => '153', 'name' => 'TİCARİ MALLAR', 'type' => 'asset', 'level' => 1, 'description' => 'Ticari mal stokları'],
            
            ['code' => '191', 'name' => 'İNDİRİLECEK KDV', 'type' => 'asset', 'level' => 1, 'description' => 'Alışlarda ödenen KDV'],
            ['code' => '193', 'name' => 'PEŞİN ÖDENEN VERGİ VE FONLAR', 'type' => 'asset', 'level' => 1, 'description' => 'Peşin ödenen vergiler'],

            // 2XX - DURAN VARLIKLAR
            ['code' => '250', 'name' => 'ARAZİ VE ARSALAR', 'type' => 'asset', 'level' => 1, 'description' => 'Arazi ve arsalar'],
            ['code' => '251', 'name' => 'YER ALTI VE YER ÜSTÜ DÜZENLERİ', 'type' => 'asset', 'level' => 1, 'description' => 'Arazi düzenlemeleri'],
            ['code' => '252', 'name' => 'BİNALAR', 'type' => 'asset', 'level' => 1, 'description' => 'Binalar'],
            ['code' => '253', 'name' => 'TESİS, MAKİNE VE CİHAZLAR', 'type' => 'asset', 'level' => 1, 'description' => 'Üretim tesisleri'],
            ['code' => '254', 'name' => 'TAŞITLAR', 'type' => 'asset', 'level' => 1, 'description' => 'Araçlar'],
            ['code' => '255', 'name' => 'DEMİRBAŞLAR', 'type' => 'asset', 'level' => 1, 'description' => 'Demirbaşlar'],
            ['code' => '257', 'name' => 'DİĞER MADDİ DURAN VARLIKLAR', 'type' => 'asset', 'level' => 1, 'description' => 'Diğer sabit kıymetler'],
            ['code' => '258', 'name' => 'BİRİKMİŞ AMORTİSMANLAR (-)', 'type' => 'asset', 'level' => 1, 'description' => 'Amortisman karşılığı'],

            // 3XX - KISA VADELİ YABANCI KAYNAKLAR
            ['code' => '300', 'name' => 'BANKA KREDİLERİ', 'type' => 'liability', 'level' => 1, 'description' => 'Kısa vadeli banka kredileri'],
            ['code' => '320', 'name' => 'SATICILAR', 'type' => 'liability', 'level' => 1, 'description' => 'Ticari borçlar'],
            ['code' => '321', 'name' => 'BORÇ SENETLERİ', 'type' => 'liability', 'level' => 1, 'description' => 'Verilen senetler'],
            ['code' => '322', 'name' => 'BORÇ SENETLERİ REESKONTU (-)', 'type' => 'liability', 'level' => 1, 'description' => 'Senet reeskontu'],
            ['code' => '326', 'name' => 'ALINAN DEPOZİTO VE TEMİNATLAR', 'type' => 'liability', 'level' => 1, 'description' => 'Alınan depozitolar'],
            
            ['code' => '360', 'name' => 'ÖDENECEK VERGİ VE FONLAR', 'type' => 'liability', 'level' => 1, 'description' => 'Ödenecek vergiler'],
            ['code' => '361', 'name' => 'ÖDENECEK SOSYAL GÜVENLİK KESİNTİLERİ', 'type' => 'liability', 'level' => 1, 'description' => 'SGK primleri'],
            ['code' => '391', 'name' => 'HESAPLANAN KDV', 'type' => 'liability', 'level' => 1, 'description' => 'Satışlarda hesaplanan KDV'],

            // 4XX - UZUN VADELİ YABANCI KAYNAKLAR
            ['code' => '400', 'name' => 'BANKA KREDİLERİ', 'type' => 'liability', 'level' => 1, 'description' => 'Uzun vadeli banka kredileri'],
            ['code' => '420', 'name' => 'ALINAN UZUN VADELİ BORÇLAR', 'type' => 'liability', 'level' => 1, 'description' => 'Uzun vadeli ticari borçlar'],

            // 5XX - ÖZKAYNAKLAR
            ['code' => '500', 'name' => 'SERMAYE', 'type' => 'equity', 'level' => 1, 'description' => 'Ödenmiş sermaye'],
            ['code' => '540', 'name' => 'YASAL YEDEKLER', 'type' => 'equity', 'level' => 1, 'description' => 'Yasal yedek akçeler'],
            ['code' => '549', 'name' => 'ÖZEL FONLAR', 'type' => 'equity', 'level' => 1, 'description' => 'Özel fonlar'],
            ['code' => '570', 'name' => 'GEÇMİŞ YILLAR KARLARI', 'type' => 'equity', 'level' => 1, 'description' => 'Geçmiş yıl karları'],
            ['code' => '580', 'name' => 'GEÇMİŞ YILLAR ZARARLARI (-)', 'type' => 'equity', 'level' => 1, 'description' => 'Geçmiş yıl zararları'],
            ['code' => '590', 'name' => 'DÖNEM NET KARI', 'type' => 'equity', 'level' => 1, 'description' => 'Cari dönem karı'],
            ['code' => '591', 'name' => 'DÖNEM NET ZARARI (-)', 'type' => 'equity', 'level' => 1, 'description' => 'Cari dönem zararı'],

            // 6XX - GELİR TABLOSU HESAPLARI
            ['code' => '600', 'name' => 'YURTİÇİ SATIŞLAR', 'type' => 'income', 'level' => 1, 'description' => 'Yurtiçi mal satışları'],
            ['code' => '601', 'name' => 'YURTDIŞI SATIŞLAR', 'type' => 'income', 'level' => 1, 'description' => 'İhracat satışları'],
            ['code' => '602', 'name' => 'DİĞER GELİRLER', 'type' => 'income', 'level' => 1, 'description' => 'Diğer gelirler'],
            
            ['code' => '610', 'name' => 'SATIŞTAN İADELER (-)', 'type' => 'income', 'level' => 1, 'description' => 'Satış iadeleri'],
            ['code' => '611', 'name' => 'SATIŞ İSKONTOLARI (-)', 'type' => 'income', 'level' => 1, 'description' => 'Satış iskontoları'],
            ['code' => '612', 'name' => 'DİĞER İNDİRİMLER (-)', 'type' => 'income', 'level' => 1, 'description' => 'Diğer indirimler'],
            
            ['code' => '620', 'name' => 'SATILAN TİCARİ MALLAR MALİYETİ (-)', 'type' => 'expense', 'level' => 1, 'description' => 'Satılan malların maliyeti'],
            ['code' => '621', 'name' => 'SATILAN MAMULLER MALİYETİ (-)', 'type' => 'expense', 'level' => 1, 'description' => 'Satılan mamullerin maliyeti'],
            
            ['code' => '630', 'name' => 'ARAŞTIRMA VE GELİŞTİRME GİDERLERİ (-)', 'type' => 'expense', 'level' => 1, 'description' => 'Ar-Ge giderleri'],
            ['code' => '631', 'name' => 'PAZARLAMA SATIŞ VE DAĞITIM GİDERLERİ (-)', 'type' => 'expense', 'level' => 1, 'description' => 'Pazarlama giderleri'],
            ['code' => '632', 'name' => 'GENEL YÖNETİM GİDERLERİ (-)', 'type' => 'expense', 'level' => 1, 'description' => 'Yönetim giderleri'],
            
            ['code' => '640', 'name' => 'FAALİYETLERDEN OLAĞAN GELİR VE KARLAR', 'type' => 'income', 'level' => 1, 'description' => 'Diğer olağan gelirler'],
            ['code' => '641', 'name' => 'FAALİYETLERDEN OLAĞAN GİDER VE ZARARLAR (-)', 'type' => 'expense', 'level' => 1, 'description' => 'Diğer olağan giderler'],
            
            ['code' => '660', 'name' => 'KISA VADELİ BORÇLANMA GİDERLERİ (-)', 'type' => 'expense', 'level' => 1, 'description' => 'Faiz giderleri'],
            ['code' => '661', 'name' => 'UZUN VADELİ BORÇLANMA GİDERLERİ (-)', 'type' => 'expense', 'level' => 1, 'description' => 'Uzun vadeli faiz giderleri'],
            
            ['code' => '679', 'name' => 'DİĞER OLAĞANDIŞI GELİR VE KARLAR', 'type' => 'income', 'level' => 1, 'description' => 'Olağandışı gelirler'],
            ['code' => '689', 'name' => 'DİĞER OLAĞANDIŞI GİDER VE ZARARLAR (-)', 'type' => 'expense', 'level' => 1, 'description' => 'Olağandışı giderler'],

            // 7XX - MALİYET HESAPLARI
            ['code' => '700', 'name' => 'İLK MADDE VE MALZEME GİDERLERİ', 'type' => 'expense', 'level' => 1, 'description' => 'Direkt ilk madde malzeme'],
            ['code' => '710', 'name' => 'DİREKT İŞÇİLİK GİDERLERİ', 'type' => 'expense', 'level' => 1, 'description' => 'Direkt işçilik'],
            ['code' => '720', 'name' => 'GENEL ÜRETİM GİDERLERİ', 'type' => 'expense', 'level' => 1, 'description' => 'Endirekt üretim giderleri'],
        ];

        foreach ($accounts as $accountData) {
            Account::updateOrCreate(
                [
                    'company_id' => $companyId,
                    'code' => $accountData['code'],
                ],
                [
                    'name' => $accountData['name'],
                    'type' => $accountData['type'],
                    'level' => $accountData['level'],
                    'description' => $accountData['description'],
                    'is_system' => true, // Tek Düzen Hesap Planı hesapları sistem hesabıdır
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Tek Düzen Hesap Planı başarıyla yüklendi! (' . count($accounts) . ' hesap)');
    }
}
