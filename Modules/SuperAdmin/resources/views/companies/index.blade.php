<x-app-layout>
    <x-slot name="header">
        Şirket Yönetimi
    </x-slot>

    <x-card>
        <div class="p-6 border-b border-white/5 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">Tüm Şirketler</h2>
            <button class="bg-primary-600 hover:bg-primary-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all shadow-neon">
                + Yeni Şirket Ekle
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/5">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Şirket Adı</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Kullanıcı</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Aktif Modüller</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">İşlem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 bg-transparent">
                    @foreach($companies as $company)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-white">{{ $company->name }}</div>
                                <div class="text-xs text-slate-500">{{ $company->domain ?? 'Alt alan adı yok' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $company->status === 'active' ? 'bg-green-900/30 text-green-400 border border-green-800/50' : 'bg-red-900/30 text-red-400 border border-red-800/50' }}">
                                    {{ $company->status === 'active' ? 'Aktif' : 'Pasif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                {{ $company->users_count }} Kayıtlı
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                <div class="flex gap-1 flex-wrap">
                                    @php 
                                        $activeModules = $company->settings['modules'] ?? ['Accounting', 'CRM', 'Inventory'];
                                        $moduleNames = [
                                            'Accounting' => 'Muhasebe',
                                            'CRM' => 'CRM',
                                            'Inventory' => 'Stok Yönetimi',
                                        ];
                                    @endphp
                                    @foreach($activeModules as $mod)
                                        <span class="px-1.5 py-0.5 rounded bg-white/5 border border-white/10 text-[10px] text-slate-300">
                                            {{ $moduleNames[$mod] ?? $mod }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('superadmin.companies.show', $company) }}" class="p-2 rounded-lg bg-primary-500/20 text-white hover:bg-primary-500/30 transition-colors flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        <span class="text-xs font-bold">İncele</span>
                                    </a>
                                    
                                    <a href="{{ route('superadmin.companies.edit', $company) }}" class="p-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 transition-colors flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                        <span class="text-xs font-bold">Düzenle</span>
                                    </a>
                                    
                                    <form action="{{ route('superadmin.companies.destroy', $company) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                            <span class="text-xs font-bold">Sil</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-white/5">
            {{ $companies->links() }}
        </div>
    </x-card>
</x-app-layout>
