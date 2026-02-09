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
            $items[] = [
                'malHizmet' => $item->description,
                'miktar' => $item->quantity,
                'birim' => 'C62', // Adet (Varsayılan) - Geliştirilebilir
                'birimFiyat' => $item->unit_price,
                'fiyat' => $item->quantity * $item->unit_price,
                'iskontoOrani' => 0,
                'iskontoTutari' => 0,
                'iskontoNedeni' => '',
                'malHizmetTutari' => $item->quantity * $item->unit_price,
                'kdvOrani' => $item->tax_rate,
                'kdvTutari' => ($item->quantity * $item->unit_price) * ($item->tax_rate / 100),
                'vergininKdvTutari' => 0,
                'ozelMatrahTutari' => 0,
                'hesaplananotvtevkifatabitutari' => 0
            ];
        }

        $grandTotal = $invoice->grand_total;
        $taxAmount = $invoice->tax_amount; // vat_total actually
        $subtotal = $invoice->subtotal;

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
            'bulvarcaddesokak' => $invoice->contact->address ?? 'Adres Bulunamadı',
            'mahalleSemtIlce' => '',
            'sehir' => 'Ankara', // Adres ayrıştırması olmadığı için varsayılan
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
}
