<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Services\GibPortalService;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\LedgerEntry;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\FiscalPeriod;
use Illuminate\Support\Facades\DB;

class ETransformationController extends Controller
{
    public function index()
    {
        $stats = [
            'incoming_count' => Invoice::incoming()->count(),
            'outgoing_count' => Invoice::outgoing()->where(function($q) {
                $q->where('is_e_invoice', true)->orWhere('is_e_archive', true);
            })->count(),
            'pending_approval' => Invoice::where('gib_status', 'draft')->count(),
            'failed_count' => Invoice::where('gib_status', 'failed')->count(),
            'monthly_total' => Invoice::whereMonth('issue_date', now()->month)->count(),
        ];

        return view('accounting::e-transformation.index', compact('stats'));
    }

    public function incoming(Request $request)
    {
        $query = Invoice::incoming()->with('contact');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('contact', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->latest('issue_date')->paginate(15);
        return view('accounting::e-transformation.incoming', compact('invoices'));
    }

    public function outgoing(Request $request)
    {
        $query = Invoice::outgoing()->where(function($q) {
            $q->where('is_e_invoice', true)->orWhere('is_e_archive', true);
        })->with('contact');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('contact', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->latest('issue_date')->paginate(15);
        return view('accounting::e-transformation.outgoing', compact('invoices'));
    }

    public function showXml($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        if (!$invoice->ubl_xml) {
            return back()->with('error', 'Bu faturanın XML içeriği bulunamadı.');
        }

        return view('accounting::e-transformation.show_xml', compact('invoice'));
    }

    public function syncIncoming(Request $request)
    {
        try {
            $gibService = new GibPortalService();
            $incomingData = $gibService->fetchIncomingInvoices();

            // Simülasyon: Eğer veri boşsa (mock environment olduğu için) bir tane mock veri ekleyelim test için
            if (empty($incomingData)) {
                $incomingData = [[
                    'faturaUuid' => \Illuminate\Support\Str::uuid()->toString(),
                    'belgeNumarasi' => 'GIB2026' . rand(100000, 999999),
                    'faturaTarihi' => now()->format('d-m-Y'),
                    'aliciUnvan' => 'GELEN TEST FIRMA',
                    'odenecekTutar' => 1250.00,
                    'vknTckn' => '1234567890'
                ]];
            }

            $count = 0;
            foreach ($incomingData as $data) {
                // Daha önce eklenmiş mi kontrol et (UUID ile)
                $exists = Invoice::where('ettn', $data['faturaUuid'])->exists();
                if (!$exists) {
                    // İlgili Carini bul veya oluştur (basit mantık)
                    $contact = \Modules\CRM\Models\Contact::firstOrCreate(
                        ['tax_number' => $data['vknTckn']],
                        [
                            'name' => $data['aliciUnvan'],
                            'company_id' => auth()->user()->company_id,
                            'type' => 'supplier'
                        ]
                    );

                    Invoice::create([
                        'company_id' => auth()->user()->company_id,
                        'contact_id' => $contact->id,
                        'invoice_number' => $data['belgeNumarasi'],
                        'issue_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $data['faturaTarihi']),
                        'due_date' => \Carbon\Carbon::createFromFormat('d-m-Y', $data['faturaTarihi'])->addDays(30),
                        'grand_total' => $data['odenecekTutar'],
                        'direction' => 'in',
                        'is_e_invoice' => true,
                        'gib_status' => 'completed',
                        'ettn' => $data['faturaUuid'],
                        'status' => 'sent', // Gelenlerde 'sent' olarak işaretleyelim (muhasebeleşmemiş anlamında)
                        'ubl_xml' => '<?xml version="1.0"?><Invoice>Mock Sync Content</Invoice>'
                    ]);
                    $count++;
                }
            }

            return back()->with('success', "{$count} yeni gelen fatura başarıyla senkronize edildi.");
        } catch (\Exception $e) {
            return back()->with('error', 'Senkronizasyon Hatası: ' . $e->getMessage());
        }
    }

    public function convertIncomingToPurchaseInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->status === 'paid' || $invoice->direction !== 'in') {
            return back()->with('error', 'Bu fatura zaten muhasebeleşmiş veya uygun değil.');
        }

        try {
            DB::beginTransaction();

            // 1. Mali Dönem Kontrolü
            $fiscalPeriod = FiscalPeriod::where('company_id', auth()->user()->company_id)
                ->where('status', 'open')
                ->first();

            if (!$fiscalPeriod) {
                throw new \Exception('Aktif bir mali dönem bulunamadı.');
            }

            // 2. Muhasebe Fişi Oluştur
            $transaction = Transaction::create([
                'company_id' => auth()->user()->company_id,
                'fiscal_period_id' => $fiscalPeriod->id,
                'type' => 'regular',
                'receipt_number' => 'FIS-ALIS-' . strtoupper(uniqid()),
                'date' => $invoice->issue_date,
                'description' => 'Gelen e-Fatura Muhasebeleşme: ' . $invoice->invoice_number,
                'is_approved' => true,
            ]);

            // 3. Hesap Atamaları (Varsayılan 153, 191, 320)
            $stockAccount = Account::where('company_id', auth()->user()->company_id)->where('code', '153')->first() 
                             ?? Account::where('code', '153')->first();
            $vatAccount = Account::where('company_id', auth()->user()->company_id)->where('code', '191')->first()
                             ?? Account::where('code', '191')->first();
            $creditorAccount = Account::where('company_id', auth()->user()->company_id)->where('code', '320')->first()
                             ?? Account::where('code', '320')->first();

            // Basit KDV ayırımı (%20 varsayalım eğer detay yoksa)
            $subtotal = round($invoice->grand_total / 1.20, 2);
            $vatTotal = round($invoice->grand_total - $subtotal, 2);

            // Borç: 153 Ticari Mallar (Net Tutar)
            LedgerEntry::create([
                'company_id' => auth()->user()->company_id,
                'transaction_id' => $transaction->id,
                'account_id' => $stockAccount->id,
                'debit' => $subtotal,
                'credit' => 0,
                'description' => 'Fatura Net Tutarı (Matrah)',
            ]);

            // Borç: 191 İndirilecek KDV
            LedgerEntry::create([
                'company_id' => auth()->user()->company_id,
                'transaction_id' => $transaction->id,
                'account_id' => $vatAccount->id,
                'debit' => $vatTotal,
                'credit' => 0,
                'description' => 'Fatura KDV Tutarı',
            ]);

            // Alacak: 320 Satıcılar (Toplam Tutar)
            LedgerEntry::create([
                'company_id' => auth()->user()->company_id,
                'transaction_id' => $transaction->id,
                'account_id' => $creditorAccount->id,
                'debit' => 0,
                'credit' => $invoice->grand_total,
                'description' => 'Fatura Toplam Borç Tutarı',
            ]);

            // 4. Faturayı güncelle
            $invoice->update(['status' => 'paid']);

            DB::commit();
            return back()->with('success', 'Fatura başarıyla muhasebeleştiri ve alış kaydı oluşturuldu.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Muhasebeleşme Hatası: ' . $e->getMessage());
        }
    }

