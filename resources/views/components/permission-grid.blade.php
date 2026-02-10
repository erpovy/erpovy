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
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8" x-data="{ 
    selected: {{ json_encode(array_map('strval', $selectedPermissions)) }},
    toggleModule(moduleResources, checked) {
        moduleResources.forEach(res => {
            const resourceKeys = Object.keys(res.perms);
            resourceKeys.forEach(action => {
                const id = res.perms[action].toString();
                if (checked && !this.selected.includes(id)) this.selected.push(id);
                if (!checked) this.selected = this.selected.filter(i => i !== id);
            });
        });
    },
    toggleRow(res, checked) {
        const ids = Object.values(res.perms).map(id => id.toString());
        if (checked) {
            ids.forEach(id => {
                if (!this.selected.includes(id)) this.selected.push(id);
            });
        } else {
            this.selected = this.selected.filter(id => !ids.includes(id));
        }
    }
}">
    @foreach(collect($resources)->groupBy('module') as $module => $moduleResources)
        @php
            $resWithPerms = $moduleResources->map(fn($r, $k) => [
                'key' => $k,
                'label' => $r['label'],
                'perms' => $groupedPermissions[$k] ?? []
            ]);
        @endphp
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-primary uppercase tracking-wider px-3 border-l-4 border-primary bg-primary/5 py-1 rounded-r-lg flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <span>{{ $module }}</span>
                    <label class="flex items-center gap-1 cursor-pointer group/toggle">
                        <input type="checkbox" 
                               class="w-3 h-3 rounded border-white/20 bg-white/5 text-primary focus:ring-0" 
                               @change="toggleModule({{ json_encode($resWithPerms) }}, $event.target.checked)">
                        <span class="text-[10px] text-gray-500 group-hover/toggle:text-primary transition-colors">Tümünü Seç</span>
                    </label>
                </div>
                @if($loop->first)
                    <form action="{{ route('hr.permissions.seed') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-[10px] bg-amber-500/20 hover:bg-amber-500/40 text-amber-500 px-2 py-0.5 rounded border border-amber-500/30 transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">sync</span>
                            Tabloyu Onar / Tanımları Yükle
                        </button>
                    </form>
                @endif
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
                        @foreach($resWithPerms as $res)
                            @php $hasAnyPerm = !empty($res['perms']); @endphp
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-4 py-3 font-medium text-white group-hover:text-primary transition-colors text-xs flex items-center justify-between">
                                    <span>{{ $res['label'] }}</span>
                                    @if($hasAnyPerm)
                                        <input type="checkbox" 
                                               class="w-3 h-3 rounded-full border-white/10 bg-white/5 text-primary focus:ring-0 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" 
                                               title="Satırı Seç"
                                               @change="toggleRow({{ json_encode($res) }}, $event.target.checked)">
                                    @endif
                                </td>
                                @foreach($actions as $action => $label)
                                    <td class="px-2 py-3 text-center">
                                        @if(isset($res['perms'][$action]))
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $res['perms'][$action] }}"
                                                   x-model="selected"
                                                   class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary focus:ring-primary/30 transition-all cursor-pointer">
                                        @else
                                            <span class="text-gray-700">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-2 py-3 text-center">
                                    @if(isset($res['perms']['manage']))
                                         <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $res['perms']['manage'] }}"
                                               x-model="selected"
                                               class="w-4 h-4 rounded border-amber-500/30 bg-amber-500/5 text-amber-500 focus:ring-amber-500/30 transition-all cursor-pointer">
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
