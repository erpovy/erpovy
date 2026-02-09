<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Sözleşmeler
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">history_edu</span>
                        Müşteri Sözleşmeleri ve Anlaşmalar
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('crm.contracts.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add_circle</span>
                            Yeni Sözleşme
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
                    ['label' => 'Toplam Sözleşme', 'value' => $stats['total'], 'icon' => 'folder', 'color' => 'blue'],
                    ['label' => 'Aktif Sözleşmeler', 'value' => $stats['active'], 'icon' => 'verified', 'color' => 'green'],
                    ['label' => 'Taslaklar', 'value' => $stats['draft'], 'icon' => 'edit_document', 'color' => 'orange'],
                    ['label' => 'Süresi Dolanlar', 'value' => $stats['expired'], 'icon' => 'timer_off', 'color' => 'red']
                ] as $stat)
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $stat['color'] }}-500/20 to-{{ $stat['color'] }}-500/5 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:border-{{ $stat['color'] }}-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-400">
                                <span class="material-symbols-outlined text-[24px]">{{ $stat['icon'] }}</span>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-white mb-1">{{ number_format($stat['value']) }}</div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">{{ $stat['label'] }}</div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- Filters & Search -->
            <x-card class="p-4 border-white/10 bg-white/5 backdrop-blur-2xl relative z-[100] overflow-visible">
                <form action="{{ route('crm.contracts.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Sözleşme konusu veya müşteri adı ile ara..." 
                               style="padding-left: 20px !important;"
                               class="w-full pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:border-primary/50 focus:ring-0 transition-all">
                    </div>
                    <div class="w-full md:w-64 relative z-50" x-data="{ 
                        open: false,
                        status: '{{ request('status', '') }}',
                        statusLabel: '{{ request('status') == 'Active' ? 'Aktif' : (request('status') == 'Draft' ? 'Taslak' : (request('status') == 'Expired' ? 'Süresi Dolmuş' : (request('status') == 'Terminated' ? 'İptal Edilmiş' : 'Tüm Durumlar'))) }}',
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
                                class="w-full pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-left focus:border-primary/50 focus:ring-0 transition-all flex items-center justify-between">
                            <span x-text="statusLabel" class="text-sm font-medium"></span>
                            <span class="material-symbols-outlined text-slate-500 transition-transform" :class="{'rotate-180': open}">expand_more</span>
                        </button>
                        
                        <!-- Custom Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             @click.away="open = false"
                             class="absolute top-full left-0 w-full mt-2 bg-slate-900/50 border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50 backdrop-blur-xl">
                            <div class="py-1">
                                <button type="button" @click="select('', 'Tüm Durumlar')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors">Tüm Durumlar</button>
                                <button type="button" @click="select('Active', 'Aktif')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors border-t border-white/5">Aktif</button>
                                <button type="button" @click="select('Draft', 'Taslak')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors border-t border-white/5">Taslak</button>
                                <button type="button" @click="select('Expired', 'Süresi Dolmuş')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors border-t border-white/5">Süresi Dolmuş</button>
                                <button type="button" @click="select('Terminated', 'İptal Edilmiş')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors border-t border-white/5">İptal Edilmiş</button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-card>

            <!-- Contracts Table -->
            <x-card class="p-0 border-white/10 bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02] border-b border-white/5">
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center w-24">ID</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Konu</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Müşteri</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Tarih Aralığı</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Değer</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Durum</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center w-24">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($contracts as $contract)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="p-4 text-center">
                                    <span class="px-2 py-1 bg-white/10 rounded text-xs font-mono font-bold text-primary group-hover:bg-primary/20 transition-colors">
                                        #{{ $contract->id }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-bold text-white group-hover:text-primary transition-colors">{{ $contract->subject }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-medium text-slate-300">{{ $contract->contact->name ?? 'Müşteri Yok' }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-xs text-slate-400 font-mono">
                                        {{ \Carbon\Carbon::parse($contract->start_date)->format('d.m.Y') }} 
                                        <span class="text-slate-600">-</span>
                                        {{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d.m.Y') : 'Süresiz' }}
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black text-white">
                                        {{ number_format($contract->value, 2, ',', '.') }} ₺
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    @php
                                        $statusClass = match($contract->status) {
                                            'Active' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                            'Draft' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                                            'Expired' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            default => 'bg-blue-500/10 text-blue-400 border-blue-500/20'
                                        };
                                        $statusLabel = match($contract->status) {
                                            'Active' => 'Aktif',
                                            'Draft' => 'Taslak',
                                            'Expired' => 'Süresi Dolmuş',
                                            'Terminated' => 'İptal',
                                            default => $contract->status
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('crm.contracts.show', $contract) }}" class="p-2 rounded-lg bg-white/5 text-slate-400 hover:bg-white/10 transition-all active:scale-90" title="Görüntüle">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                        <a href="{{ route('crm.contracts.edit', $contract) }}" class="p-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 transition-all active:scale-90" title="Düzenle">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-slate-500 italic">
                                    Kayıt bulunamadı.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($contracts->hasPages())
                <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                    {{ $contracts->links() }}
                </div>
                @endif
            </x-card>
        </div>
    </div>

    <!-- Live Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const target = document.getElementById('live-clock');
            if (target) target.textContent = now.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</x-app-layout>
