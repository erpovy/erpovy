<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\InvoiceTemplate;
use Illuminate\Support\Facades\DB;

class InvoiceTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = InvoiceTemplate::latest()->paginate(10);
        return view('accounting::invoice-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Default template content for new templates
        $defaultContent = $this->getDefaultTemplateContent();
        return view('accounting::invoice-templates.create', compact('defaultContent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'html_content' => 'required|string',
            'is_default' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            if ($validated['is_default'] ?? false) {
                // Unset other defaults
                InvoiceTemplate::where('company_id', auth()->user()->company_id)
                    ->update(['is_default' => false]);
            }

            InvoiceTemplate::create([
                'company_id' => auth()->user()->company_id,
                'name' => $validated['name'],
                'html_content' => $validated['html_content'],
                'is_default' => $validated['is_default'] ?? false,
            ]);

            DB::commit();
            return redirect()->route('accounting.invoice-templates.index')->with('success', 'Fatura şablonu başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Şablon oluşturulurken bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $template = InvoiceTemplate::findOrFail($id);
        return view('accounting::invoice-templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $template = InvoiceTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'html_content' => 'required|string',
            'is_default' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            if ($validated['is_default'] ?? false) {
                // Unset other defaults
                InvoiceTemplate::where('company_id', auth()->user()->company_id)
                    ->where('id', '!=', $id)
                    ->update(['is_default' => false]);
            }

            $template->update([
                'name' => $validated['name'],
                'html_content' => $validated['html_content'],
                'is_default' => $validated['is_default'] ?? false,
            ]);

            DB::commit();
            return back()->with('success', 'Fatura şablonu başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Güncelleme hatası: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $template = InvoiceTemplate::findOrFail($id);
        
        if ($template->is_default) {
            return back()->with('error', 'Varsayılan şablon silinemez. Önce başka bir şablonu varsayılan yapın.');
        }

        $template->delete();
        return redirect()->route('accounting.invoice-templates.index')->with('success', 'Şablon silindi.');
    }

    /**
     * Preview the template.
     */
    public function preview(Request $request)
    {
        $html = $request->input('html_content');
        
        // Creating a dummy invoice object for preview
        $invoice = new \stdClass();
        $invoice->invoice_number = 'INV-20261025-001';
        $invoice->issue_date = now();
        $invoice->due_date = now()->addDays(7);
        $invoice->created_at = now();
        $invoice->invoice_scenario = 'E-ARŞİV FATURA';
        $invoice->ettn = '550e8400-e29b-41d4-a716-446655440000';
        $invoice->subtotal = 1000.00;
        $invoice->vat_total = 180.00;
        $invoice->grand_total = 1180.00;
        // Old properties kept for compatibility if needed, but template uses new ones
        $invoice->total_amount = 1180.00;
        $invoice->tax_amount = 180.00;
        
        $invoice->company = new \stdClass();
        $invoice->company->name = 'Teknoloji Ltd. Şti.';
        $invoice->company->settings = [
            'address' => 'Levent Mah. Cömert Sk. No:1 Yapı Kredi Plaza C Blok Beşiktaş / İstanbul',
            'tax_office' => 'Boğaziçi V.D.',
            'tax_number' => '1234567890'
        ];
        
        $invoice->contact = new \stdClass();
        $invoice->contact->name = 'Örnek Müşteri A.Ş.';
        $invoice->contact->address = 'Teknoloji Mah. İnovasyon Cad. No:1 İstanbul';
        $invoice->contact->tax_office = 'Marmara Kurumlar V.D.';
        $invoice->contact->tax_number = '1234567890';
        $invoice->contact->email = 'info@ornek.com';
        $invoice->contact->phone = '0212 123 45 67';
        
        $item1 = new \stdClass();
        $item1->description = 'Web Yazılım Geliştirme Hizmeti';
        $item1->quantity = 1;
        $item1->unit_price = 1000.00;
        $item1->vat_rate = 18;
        $item1->total = 1000.00;
        // Tax included total for line? Template uses number_format($item->total)
        // Let's assume item->total is line total.
        
        $item1->product = new \stdClass();
        $item1->product->code = 'SRV-001';
        $item1->product->name = 'Web Yazılım Geliştirme Hizmeti';
        $item1->product->unit = new \stdClass();
        $item1->product->unit->name = 'Adet';
        
        $invoice->items = collect([$item1]);

        try {
            // Using Blade::render to render the string template
            $rendered = \Illuminate\Support\Facades\Blade::render($html, ['invoice' => $invoice]);
            return response()->json(['html' => $rendered]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    private function getDefaultTemplateContent()
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Fatura {{ $invoice->invoice_number }}</title>
    <!-- Tailwind CSS for Preview -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#136dec",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; }
            .invoice-content { padding: 0 !important; width: 100% !important; max-width: 100% !important; }
        }
        body {
            background-color: white;
            color: #0f172a;
        }
    </style>
</head>
<body class="font-display antialiased">
<div class="min-h-screen w-full flex flex-col items-center">
    <div class="invoice-content w-full max-w-[1000px] px-8 py-12 md:px-16 md:py-16">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-12">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white shadow-sm">
                        <span class="material-symbols-outlined text-3xl">business</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black tracking-tighter text-slate-900 uppercase leading-none">
                            {{ $invoice->company->name ?? 'FİRMA ADI' }}
                        </h1>
                        <p class="text-[10px] text-primary font-bold tracking-[0.2em] uppercase mt-1">
                            {{ $invoice->invoice_scenario ?? 'E-ARŞİV FATURA' }}
                        </p>
                    </div>
                </div>
                <div class="max-w-xs space-y-1">
                    <p class="text-sm text-slate-600 leading-tight">
                        {{ $invoice->company->settings['address'] ?? 'Şirket Adresi Bulunamadı' }}
                    </p>
                    <div class="pt-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Vergi Dairesi / VKN</p>
                        <p class="text-sm font-medium text-slate-800">
                            {{ $invoice->company->settings['tax_office'] ?? '-' }} / {{ $invoice->company->settings['tax_number'] ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col items-end text-right gap-4">
                <h2 class="text-5xl font-black text-slate-100 tracking-tighter leading-none select-none">FATURA</h2>
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-lg min-w-[280px]">
                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase text-left">Fatura No</span>
                        <span class="text-sm font-bold text-slate-900 tracking-tight">{{ $invoice->invoice_number }}</span>
                        
                        <span class="text-[10px] font-bold text-slate-400 uppercase text-left">Tarih</span>
                        <span class="text-sm font-medium text-slate-900">{{ $invoice->issue_date->format('d.m.Y') }}</span>
                        
                        <span class="text-[10px] font-bold text-slate-400 uppercase text-left">Saat</span>
                        <span class="text-sm font-medium text-slate-900">{{ $invoice->created_at->format('H:i:s') }}</span>
                        
                        <span class="text-[10px] font-bold text-slate-400 uppercase text-left">ETTN</span>
                        <span class="text-[9px] font-mono text-slate-500 break-all leading-tight text-right uppercase">
                            {{ $invoice->ettn }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Receiver Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12 py-8 border-y border-slate-100">
            <div class="flex flex-col gap-3">
                <h3 class="text-[11px] font-black text-primary uppercase tracking-[0.2em]">Alıcı Bilgileri</h3>
                <div class="space-y-1">
                    <p class="text-xl font-bold text-slate-900">{{ $invoice->contact->name ?? 'Müşteri Adı' }}</p>
                    <p class="text-sm text-slate-600 leading-relaxed max-w-sm">
                        {{ $invoice->contact->address ?? 'Adres bilgisi mevcut değil' }}
                    </p>
                </div>
                <div class="mt-2 grid grid-cols-2 max-w-sm">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Vergi Dairesi</p>
                        <p class="text-sm font-medium text-slate-800">{{ $invoice->contact->tax_office ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">VKN / TCKN</p>
                        <p class="text-sm font-medium text-slate-800">{{ $invoice->contact->tax_number ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col md:items-end justify-center">
                <div class="bg-slate-50 rounded-lg px-5 py-4 border border-slate-100 inline-block">
                    <div class="flex items-center gap-3 text-slate-700">
                        <span class="material-symbols-outlined text-primary">payments</span>
                        <div class="text-left">
                            <p class="text-[10px] font-bold uppercase opacity-60 tracking-wider">Ödeme Şekli</p>
                            <p class="text-sm font-bold">Banka Havalesi / EFT</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto mb-10">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-slate-900 text-slate-900">
                        <th class="py-3 px-2 text-[11px] font-black uppercase tracking-wider">S/No</th>
                        <th class="py-3 px-2 text-[11px] font-black uppercase tracking-wider">Hizmet / Ürün</th>
                        <th class="py-3 px-2 text-[11px] font-black uppercase tracking-wider text-center">Miktar</th>
                        <th class="py-3 px-2 text-[11px] font-black uppercase tracking-wider text-center">Birim</th>
                        <th class="py-3 px-2 text-[11px] font-black uppercase tracking-wider text-right">Birim Fiyat</th>
                        <th class="py-3 px-2 text-[11px] font-black uppercase tracking-wider text-center">KDV %</th>
                        <th class="py-3 px-2 text-[11px] font-black uppercase tracking-wider text-right">Tutar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($invoice->items as $index => $item)
                    <tr>
                        <td class="py-5 px-2 text-sm text-slate-500 font-medium">{{ $index + 1 }}</td>
                        <td class="py-5 px-2 text-sm font-bold text-slate-900">
                            {{ $item->product->name ?? $item->description }}
                        </td>
                        <td class="py-5 px-2 text-sm text-slate-700 text-center">
                            {{ number_format($item->quantity, 2, ',', '.') }}
                        </td>
                        <td class="py-5 px-2 text-sm text-slate-700 text-center">
                            {{ $item->product->unit->name ?? 'Adet' }}
                        </td>
                        <td class="py-5 px-2 text-sm text-slate-700 text-right">
                            {{ number_format($item->unit_price, 2, ',', '.') }} ₺
                        </td>
                        <td class="py-5 px-2 text-sm text-slate-700 text-center">
                            %{{ number_format($item->vat_rate ?? 18, 0) }}
                        </td>
                        <td class="py-5 px-2 text-sm font-bold text-slate-900 text-right">
                            {{ number_format($item->total, 2, ',', '.') }} ₺
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="flex flex-col md:flex-row gap-12 justify-between items-start">
            <div class="flex-1 w-full max-w-md">
                <div class="p-5 bg-slate-50 rounded-lg border border-slate-100 mb-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Yazı İle</p>
                    <p class="text-sm font-bold text-slate-800 italic">
                        <!-- Number to Words Placeholder -->
                        #{{ $invoice->grand_total }}#
                    </p>
                </div>
                <div class="text-[11px] text-slate-500 space-y-2 leading-relaxed">
                    <p class="flex items-start gap-2"><span class="text-primary font-bold">•</span> E-Arşiv Fatura, kağıt fatura yerine geçer.</p>
                    <p class="flex items-start gap-2"><span class="text-primary font-bold">•</span> Bu fatura elektronik ortamda oluşturulmuş ve imzalanmıştır.</p>
                </div>
            </div>
            
            <div class="w-full md:w-80 space-y-4">
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Ara Toplam</span>
                        <span class="text-slate-900 font-bold">{{ number_format($invoice->subtotal, 2, ',', '.') }} ₺</span>
                    </div>
                    @if(isset($invoice->discount_total) && $invoice->discount_total > 0)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">İskonto Toplamı</span>
                        <span class="text-slate-900 font-bold">-{{ number_format($invoice->discount_total, 2, ',', '.') }} ₺</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">KDV Toplamı</span>
                        <span class="text-slate-900 font-bold">{{ number_format($invoice->vat_total, 2, ',', '.') }} ₺</span>
                    </div>
                </div>
                <div class="pt-4 border-t-2 border-slate-900">
                    <div class="flex justify-between items-center">
                        <span class="text-base font-black text-slate-900 uppercase tracking-tighter">Genel Toplam</span>
                        <span class="text-2xl font-black text-primary">{{ number_format($invoice->grand_total, 2, ',', '.') }} ₺</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Signatures -->
        <div class="mt-20 grid grid-cols-2 gap-12 pt-10 border-t border-slate-100">
            <div class="text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-12">Teslim Eden</p>
                <div class="mx-auto w-40 border-b border-slate-300"></div>
                <p class="text-[10px] text-slate-400 mt-2 uppercase">Kaşe / İmza</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-12">Teslim Alan</p>
                <div class="mx-auto w-40 border-b border-slate-300"></div>
                <p class="text-[10px] text-slate-400 mt-2 uppercase">İmza</p>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="no-print mt-20 flex justify-center opacity-40 text-[9px] font-bold text-slate-500 uppercase tracking-[0.25em] gap-6">
            <span>E-Fatura Mükellefi</span>
            <span class="text-slate-300">|</span>
            <span>T.C. Hazine ve Maliye Bakanlığı</span>
            <span class="text-slate-300">|</span>
            <span>GIB Standartlarına Uygundur</span>
        </div>
    </div>
</div>
</body>
</html>
HTML;
    }
}
