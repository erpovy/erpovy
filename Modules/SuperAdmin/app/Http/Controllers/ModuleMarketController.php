<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModuleMarketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = [
            [
                'id' => 'sales',
                'name' => 'Satış (Sales)',
                'description' => 'Satış siparişleri, teklif yönetimi, müşteri bazlı fiyatlandırma ve sevkiyat takibi.',
                'icon' => 'payments',
                'color' => 'rose',
                'style_classes' => 'bg-rose-500/20 text-rose-400 ring-rose-500/30 group-hover:bg-rose-500',
                'version' => 'v1.0.0',
                'status' => 'active',
                'is_installed' => true,
            ],
            [
                'id' => 'accounting',
                'name' => 'Muhasebe (Accounting)',
                'description' => 'Gelir/Gider takibi, faturalama, cari hesap yönetimi ve finansal raporlama.',
                'icon' => 'account_balance',
                'color' => 'blue', // For logic if needed
                'style_classes' => 'bg-blue-500/20 text-blue-400 ring-blue-500/30 group-hover:bg-blue-500', // Explicit classes
                'version' => 'v2.1.0',
                'status' => 'active',
                'is_installed' => true,
            ],
            [
                'id' => 'crm',
                'name' => 'Müşteri İlişkileri (CRM)',
                'description' => 'Müşteri takibi, satış fırsatları, teklifler ve sözleşme yönetimi.',
                'icon' => 'groups',
                'color' => 'indigo',
                'style_classes' => 'bg-indigo-500/20 text-indigo-400 ring-indigo-500/30 group-hover:bg-indigo-500',
                'version' => 'v1.8.5',
                'status' => 'active',
                'is_installed' => true,
            ],
            [
                'id' => 'inventory',
                'name' => 'Stok Yönetimi (Inventory)',
                'description' => 'Ürün/Hizmet yönetimi, stok takibi, depo transferleri ve sayım işlemleri.',
                'icon' => 'inventory_2',
                'color' => 'orange',
                'style_classes' => 'bg-orange-500/20 text-orange-400 ring-orange-500/30 group-hover:bg-orange-500',
                'version' => 'v1.5.2',
                'status' => 'active',
                'is_installed' => true,
            ],
            [
                'id' => 'manufacturing',
                'name' => 'Üretim (Manufacturing)',
                'description' => 'MRP, MES, PLM, Kalite Kontrol ve Üretim bandı yönetimi.',
                'icon' => 'factory',
                'color' => 'purple',
                'style_classes' => 'bg-purple-500/20 text-purple-400 ring-purple-500/30 group-hover:bg-purple-500',
                'version' => 'v1.2.0',
                'status' => 'active',
                'is_installed' => true,
            ],
             [
                'id' => 'hr',
                'name' => 'İnsan Kaynakları (HR)',
                'description' => 'Personel yönetimi, izinler, departmanlar ve bordro işlemleri.',
                'icon' => 'badge',
                'color' => 'pink',
                'style_classes' => 'bg-pink-500/20 text-pink-400 ring-pink-500/30 group-hover:bg-pink-500',
                'version' => 'v1.1.0',
                'status' => 'active',
                'is_installed' => true,
            ],
            [
                'id' => 'ecommerce',
                'name' => 'E-Ticaret Entegrasyonu',
                'description' => 'Pazaryeri entegrasyonları, online sipariş yönetimi ve sanal mağaza.',
                'icon' => 'shopping_cart',
                'color' => 'green',
                'style_classes' => 'bg-green-500/20 text-green-400 ring-green-500/30 group-hover:bg-green-500',
                'version' => 'v0.5.0',
                'status' => 'coming_soon',
                'is_installed' => false,
            ],
             [
                'id' => 'project',
                'name' => 'Proje Yönetimi',
                'description' => 'Görev takibi, zaman çizelgeleri, agile/scrum panoları ve ekip işbirliği.',
                'icon' => 'view_kanban',
                'color' => 'teal',
                'style_classes' => 'bg-teal-500/20 text-teal-400 ring-teal-500/30 group-hover:bg-teal-500',
                'version' => 'v0.9.0',
                'status' => 'coming_soon',
                'is_installed' => false,
            ],
            [
                'id' => 'purchasing',
                'name' => 'Satın Alma (Purchasing)',
                'description' => 'Tedarikçi yönetimi, satınalma talepleri, sipariş onay süreçleri ve teklif toplama.',
                'icon' => 'shopping_basket',
                'color' => 'sky',
                'style_classes' => 'bg-sky-500/20 text-sky-400 ring-sky-500/30 group-hover:bg-sky-500',
                'version' => 'v1.0.0',
                'status' => 'active',
                'is_installed' => true,
            ],
            [
                'id' => 'bi',
                'name' => 'İş Zekası (BI & Reporting)',
                'description' => 'Gelişmiş veri analizi, özel rapor oluşturucu, görsel panolar ve KPI takibi.',
                'icon' => 'analytics',
                'color' => 'violet',
                'style_classes' => 'bg-violet-500/20 text-violet-400 ring-violet-500/30 group-hover:bg-violet-500',
                'version' => 'v0.5.0',
                'status' => 'coming_soon',
                'is_installed' => false,
            ],
            [
                'id' => 'fixedassets',
                'name' => 'Demirbaş Yönetimi (Fixed Assets)',
                'description' => 'Şirket varlıkları, amortisman takibi, zimmetleme ve bakım periyotları.',
                'icon' => 'web_asset',
                'color' => 'emerald',
                'style_classes' => 'bg-emerald-500/20 text-emerald-400 ring-emerald-500/30 group-hover:bg-emerald-500',
                'version' => 'v1.0.0',
                'status' => 'active',
                'is_installed' => true,
            ],
            [
                'id' => 'dms',
                'name' => 'Doküman Yönetimi (DMS)',
                'description' => 'Dijital arşiv, versiyon kontrolü, yetkilendirilmiş klasör yapısı ve güvenli paylaşım.',
                'icon' => 'folder_open',
                'color' => 'amber',
                'style_classes' => 'bg-amber-500/20 text-amber-400 ring-amber-500/30 group-hover:bg-amber-500',
                'version' => 'v0.7.5',
                'status' => 'coming_soon',
                'is_installed' => false,
            ],
            [
                'id' => 'helpdesk',
                'name' => 'Destek & Talep Yönetimi',
                'description' => 'İç/Dış destek talepleri, bilet (ticket) sistemi, SLA takibi ve çözüm tabanı.',
                'icon' => 'live_help',
                'color' => 'rose',
                'style_classes' => 'bg-rose-500/20 text-rose-400 ring-rose-500/30 group-hover:bg-rose-500',
                'version' => 'v0.9.2',
                'status' => 'coming_soon',
                'is_installed' => false,
            ],
            [
                'id' => 'wms',
                'name' => 'Gelişmiş Depo (WMS)',
                'description' => 'El terminalleri, raf/adresleme sistemleri, barkod/QR okuma ve rota optimizasyonu.',
                'icon' => 'qr_code_scanner',
                'color' => 'lime',
                'style_classes' => 'bg-lime-500/20 text-lime-400 ring-lime-500/30 group-hover:bg-lime-500',
                'version' => 'v1.0.0',
                'status' => 'coming_soon',
                'is_installed' => false,
            ],
            [
                'id' => 'b2b',
                'name' => 'B2B Bayi Portalı',
                'description' => 'Bayiler için özel sipariş ekranları, cari ekstre görüntüleme ve online tahsilat.',
                'icon' => 'store',
                'color' => 'cyan',
                'style_classes' => 'bg-cyan-500/20 text-cyan-400 ring-cyan-500/30 group-hover:bg-cyan-500',
                'version' => 'v0.6.0',
                'status' => 'coming_soon',
                'is_installed' => false,
            ],
            [
                'id' => 'lms',
                'name' => 'Eğitim Yönetimi (LMS)',
                'description' => 'Personel oryantasyonu, video eğitimler, sınavlar ve gelişim takibi.',
                'icon' => 'school',
                'color' => 'fuchsia',
                'style_classes' => 'bg-fuchsia-500/20 text-fuchsia-400 ring-fuchsia-500/30 group-hover:bg-fuchsia-500',
                'version' => 'v0.4.5',
                'status' => 'coming_soon',
                'is_installed' => false,
            ],
            [
                'id' => 'iot',
                'name' => 'IoT Hub & Entegrasyon',
                'description' => 'Makine verilerini canlı izleme, sensör entegrasyonları ve kestirimci bakım sinyalleri.',
                'icon' => 'hub',
                'color' => 'amber',
                'style_classes' => 'bg-amber-500/20 text-amber-400 ring-amber-500/30 group-hover:bg-amber-500',
                'version' => 'v1.0.0',
                'status' => 'coming_soon',
                'is_installed' => false,
            ],
            [
                'id' => 'logistics',
                'name' => 'Lojistik & Sevkiyat',
                'description' => 'Araç yükleme planı, rota optimizasyonu, kargo entegrasyonları ve canlı takip.',
                'icon' => 'local_shipping',
                'color' => 'slate',
                'style_classes' => 'bg-slate-500/20 text-slate-400 ring-slate-500/30 group-hover:bg-slate-500',
                'version' => 'v0.7.0',
                'status' => 'active',
                'is_installed' => true,
            ],
            [
                'id' => 'legal',
                'name' => 'Hukuk & KVKK Yönetimi',
                'description' => 'Dava dosyaları, sözleşme hukuku, KVKK süreçleri ve uyumluluk denetimleri.',
                'icon' => 'gavel',
                'color' => 'red',
                'style_classes' => 'bg-red-500/20 text-red-400 ring-red-500/30 group-hover:bg-red-500',
                'version' => 'v0.5.5',
                'status' => 'coming_soon',
                'is_installed' => false,
            ],
            [
                'id' => 'servicemanagement',
                'name' => 'Servis / Bakım (Service/Maintenance)',
                'description' => 'Araç servis, bakım, tamir ve parça değişim süreçlerinin yönetimi.',
                'icon' => 'build',
                'color' => 'amber',
                'style_classes' => 'bg-amber-500/20 text-amber-400 ring-amber-500/30 group-hover:bg-amber-500',
                'version' => 'v1.0.0',
                'status' => 'active',
                'is_installed' => true,
            ],
        ];

        // Sort modules: Core > Active > Inactive
        $coreIds = ['accounting', 'crm', 'inventory', 'hr', 'sales'];
        usort($modules, function ($a, $b) use ($coreIds) {
            $aCore = in_array($a['id'], $coreIds) ? 0 : 1;
            $bCore = in_array($b['id'], $coreIds) ? 0 : 1;

            if ($aCore !== $bCore) {
                return $aCore - $bCore;
            }

            $aActive = $a['is_installed'] ? 0 : 1;
            $bActive = $b['is_installed'] ? 0 : 1;

            if ($aActive !== $bActive) {
                return $aActive - $bActive;
            }

            return strcmp($a['name'], $b['name']);
        });

        return view('superadmin::market.index', compact('modules'));
    }
}
