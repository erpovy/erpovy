<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyManagementController extends Controller
{
    public function index()
    {
        $companies = Company::withCount('users')->latest()->paginate(10);
        return view('superadmin::companies.index', compact('companies'));
    }

    public function show(Company $company)
    {
        return view('superadmin::companies.show', compact('company'));
    }

    public function toggleModule(Request $request, Company $company)
    {
        $module = $request->input('module');
        $isGroup = $request->boolean('is_group', false);
        $settings = $company->settings ?? [];
        $activeModules = $settings['modules'] ?? [];
        
        if ($isGroup) {
            $items = explode(',', $module);
            $allActive = count(array_intersect($items, $activeModules)) === count($items);
            
            if ($allActive) {
                // Remove all items in this group
                $activeModules = array_diff($activeModules, $items);
            } else {
                // Add all items in this group
                $activeModules = array_unique(array_merge($activeModules, $items));
            }
        } else {
            if (in_array($module, $activeModules)) {
                $activeModules = array_filter($activeModules, fn($m) => $m !== $module);
            } else {
                $activeModules[] = $module;
            }
        }
        
        $settings['modules'] = array_values($activeModules);
        $company->settings = $settings;
        $company->save();
        
        return back()->with('success', "Modül durumu güncellendi.");
    }

    public function inspect(Company $company)
    {
        // Store original admin data to allow returning
        session([
            'is_inspecting' => true,
            'inspected_company_id' => $company->id,
            'inspected_company_name' => $company->name,
            'original_admin_id' => auth()->id()
        ]);

        return redirect()->route('dashboard')->with('info', "{$company->name} paneli gözetim modunda açıldı.");
    }

    public function updateLocation(Request $request, Company $company)
    {
        $validated = $request->validate([
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
        ]);

        $settings = $company->settings ?? [];
        $settings['country'] = $validated['country'];
        $settings['city'] = $validated['city'];
        
        $company->settings = $settings;
        $company->save();

        return back()->with('success', 'Konum ayarları güncellendi.');
    }

    public function edit(Company $company)
    {
        $menuGroups = [
            'General' => [
                'name' => 'Genel',
                'icon' => 'dashboard',
                'is_core' => true,
                'items' => [
                    'dashboard' => 'Özet (Dashboard)',
                    'activities' => 'Aktiviteler',
                ]
            ],
            'Accounting' => [
                'name' => 'Muhasebe',
                'icon' => 'account_balance',
                'is_core' => true,
                'items' => [
                    'accounting.dashboard' => 'Muhasebe Özeti',
                    'accounting.accounts' => 'Hesap Planı',
                    'accounting.transactions' => 'Fiş İşlemleri',
                    'accounting.invoices' => 'Faturalar',
                    'accounting.templates' => 'Fatura Şablonları',
                    'accounting.cash_bank' => 'Kasa/Banka',
                    'accounting.portfolio' => 'Çek/Senet',
                    'accounting.collections' => 'Ödeme Al (Tahsilat)',
                    'accounting.payments' => 'Ödeme Yap (Tediye)',
                    'accounting.reports' => 'Finansal Raporlar',
                ]
            ],
            'Sales' => [
                'name' => 'Satış',
                'icon' => 'point_of_sale',
                'items' => [
                    'sales.crm_sync' => 'Müşteri İlişkileri (Sync)',
                    'sales.list' => 'Satış Listesi',
                    'sales.quotes' => 'Teklif Hazırla',
                    'sales.pos' => 'Satış Noktası (POS)',
                    'sales.subscriptions' => 'Abonelikler',
                    'sales.rentals' => 'Kiralama',
                ]
            ],
            'CRM' => [
                'name' => 'CRM',
                'icon' => 'groups',
                'items' => [
                    'crm.contacts' => 'Kişiler & Firmalar',
                    'crm.leads' => 'Potansiyel Müşteriler',
                    'crm.deals' => 'Anlaşmalar',
                    'crm.contracts' => 'Sözleşmeler',
                ]
            ],
            'Inventory' => [
                'name' => 'Stok Yönetimi',
                'icon' => 'inventory_2',
                'items' => [
                    'inventory.analytics' => 'Stok Analitik',
                    'inventory.products' => 'Ürünler',
                    'inventory.categories' => 'Kategoriler',
                    'inventory.brands' => 'Markalar',
                    'inventory.units' => 'Ölçü Birimleri',
                    'inventory.warehouses' => 'Depolar',
                ]
            ],
            'Manufacturing' => [
                'name' => 'Üretim',
                'icon' => 'factory',
                'items' => [
                    'manufacturing.dashboard' => 'Üretim Özeti',
                    'manufacturing.orders' => 'İş Emirleri',
                    'manufacturing.mrp' => 'MRP (Malzeme Planlama)',
                    'manufacturing.mes' => 'MES (Yönetim Sistemi)',
                    'manufacturing.plm' => 'PLM (Ürün Yaşam Döngüsü)',
                    'manufacturing.quality' => 'Kalite Kontrol',
                    'manufacturing.shopfloor' => 'Üretim Alanı',
                    'manufacturing.maintenance' => 'Bakım Yönetimi',
                ]
            ],
            'HumanResources' => [
                'name' => 'İnsan Kaynakları',
                'icon' => 'badge',
                'items' => [
                    'hr.dashboard' => 'İK Özeti',
                    'hr.departments' => 'Departmanlar',
                    'hr.employees' => 'Personel Listesi',
                    'hr.leaves' => 'İzin Takvimi',
                    'hr.fleet' => 'Filo Yönetimi',
                ]
            ],
            'FixedAssets' => [
                'name' => 'Demirbaş Yönetimi',
                'icon' => 'inventory_2',
                'items' => [
                    'fixedassets.index' => 'Demirbaş Listesi',
                    'fixedassets.create' => 'Yeni Demirbaş',
                    'fixedassets.categories' => 'Kategoriler',
                ]
            ],
            'MarketSetup' => [
                'name' => 'Market ve Kurulum',
                'icon' => 'settings_suggest',
                'items' => [
                    'market.index' => 'Modül Market',
                    'setup.accounting' => 'Muhasebe Kurulumu',
                    'setup.invoice' => 'Fatura Kurulumu',
                    'setup.crm' => 'CRM Kurulumu',
                ]
            ]
        ];

        $registrant = $company->users()->oldest()->first();

        return view('superadmin::companies.edit', compact('company', 'menuGroups', 'registrant'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255|unique:companies,domain,' . $company->id,
            'status' => 'required|in:active,suspended',
        ]);

        $company->update($validated);

        return redirect()->route('superadmin.companies.index')->with('success', 'Şirket başarıyla güncellendi.');
    }

    public function destroy(Company $company)
    {
        // Prevent deletion if company has users
        if ($company->users()->exists()) {
            return back()->with('error', 'Bu şirkete ait kullanıcılar bulunduğu için silinemez.');
        }

        $company->delete();

        return redirect()->route('superadmin.companies.index')->with('success', 'Şirket başarıyla silindi.');
    }

    public function stopInspection()
    {
        session()->forget(['is_inspecting', 'inspected_company_id', 'inspected_company_name', 'original_admin_id']);

        return redirect()->route('superadmin.index')->with('success', 'Gözetim modu sonlandırıldı.');
    }
}
