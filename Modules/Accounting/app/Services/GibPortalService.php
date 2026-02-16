<?php

namespace Modules\Accounting\Services;

use Modules\Accounting\Models\Invoice;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GibPortalService
{
    protected $baseUrl = 'https://earsivportal.efatura.gov.tr';
    protected $token = null;

    protected $company;

    public function __construct($company = null)
    {
        $this->company = $company ?? auth()->user()->company;
    }

    /**
     * GİB Portalına giriş yapar ve token alır.
     */
    public function login()
    {
        if (empty($this->company->gib_username) || empty($this->company->gib_password)) {
            throw new \Exception('Şirket ayarlarında GİB Portal kullanıcı adı veya şifresi eksik.');
        }

        $response = Http::withoutVerifying()->asForm()->post($this->baseUrl . '/earsiv-services/assos-login', [
            'assoscmd' => 'login',
            'userid' => $this->company->gib_username,
            'username' => $this->company->gib_username,
            'password' => $this->company->gib_password,
        ]);

        $data = $response->json();

        if (isset($data['error'])) {
            throw new \Exception('GİB Giriş Hatası: ' . $data['messages'][0]['text'] ?? 'Bilinmeyen hata');
        }

        if (!isset($data['token'])) {
            throw new \Exception('GİB Token alınamadı.');
        }

        $this->token = $data['token'];
        session(['gib_token' => $this->token]);

        return $this->token;
    }

    /**
     * Faturayı GİB formatına çevirir ve taslak olarak gönderir.
     */
    public function createDraft(Invoice $invoice)
    {
        if (!$this->token) {
            $this->login();
        }

        $gibData = $this->transformToGibFormat($invoice);

        $response = Http::withoutVerifying()->asForm()->post($this->baseUrl . '/earsiv-services/dispatch', [
            'cmd' => 'EARSIV_PORTAL_FATURA_OLUSTUR',
            'callid' => Str::uuid()->toString(),
            'pageName' => 'RG_BASITFATURA',
            'token' => $this->token,
            'jp' => json_encode($gibData)
        ]);

        $result = $response->json();
        
        \Illuminate\Support\Facades\Log::info('GİB Fatura Gönderim Cevabı:', ['result' => $result, 'data' => $gibData]);

        if (isset($result['error'])) {
            throw new \Exception('GİB Fatura Oluşturma Hatası: ' . ($result['messages'][0]['text'] ?? 'Bilinmeyen hata'));
        }

        // Başarılı ise faturayı güncelle
        $invoice->update([
            'gib_status' => 'draft', // GIB tarafında taslak
        ]);

        return true;
    }

    /**
     * Invoice modelini GİB JSON formatına çevirir.
     */
    protected function transformToGibFormat(Invoice $invoice)
    {
        $items = [];
        foreach ($invoice->items as $item) {
            $lineAmount = round($item->quantity * $item->unit_price, 2);
            $lineVat = round($lineAmount * ($item->tax_rate / 100), 2);
            
            $items[] = [
                'malHizmet' => $item->description,
                'miktar' => $item->quantity,
                'birim' => 'C62', // Adet
                'birimFiyat' => $item->unit_price,
                'fiyat' => $lineAmount,
                'iskontoOrani' => 0,
                'iskontoTutari' => 0,
                'iskontoNedeni' => '',
                'malHizmetTutari' => $lineAmount,
                'kdvOrani' => (int)$item->tax_rate,
                'kdvTutari' => $lineVat,
                'vergininKdvTutari' => 0,
                'ozelMatrahTutari' => 0,
                'hesaplananotvtevkifatabitutari' => 0
            ];
        }

        $grandTotal = round($invoice->grand_total, 2);
        $taxAmount = round($invoice->vat_total, 2);
        $subtotal = round($invoice->subtotal, 2);

        // Tutar yazıyla
        $totalText = 'YALNIZ ' . $grandTotal . ' TRY'; // Basit

        return [
            'faturaUuid' => $invoice->ettn,
            'belgeNumarasi' => '', // GIB Verecek
            'faturaTarihi' => $invoice->issue_date->format('d-m-Y'),
            'saat' => now()->format('H:i:s'),
            'paraBirim' => 'TRY',
            'dovizKuru' => 0,
            'faturaTipi' => $invoice->invoice_type, // SATIS
            'hangiTip' => '5000/30000', // Modüle göre değişebilir, varsayılan
            'vknTckn' => $invoice->contact->tax_number ?? '11111111111',
            'aliciUnvan' => $invoice->contact->name,
            'aliciAdi' => '', // Şahıs ise ad soyad ayrımı gerekebilir
            'aliciSoyadi' => '',
            'binaAdi' => '',
            'binaNo' => '',
            'kapiNo' => '',
            'kasabaKoy' => '',
            'vergiDairesi' => $invoice->contact->tax_office ?? '',
            'ulke' => 'Türkiye',
            'bulvarcaddesokak' => $invoice->receiver_info['address'] ?? ($invoice->contact->address ?? 'Adres Bulunamadı'),
            'mahalleSemtIlce' => $invoice->receiver_info['district'] ?? '',
            'sehir' => $invoice->receiver_info['city'] ?? 'Ankara', 
            'postaKodu' => '',
            'tel' => $invoice->contact->phone ?? '',
            'fax' => '',
            'eposta' => $invoice->contact->email ?? '',
            'websitesi' => '',
            'iadeTable' => [],
            'vergiCesidi' => ' ',
            'malHizmetTable' => $items,
            'tip' => 'İskonto',
            'matrah' => $subtotal,
            'malhizmetToplamTutari' => $subtotal,
            'toplamIskonto' => 0,
            'hesaplanankdv' => $taxAmount,
            'vergilerToplami' => $taxAmount,
            'vergilerDahilToplamTutar' => $grandTotal,
            'odenecekTutar' => $grandTotal,
            'not' => $invoice->notes ?? '',
            'siparisNumarasi' => '',
            'siparisTarihi' => '',
            'irsaliyeNumarasi' => '',
            'irsaliyeTarihi' => '',
            'fisNo' => '',
            'fisTarihi' => '',
            'fisSaati' => '',
            'fisTipi' => ' ',
            'zRaporNo' => '',
            'okcSeriNo' => ''
        ];
    }

    /**
     * Gelen faturaları portal üzerinden sorgular. (Simülasyon/Taslak)
     */
    public function fetchIncomingInvoices($startDate = null, $endDate = null)
    {
        if (!$this->token) {
            $this->login();
        }

        $startDate = $startDate ?? now()->subDays(7)->format('d/m/Y');
        $endDate = $endDate ?? now()->format('d/m/Y');

        $response = Http::withoutVerifying()->asForm()->post($this->baseUrl . '/earsiv-services/dispatch', [
            'cmd' => 'EARSIV_PORTAL_GELEN_FATURALARI_GETIR',
            'callid' => Str::uuid()->toString(),
            'token' => $this->token,
            'jp' => json_encode([
                'baslangic' => $startDate,
                'bitis' => $endDate
            ])
        ]);

        $result = $response->json();

        if (isset($result['error'])) {
            // Gerçek portalda veri yoksa boş dönebilir, hata fırlatmak yerine loglayıp boş dönelim
            \Illuminate\Support\Facades\Log::warning('GİB Gelen Fatura Sorgulama Hatası:', ['result' => $result]);
            return [];
        }

        return $result['data'] ?? [];
    }

    /**
     * Giden faturanın durumunu portal üzerinden sorgular.
     */
    public function checkInvoiceStatus($uuid)
    {
        if (!$this->token) {
            $this->login();
        }

        $response = Http::withoutVerifying()->asForm()->post($this->baseUrl . '/earsiv-services/dispatch', [
            'cmd' => 'EARSIV_PORTAL_FATURA_DURUM_SORGULA',
            'callid' => Str::uuid()->toString(),
            'token' => $this->token,
            'jp' => json_encode(['faturaUuid' => $uuid])
        ]);

        return $response->json();
    }
}
