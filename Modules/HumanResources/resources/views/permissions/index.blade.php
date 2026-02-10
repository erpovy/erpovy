@php
    function translatePermission($name) {
        $parts = explode(' ', $name);
        $action = $parts[0] ?? '';
        $resource = $parts[1] ?? '';

        $actions = [
            'view' => 'Görüntüleme',
            'create' => 'Oluşturma',
            'edit' => 'Düzenleme',
            'delete' => 'Silme',
            'manage' => 'Yönetme',
        ];

        $resources = [
            'users' => 'Kullanıcı',
            'companies' => 'Şirket',
            'accounting' => 'Muhasebe',
            'invoices' => 'Fatura',
            'roles' => 'Rol',
            'permissions' => 'Yetki',
            'employees' => 'Personel',
            'departments' => 'Departman',
            'settings' => 'Sistem Ayarı',
            'cash-bank' => 'Kasa/Banka',
            'reports' => 'Rapor',
            'hr' => 'İnsan Kaynakları',
            'leaves' => 'İzin',
            'fleet' => 'Araç/Filo',
            'inventory' => 'Envanter',
            'products' => 'Ürün',
            'categories' => 'Kategori',
            'brands' => 'Marka',
            'units' => 'Birim',
            'warehouses' => 'Depo',
            'stock' => 'Stok',
            'stock-movements' => 'Stok Hareketi',
            'sales' => 'Satış',
            'subscriptions' => 'Abonelik',
            'rentals' => 'Kiralama',
            'manufacturing' => 'Üretim',
            'bom' => 'Ürün Reçetesi (BOM)',
        ];

        $translatedAction = $actions[$action] ?? $action;
        $translatedResource = $resources[$resource] ?? $resource;

        return "$translatedResource $translatedAction";
    }
@endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Yetki Yönetimi') }}
            </h2>
            <a href="#" class="px-4 py-2 bg-primary hover:bg-primary-600 text-white rounded-lg transition-colors text-sm font-medium">
                + Yeni Yetki
            </a>
        </div>
    </x-slot>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-400">
                <thead class="bg-white/5 text-xs uppercase text-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">Yetki Adı</th>
                        <th scope="col" class="px-6 py-3">Guard</th>
                        <th scope="col" class="px-6 py-3">Eklenme Tarihi</th>
                        <th scope="col" class="px-6 py-3 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($permissions as $permission)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ translatePermission($permission->name) }}</div>
                                <div class="text-xs text-gray-500">{{ $permission->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-400/20">{{ $permission->guard_name }}</span>
                            </td>
                            <td class="px-6 py-4">{{ $permission->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button class="text-blue-400 hover:text-blue-300 transition-colors" title="Görüntüle">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <button class="text-yellow-400 hover:text-yellow-300 transition-colors" title="Düzenle">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </button>
                                    <button class="text-red-400 hover:text-red-300 transition-colors pt-1" title="Sil">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Kayıtlı yetki bulunamadı.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($permissions->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $permissions->links('pagination::tailwind') }}
            </div>
        @endif
    </x-card>
</x-app-layout>
