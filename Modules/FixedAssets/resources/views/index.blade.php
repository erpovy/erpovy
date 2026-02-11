<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Demirbaş Yönetimi
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                        Şirket Envanter ve Demirbaş Takibi
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="material-symbols-outlined text-[16px]">devices</span>
                        Tüm Ekipmanlar
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('fixedassets.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-gray-900 dark:text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                            Yeni Demirbaş Ekle
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Row 1: Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['label' => 'Toplam Demirbaş', 'value' => $stats['total_count'], 'icon' => 'inventory_2', 'color' => 'blue'],
                    ['label' => 'Aktif Kullanımda', 'value' => $stats['active_count'], 'icon' => 'check_circle', 'color' => 'green'],
                    ['label' => 'Toplam Değer', 'value' => '₺' . number_format($stats['total_value'], 0), 'icon' => 'payments', 'color' => 'purple'],
                    ['label' => 'Emekli/Hurda', 'value' => $stats['retired_count'], 'icon' => 'delete_sweep', 'color' => 'orange']
                ] as $stat)
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $stat['color'] }}-500/20 to-{{ $stat['color'] }}-500/5 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-{{ $stat['color'] }}-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-400">
                                <span class="material-symbols-outlined text-[24px]">{{ $stat['icon'] }}</span>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ $stat['value'] }}</div>
                        <div class="text-xs text-gray-600 dark:text-slate-500 font-bold uppercase tracking-wider">{{ $stat['label'] }}</div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- Assets Table -->
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Demirbaş</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Kod / Seri No</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Kategori</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Durum</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">Değer</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($assets as $asset)
                            <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors group">
                                <td class="p-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $asset->name }}</div>
                                    <div class="text-[10px] text-slate-600 font-bold uppercase">{{ Str::limit($asset->description, 30) }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-mono font-black text-primary">{{ $asset->code }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $asset->serial_number ?? '-' }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold bg-slate-500/10 text-slate-400">
                                        {{ $asset->category->name ?? 'Genel' }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @php
                                        $statusColors = [
                                            'active' => 'green',
                                            'retired' => 'orange',
                                            'maintenance' => 'blue',
                                            'lost' => 'red',
                                        ];
                                        $statusLabels = [
                                            'active' => 'Aktif',
                                            'retired' => 'Emekli',
                                            'maintenance' => 'Bakımda',
                                            'lost' => 'Kayıp',
                                        ];
                                        $color = $statusColors[$asset->status] ?? 'slate';
                                        $label = $statusLabels[$asset->status] ?? $asset->status;
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-{{ $color }}-500/10 text-{{ $color }}-400 border border-{{ $color }}-500/20">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">
                                        {{ number_format($asset->purchase_value, 2, ',', '.') }} ₺
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('fixedassets.show', $asset->id) }}" class="p-2 text-slate-400 hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </a>
                                        <a href="{{ route('fixedassets.edit', $asset->id) }}" class="p-2 text-slate-400 hover:text-blue-400 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-600 dark:text-slate-500 italic">
                                    Demirbaş bulunamadı.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($assets->hasPages())
                <div class="p-6 border-t border-gray-200 dark:border-white/5 bg-white/[0.01]">
                    {{ $assets->links() }}
                </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
