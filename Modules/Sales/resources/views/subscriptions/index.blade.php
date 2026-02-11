<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Abonelik Yönetimi
                    </h2>
                    <p class="text-gray-500 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">autorenew</span>
                        Periyodik Hizmet ve Tahsilat Takibi
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('sales.subscriptions.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add_circle</span>
                            Yeni Abonelik Başlat
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['label' => 'Aktif Abonelik', 'value' => $stats['active_count'], 'icon' => 'check_circle', 'color' => 'green', 'type' => 'count'],
                    ['label' => 'Aylık Düzenli Gelir (MRR)', 'value' => $stats['mrr'], 'icon' => 'show_chart', 'color' => 'blue', 'type' => 'currency'],
                    ['label' => 'Yıllık Tahmini Hacim', 'value' => $stats['mrr'] * 12, 'icon' => 'query_stats', 'color' => 'purple', 'type' => 'currency'],
                    ['label' => 'İptal / Sona Eren', 'value' => $stats['expired_count'], 'icon' => 'cancel', 'color' => 'red', 'type' => 'count']
                ] as $stat)
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $stat['color'] }}-500/20 to-{{ $stat['color'] }}-500/5 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl hover:border-{{ $stat['color'] }}-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-400">
                                <span class="material-symbols-outlined text-[24px]">{{ $stat['icon'] }}</span>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white mb-1">
                            @if($stat['type'] == 'currency')
                                ₺{{ number_format($stat['value'], 0, ',', '.') }}
                            @else
                                {{ number_format($stat['value']) }}
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 dark:text-slate-500 font-bold uppercase tracking-wider">{{ $stat['label'] }}</div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- List & Search -->
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5">
                <div class="p-4 border-b border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-white/[0.02] rounded-t-xl">
                    <form action="{{ route('sales.subscriptions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative group">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Abonelik adı veya müşteri ile ara..." 
                                   style="padding-left: 20px !important;"
                                   class="w-full pr-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-slate-500 focus:border-primary/30 focus:bg-gray-50 dark:focus:bg-white/10 focus:ring-0 transition-all text-sm font-medium">
                        </div>
                        
                        
                        <div class="w-full md:w-64 relative" x-data="{ 
                            open: false,
                            status: '{{ request('status', '') }}',
                            statusLabel: '{{ request('status') == 'active' ? 'Aktif' : (request('status') == 'suspended' ? 'Askıda' : (request('status') == 'cancelled' ? 'İptal' : 'Tüm Durumlar')) }}',
                            select(val, label) {
                                this.status = val;
                                this.statusLabel = label;
                                this.open = false;
                                $nextTick(() => { $el.closest('form').submit(); });
                            }
                        }">
                            <input type="hidden" name="status" :value="status">
                            <button type="button" @click="open = !open" 
                                    style="padding-left: 20px !important;"
                                    class="w-full pr-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white text-left focus:border-primary/50 focus:ring-0 transition-all flex items-center justify-between group">
                                <span x-text="statusLabel" class="text-sm font-medium truncate mr-2"></span>
                                <span class="material-symbols-outlined text-gray-500 dark:text-slate-500 transition-transform" :class="{'rotate-180': open}">expand_more</span>
                            </button>
                            
                            <!-- Custom Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 @click.away="open = false"
                                 class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-slate-900/50 border border-gray-200 dark:border-white/10 rounded-xl shadow-2xl overflow-hidden z-50 backdrop-blur-xl">
                                <div class="py-1">
                                    <button type="button" @click="select('', 'Tüm Durumlar')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors">Tüm Durumlar</button>
                                    <button type="button" @click="select('active', 'Aktif')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-t border-gray-100 dark:border-white/5">Aktif</button>
                                    <button type="button" @click="select('suspended', 'Askıda')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-t border-gray-100 dark:border-white/5">Askıda</button>
                                    <button type="button" @click="select('cancelled', 'İptal')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-t border-gray-100 dark:border-white/5">İptal</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Müşteri / Abonelik</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-center">Periyot</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-right">Tutar</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-center">Başlangıç</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-center">Sonraki Fatura</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-center">Durum</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-right min-w-[180px]">İşlem</th>
                            </tr>
                        </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($subscriptions as $sub)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                <td class="p-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">
                                        {{ $sub->contact->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 dark:text-slate-500 font-bold uppercase">{{ $sub->name }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2.5 py-1 rounded-md bg-gray-100 dark:bg-white/5 text-[10px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider border border-gray-200 dark:border-white/5">
                                        {{ $sub->billing_interval }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">
                                        ₺{{ number_format($sub->price, 2, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 dark:text-slate-500">+ KDV</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-xs font-medium text-gray-600 dark:text-slate-300">
                                        {{ $sub->start_date->format('d.m.Y') }}
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ optional($sub->next_billing_date)->format('d.m.Y') ?? '-' }}
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    @php
                                        $statusConfig = [
                                            'active'    => ['class' => 'bg-emerald-600 text-white border-emerald-400', 'label' => 'AKTİF'],
                                            'suspended' => ['class' => 'bg-amber-600 text-white border-amber-400', 'label' => 'ASKIDA'],
                                            'cancelled' => ['class' => 'bg-red-600 text-white border-red-400', 'label' => 'İPTAL'],
                                            'expired'   => ['class' => 'bg-slate-600 text-white border-slate-400', 'label' => 'SONA ERDİ'],
                                        ][$sub->status] ?? ['class' => 'bg-slate-500 text-white border-slate-400', 'label' => strtoupper($sub->status)];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusConfig['class'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-3 px-1">
                                        {{-- Görüntüle --}}
                                        <a href="{{ route('sales.subscriptions.show', $sub->id) }}" 
                                           class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20 hover:scale-110 active:scale-95 transition-all duration-300">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </a>

                                        {{-- Düzenle --}}
                                        <a href="{{ route('sales.subscriptions.edit', $sub->id) }}" 
                                           style="background-color: #f59e0b !important;"
                                           class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-xl text-slate-900 shadow-lg shadow-amber-500/20 hover:scale-110 active:scale-95 transition-all duration-300">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>

                                        {{-- Sil --}}
                                        <form action="{{ route('sales.subscriptions.destroy', $sub->id) }}" method="POST" class="inline" onsubmit="return confirm('Emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-xl bg-red-600 text-white shadow-lg shadow-red-600/20 hover:scale-110 active:scale-95 transition-all duration-300">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-gray-500 dark:text-slate-500 italic">Abonelik kaydı bulunamadı.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($subscriptions->hasPages())
                <div class="p-6 border-t border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-white/[0.01]">
                    {{ $subscriptions->links() }}
                </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
