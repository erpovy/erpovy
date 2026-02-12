<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Şirket Yönetimi
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">business</span>
                        Tüm Kayıtlı Şirketleri ve Modül Yetkilerini Yönetin
                    </p>
                </div>
                
                <a href="{{ route('superadmin.companies.create') }}" class="group flex items-center gap-2 px-6 py-3 rounded-2xl bg-primary text-gray-900 dark:text-white font-black text-sm uppercase tracking-widest shadow-[0_0_20px_rgba(var(--color-primary),0.3)] hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    Yeni Şirket Ekle
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <x-card class="overflow-hidden border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-white/5">
                                <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Şirket Bilgileri</th>
                                <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Durum</th>
                                <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Kullanıcı Sayısı</th>
                                <th class="px-6 py-5 text-left text-xs font-black text-slate-500 uppercase tracking-[0.2em]">Aktif Modüller</th>
                                <th class="px-6 py-5 text-right text-xs font-black text-slate-500 uppercase tracking-[0.2em]">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @foreach($companies as $company)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-all group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary/20 to-purple-500/20 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                                <span class="material-symbols-outlined">corporate_fare</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $company->name }}</div>
                                                <div class="text-xs font-mono text-gray-500 dark:text-slate-500">{{ $company->domain ?? 'Alt alan adı yok' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex">
                                            @if($company->status === 'active')
                                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Pasif
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $company->users_count }}</span>
                                            <span class="text-xs text-slate-500 uppercase font-black">Kayıtlı</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-1.5 flex-wrap">
                                            @php 
                                                $activeModules = $company->settings['modules'] ?? [];
                                                $moduleNames = [
                                                    'Accounting' => 'Muhasebe',
                                                    'CRM' => 'CRM',
                                                    'Inventory' => 'Stok',
                                                    'FixedAssets' => 'Demirbaş',
                                                    'HumanResources' => 'İK',
                                                ];
                                            @endphp
                                            @forelse(collect($activeModules)->take(3) as $mod)
                                                <span class="px-2 py-1 rounded-lg bg-white/5 border border-gray-200 dark:border-white/10 text-[9px] font-black uppercase tracking-tight text-gray-700 dark:text-slate-300">
                                                    {{ $moduleNames[explode('.', $mod)[0]] ?? $mod }}
                                                </span>
                                            @empty
                                                <span class="text-[10px] text-slate-500 italic uppercase font-medium">Modül Atanmamış</span>
                                            @endforelse
                                            @if(count($activeModules) > 3)
                                                <span class="px-2 py-1 rounded-lg bg-primary/10 border border-primary/20 text-[9px] font-black text-primary">
                                                    +{{ count($activeModules) - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        <div class="flex justify-end items-center gap-2">
                                            <a href="{{ route('superadmin.companies.show', $company) }}" class="p-2 rounded-xl bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all duration-300 flex items-center gap-1 shadow-sm hover:shadow-primary/25">
                                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                <span class="text-[10px] font-black uppercase tracking-wider pr-1">İncele</span>
                                            </a>
                                            
                                            <a href="{{ route('superadmin.companies.edit', $company) }}" class="p-2 rounded-xl bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white transition-all duration-300 flex items-center gap-1 shadow-sm hover:shadow-blue-500/25">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                                <span class="text-[10px] font-black uppercase tracking-wider pr-1">Düzenle</span>
                                            </a>
                                            
                                            <form action="{{ route('superadmin.companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Bu şirketi tamamen silmek istediğinize emin misiniz? Bu işlem geri alınamaz!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all duration-300 flex items-center gap-1 shadow-sm hover:shadow-rose-500/25">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    <span class="text-[10px] font-black uppercase tracking-wider pr-1">Sil</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($companies->hasPages())
                    <div class="px-6 py-6 border-t border-gray-200 dark:border-white/5 bg-gray-50/30 dark:bg-white/5">
                        {{ $companies->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
