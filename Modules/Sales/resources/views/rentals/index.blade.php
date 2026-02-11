<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Kiralama Yönetimi
                    </h2>
                    <p class="text-gray-500 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">key</span>
                        Ekipman ve Ürün Kiralama Takibi
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('sales.rentals.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add_circle</span>
                            Yeni Kiralama Başlat
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
                    ['label' => 'Aktif Kiralamalar', 'value' => $stats['active_rentals'], 'icon' => 'check_circle', 'color' => 'green', 'type' => 'count'],
                    ['label' => 'Geciken Kiralamalar', 'value' => $stats['overdue_count'], 'icon' => 'schedule', 'color' => 'red', 'type' => 'count'],
                    ['label' => 'Aylık Tahmini Gelir', 'value' => $stats['monthly_revenue'], 'icon' => 'show_chart', 'color' => 'blue', 'type' => 'currency'],
                    ['label' => 'Toplam İşlem Hacmi', 'value' => $stats['total_volume'], 'icon' => 'analytics', 'color' => 'purple', 'type' => 'count']
                ] as $stat)
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $stat['color'] }}-500/5 to-{{ $stat['color'] }}-500/0 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl hover:border-{{ $stat['color'] }}-500/30 transition-all duration-300 group-hover:-translate-y-1 shadow-sm hover:shadow-md dark:shadow-none">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400">
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
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 shadow-sm dark:shadow-none">
                <div class="p-4 border-b border-gray-200 dark:border-white/5 bg-white dark:bg-white/[0.02] rounded-t-xl">
                    <form action="{{ route('sales.rentals.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative group">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kiralama no veya müşteri ile ara..." 
                                   style="padding-left: 20px !important;"
                                   class="w-full pr-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-slate-500 focus:border-primary/30 focus:bg-gray-50 dark:focus:bg-white/10 focus:ring-0 transition-all text-sm font-medium">
                        </div>
                        
                        <div class="w-full md:w-64 relative" x-data="{ 
                            open: false,
                            status: '{{ request('status', '') }}',
                            statusLabel: '{{ request('status') == 'active' ? 'Aktif' : (request('status') == 'completed' ? 'Tamamlandı' : (request('status') == 'overdue' ? 'Gecikti' : (request('status') == 'cancelled' ? 'İptal' : 'Tüm Durumlar'))) }}',
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
                                    class="w-full pr-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-700 dark:text-white text-left focus:border-primary/50 focus:ring-0 transition-all flex items-center justify-between group">
                                <span x-text="statusLabel" class="text-sm font-medium truncate mr-2"></span>
                                <span class="material-symbols-outlined text-gray-500 dark:text-slate-500 transition-transform" :class="{'rotate-180': open}">expand_more</span>
                            </button>
                            
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 @click.away="open = false"
                                 class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-slate-900 shadow-2xl rounded-xl border border-gray-200 dark:border-white/10 overflow-hidden z-50">
                                <div class="py-1">
                                    <button type="button" @click="select('', 'Tüm Durumlar')" style="padding-left: 20px !important;" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-b border-gray-100 dark:border-white/5">Tüm Durumlar</button>
                                    <button type="button" @click="select('active', 'Aktif')" style="padding-left: 20px !important;" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-b border-gray-100 dark:border-white/5">Aktif</button>
                                    <button type="button" @click="select('completed', 'Tamamlandı')" style="padding-left: 20px !important;" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-b border-gray-100 dark:border-white/5">Tamamlandı</button>
                                    <button type="button" @click="select('overdue', 'Gecikti')" style="padding-left: 20px !important;" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-b border-gray-100 dark:border-white/5">Gecikti</button>
                                    <button type="button" @click="select('cancelled', 'İptal')" style="padding-left: 20px !important;" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors">İptal</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Kiralama No / Ürün</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Müşteri</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-right">Günlük Fiyat</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-center">Başlangıç</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-center">Bitiş</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-center">Durum</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider text-right">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($rentals as $rental)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                <td class="p-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">
                                        {{ $rental->rental_no }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 dark:text-slate-500 font-bold uppercase">{{ $rental->product->name ?? 'Genel Kiralama' }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-medium text-gray-700 dark:text-slate-200">{{ $rental->contact->name ?? '---' }}</div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">
                                        ₺{{ number_format($rental->daily_price, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-xs font-medium text-gray-600 dark:text-slate-300">
                                        {{ $rental->start_date->format('d.m.Y') }}
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-xs font-medium {{ $rental->end_date && $rental->end_date->isPast() && $rental->status == 'active' ? 'text-red-500 dark:text-red-400 font-bold' : 'text-gray-600 dark:text-slate-300' }}">
                                        {{ $rental->end_date ? $rental->end_date->format('d.m.Y') : 'Açık' }}
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    @php
                                        $statusClass = [
                                            'active' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                            'completed' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                            'overdue' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            'cancelled' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                                        ][$rental->status] ?? 'bg-slate-500/10 text-slate-400 border-slate-500/20';
                                        
                                        $statusLabel = [
                                            'active' => 'AKTİF',
                                            'completed' => 'TAMAMLANDI',
                                            'overdue' => 'GECİKTİ',
                                            'cancelled' => 'İPTAL',
                                        ][$rental->status] ?? strtoupper($rental->status);
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('sales.rentals.show', $rental) }}" class="p-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                        <form action="{{ route('sales.rentals.destroy', $rental) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-100 dark:bg-red-500/10 text-red-500 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-500/20 transition-all">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-gray-500 dark:text-slate-500 italic">Kiralama kaydı bulunamadı.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($rentals->hasPages())
                <div class="p-6 border-t border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-white/[0.01]">
                    {{ $rentals->links() }}
                </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
