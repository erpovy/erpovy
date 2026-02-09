<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Account;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Account::query();

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $accounts = $query->orderBy('code')->paginate(50)->withQueryString();

        // Stats
        $stats = [
            'total' => Account::count(),
            'assets' => Account::where('type', 'asset')->count(),
            'liabilities' => Account::where('type', 'liability')->count(),
            'other' => Account::whereNotIn('type', ['asset', 'liability'])->count(),
        ];

        return view('accounting::accounts.index', compact('accounts', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('accounting::accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
        ]);

        $validated['company_id'] = auth()->user()->company_id ?? 1;
        $validated['is_active'] = true;

        Account::create($validated);

        return redirect()->route('accounting.accounts.index')->with('success', 'Hesap başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        return view('accounting::accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:accounts,code,' . $account->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
        ]);

        $account->update($validated);

        return redirect()->route('accounting.accounts.index')->with('success', 'Hesap başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        // Check if account has transactions before deleting (optional but recommended)
        if ($account->ledgerEntries()->exists()) {
            return back()->with('error', 'Bu hesaba ait işlemler bulunduğu için silinemez.');
        }

        $account->delete();

        return redirect()->route('accounting.accounts.index')->with('success', 'Hesap başarıyla silindi.');
    }
    /**
     * Import standard accounts (TDHP).
     */
    public function importDefaults()
    {
        $companyId = auth()->user()->company_id;
        
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

        $addedCount = 0;
        $existingCount = 0;

        foreach ($accounts as $data) {
            $exists = Account::where('company_id', $companyId)->where('code', $data['code'])->exists();
            if ($exists) {
                $existingCount++;
                continue;
            }

            Account::create([
                'company_id' => $companyId,
                'code' => $data['code'],
                'name' => $data['name'],
                'type' => $data['type'],
                'level' => $data['level'],
                'description' => $data['description'],
                'is_system' => true,
                'is_active' => true,
            ]);
            $addedCount++;
        }

        if ($addedCount == 0 && $existingCount > 0) {
            return back()->with('warning', 'Tüm standart hesaplar zaten listenizde ekli.');
        }

        return back()->with('success', "{$addedCount} adet standart hesap eklendi. ({$existingCount} hesap zaten mevcuttu)");
    }
}
