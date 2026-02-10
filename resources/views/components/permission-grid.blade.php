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
