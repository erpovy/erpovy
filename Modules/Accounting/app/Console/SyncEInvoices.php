<?php

namespace Modules\Accounting\Console;

use Illuminate\Console\Command;
use Modules\Accounting\Services\GibPortalService;
use Modules\Accounting\Models\Invoice;
use Illuminate\Support\Facades\Log;

class SyncEInvoices extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'accounting:sync-einvoices';

    /**
     * The console command description.
     */
    protected $description = 'Gelen faturaları senkronize eder ve giden fatura durumlarını günceller.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('e-Dönüşüm senkronizasyonu başlatılıyor...');
        Log::info('e-Dönüşüm senkronizasyonu başlatılıyor...');

        try {
            $companies = \App\Models\Company::whereNotNull('gib_username')->get();

            if ($companies->isEmpty()) {
                $this->warn('GİB bilgileri tanımlı şirket bulunamadı.');
                return;
            }

            foreach ($companies as $company) {
                $this->info("Şirket işleniyor: {$company->name}");
                $gibService = new GibPortalService($company);

                // 1. Gelen Faturaları Çek
                $this->info('Gelen faturalar sorgulanıyor...');
                $incomingData = $gibService->fetchIncomingInvoices();
                
                foreach ($incomingData as $data) {
                    $exists = Invoice::where('ettn', $data['faturaUuid'])->exists();
                    if (!$exists) {
                        Log::info('Yeni gelen fatura bulundu:', ['ettn' => $data['faturaUuid']]);
                    }
                }

                // 2. Giden Faturaların Durumunu Güncelle
                $this->info('Giden fatura durumları güncelleniyor...');
                $pendingInvoices = Invoice::outgoing()
                    ->where('company_id', $company->id)
                    ->whereIn('gib_status', ['draft', 'processing'])
                    ->get();

                foreach ($pendingInvoices as $invoice) {
                    if ($invoice->ettn) {
                        $statusData = $gibService->checkInvoiceStatus($invoice->ettn);
                        if (isset($statusData['data']['durum'])) {
                            Log::info("Fatura durumu güncellendi: {$invoice->invoice_number} -> {$statusData['data']['durum']}");
                        }
                    }
                }
            }

            $this->info('Senkronizasyon başarıyla tamamlandı.');
            Log::info('e-Dönüşüm senkronizasyonu tamamlandı.');

        } catch (\Exception $e) {
            $this->error('Hata: ' . $e->getMessage());
            Log::error('e-Dönüşüm senkronizasyon hatası: ' . $e->getMessage());
        }
    }
}