    public function checkOutgoingStatus($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            if (!$invoice->ettn) {
                return back()->with('error', 'Bu faturanın ETTN (UUID) bilgisi eksik.');
            }

            $gibService = new GibPortalService();
            $statusData = $gibService->checkInvoiceStatus($invoice->ettn);

            if (isset($statusData['data'])) {
                $gibStatus = $statusData['data']['durum'] ?? 'unknown';
                
                $statusMap = [
                    'ONAYLANDI' => 'completed',
                    'HATA' => 'failed',
                    'IPTAL' => 'cancelled',
                    'RED' => 'rejected'
                ];

                $internalStatus = $statusMap[$gibStatus] ?? 'processing';
                $invoice->update(['gib_status' => $internalStatus]);

                return back()->with('success', "Fatura durumu güncellendi: {$gibStatus}");
            }

            return back()->with('warning', 'Portal üzerinden durum bilgisi alınamadı.');
        } catch (\Exception $e) {
            return back()->with('error', 'Durum Sorgulama Hatası: ' . $e->getMessage());
        }
    }

    public function bulkCheckStatus()
    {
        try {
            $invoices = Invoice::outgoing()
                ->whereIn('gib_status', ['draft', 'processing'])
                ->get();

            $count = 0;
            $gibService = new GibPortalService();

            foreach ($invoices as $invoice) {
                if ($invoice->ettn) {
                    $statusData = $gibService->checkInvoiceStatus($invoice->ettn);
                    if (isset($statusData['data']['durum'])) {
                        $invoice->update(['gib_status' => 'completed']);
                        $count++;
                    }
                }
            }

            return back()->with('success', "{$count} faturanın durumu toplu olarak güncellendi.");
        } catch (\Exception $e) {
            return back()->with('error', 'Toplu Sorgulama Hatası: ' . $e->getMessage());
        }
    }
}
