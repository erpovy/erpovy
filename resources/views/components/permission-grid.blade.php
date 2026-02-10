@props(['permissions', 'selectedPermissions' => []])

@php
    $resources = [
        'users' => ['label' => 'Kullanıcılar', 'module' => 'Sistem'],
        'roles' => ['label' => 'Roller', 'module' => 'Sistem'],
        'permissions' => ['label' => 'Yetkiler', 'module' => 'Sistem'],
        'companies' => ['label' => 'Şirketler', 'module' => 'Sistem'],
        'settings' => ['label' => 'Ayarlar', 'module' => 'Sistem'],
        
        'invoices' => ['label' => 'Faturalar', 'module' => 'Muhasebe'],
        'cash-bank' => ['label' => 'Kasa/Banka', 'module' => 'Muhasebe'],
        'reports' => ['label' => 'Raporlar', 'module' => 'Muhasebe'],
        
        'contacts' => ['label' => 'Müşteriler/Rehber', 'module' => 'CRM'],
        
        'employees' => ['label' => 'Personeller', 'module' => 'İK'],
        'departments' => ['label' => 'Departmanlar', 'module' => 'İK'],
        'leaves' => ['label' => 'İzinler', 'module' => 'İK'],
        'fleet' => ['label' => 'Araç/Filo', 'module' => 'İK'],
        
        'products' => ['label' => 'Ürünler', 'module' => 'Envanter'],
        'categories' => ['label' => 'Kategoriler', 'module' => 'Envanter'],
        'stock' => ['label' => 'Stok Yönetimi', 'module' => 'Envanter'],
        'stock-movements' => ['label' => 'Stok Hareketleri', 'module' => 'Envanter'],
        
        'subscriptions' => ['label' => 'Abonelikler', 'module' => 'Satış'],
        'rentals' => ['label' => 'Kiralamalar', 'module' => 'Satış'],
        
        'bom' => ['label' => 'Ürün Reçeteleri', 'module' => 'Üretim'],
    ];

    $actions = [
        'view' => 'Görüntüle',
        'create' => 'Ekle',
        'edit' => 'Düzenle',
        'delete' => 'Sil',
    ];

    $groupedPermissions = [];
    foreach ($permissions ?? [] as $permission) {
        $parts = explode(' ', $permission->name);
        $action = $parts[0] ?? '';
        $resource = $parts[1] ?? '';
        
        if (isset($resources[$resource]) && isset($actions[$action])) {
            $groupedPermissions[$resource][$action] = $permission->id;
        } else {
            // "manage settings" like special cases
             if ($action === 'manage' && isset($resources[$resource])) {
                 $groupedPermissions[$resource]['manage'] = $permission->id;
             }
        }
    }
@endphp

@if(empty($groupedPermissions))
    <div class="col-span-full bg-amber-500/10 border border-amber-500/20 rounded-2xl p-8 text-center space-y-4 my-8">
        <div class="w-16 h-16 bg-amber-500/20 rounded-full flex items-center justify-center mx-auto text-amber-500 shadow-lg shadow-amber-500/10 mb-4">
            <span class="material-symbols-outlined text-4xl">warning</span>
        </div>
        <h3 class="text-xl font-bold text-white">Standart Yetki Tanımları Eksik</h3>
        <p class="text-gray-400 max-w-md mx-auto">
            Sistem yetki tanımları henüz veritabanına yüklenmemiş. Kutucukların görünebilmesi ve seçim yapabilmeniz için tanımları yüklemeniz gerekmektedir.
        </p>
        <div class="pt-4">
            <form action="{{ route('hr.permissions.seed') }}" method="POST">
                @csrf
                <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-black font-bold rounded-xl transition-all shadow-lg shadow-amber-500/20 flex items-center gap-2 mx-auto scale-110">
                    <span class="material-symbols-outlined">database</span>
                    STANDART TANIMLARI YÜKLE
                </button>
            </form>
        </div>
        <p class="text-xs text-amber-500/60 pt-2 italic">Not: Bu işlem sadece bir kez yapılmalıdır.</p>
    </div>
@else
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    @foreach(collect($resources)->groupBy('module') as $module => $moduleResources)
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-primary uppercase tracking-wider px-3 border-l-4 border-primary bg-primary/5 py-1 rounded-r-lg">
                {{ $module }}
            </h3>
            
            <div class="overflow-hidden rounded-xl border border-white/10 bg-white/5">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-white/10 text-[10px] text-gray-400 uppercase">
                        <tr>
                            <th class="px-4 py-2.5 font-semibold">Kaynak</th>
                            @foreach($actions as $action => $label)
                                <th class="px-2 py-2.5 text-center font-semibold">{{ $label }}</th>
                            @endforeach
                            <th class="px-2 py-2.5 text-center font-semibold text-[9px]">Yönet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($moduleResources as $key => $res)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-4 py-3 font-medium text-white group-hover:text-primary transition-colors text-xs">{{ $res['label'] }}</td>
                                @foreach($actions as $action => $label)
                                    <td class="px-2 py-3 text-center">
                                        @if(isset($groupedPermissions[$key][$action]))
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $groupedPermissions[$key][$action] }}"
                                                   class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary focus:ring-primary/30 transition-all cursor-pointer"
                                                   {{ in_array($groupedPermissions[$key][$action], $selectedPermissions) ? 'checked' : '' }}>
                                        @else
                                            <span class="text-gray-700">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-2 py-3 text-center">
                                    @if(isset($groupedPermissions[$key]['manage']))
                                         <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $groupedPermissions[$key]['manage'] }}"
                                               class="w-4 h-4 rounded border-amber-500/30 bg-amber-500/5 text-amber-500 focus:ring-amber-500/30 transition-all cursor-pointer"
                                               {{ in_array($groupedPermissions[$key]['manage'], $selectedPermissions) ? 'checked' : '' }}>
                                    @else
                                        <span class="text-gray-700">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endif
