<?php

namespace Modules\Accounting\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Accounting\Models\AccountTransaction;
use Modules\Accounting\Services\AccountTransactionService;
use Modules\CRM\Models\Contact;

class AccountTransactionSeeder extends Seeder
{
    /**
     * Demo cari hesap hareketleri oluşturur
     */
    public function run(): void
    {
        $service = new AccountTransactionService();
        $companyId = 1; // İlk şirket için

        // Müşteri ve tedarikçileri kontrol et
        $customers = Contact::where('company_id', $companyId)
            ->where('type', 'customer')
            ->take(3)
            ->get();

        $vendors = Contact::where('company_id', $companyId)
            ->where('type', 'vendor')
            ->take(2)
            ->get();

        // Müşteri hareketleri (borç/alacak)
        foreach ($customers as $index => $customer) {
            // İlk hareket: Satış (müşteri borçlanır)
            $service->recordTransaction([
                'company_id' => $companyId,
                'contact_id' => $customer->id,
                'type' => 'debit',
                'amount' => 5000 + ($index * 1000),
                'description' => 'Açılış bakiyesi - Satış',
                'transaction_date' => now()->subDays(30),
            ]);

            // İkinci hareket: Tahsilat (müşteri alacaklanır)
            $service->recordTransaction([
                'company_id' => $companyId,
                'contact_id' => $customer->id,
                'type' => 'credit',
                'amount' => 2000 + ($index * 500),
                'description' => 'Nakit tahsilat',
                'transaction_date' => now()->subDays(15),
            ]);

            // Üçüncü hareket: Yeni satış
            $service->recordTransaction([
                'company_id' => $companyId,
                'contact_id' => $customer->id,
                'type' => 'debit',
                'amount' => 3000 + ($index * 800),
                'description' => 'Yeni satış faturası',
                'transaction_date' => now()->subDays(5),
            ]);
        }

        // Tedarikçi hareketleri (alış/ödeme)
        foreach ($vendors as $index => $vendor) {
            // İlk hareket: Alış (tedarikçi alacaklanır - biz borçlanırız)
            $service->recordTransaction([
                'company_id' => $companyId,
                'contact_id' => $vendor->id,
                'type' => 'debit',
                'amount' => 8000 + ($index * 2000),
                'description' => 'Mal alımı',
                'transaction_date' => now()->subDays(25),
            ]);

            // İkinci hareket: Ödeme (tedarikçi borçlanır - biz alacaklanırız)
            $service->recordTransaction([
                'company_id' => $companyId,
                'contact_id' => $vendor->id,
                'type' => 'credit',
                'amount' => 4000 + ($index * 1000),
                'description' => 'Banka havalesi ile ödeme',
                'transaction_date' => now()->subDays(10),
            ]);
        }

        $totalTransactions = AccountTransaction::where('company_id', $companyId)->count();
        $this->command->info("Demo cari hareketleri oluşturuldu! ({$totalTransactions} hareket)");
    }
}
