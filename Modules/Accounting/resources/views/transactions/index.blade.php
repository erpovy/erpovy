<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Muhasebe Fişleri
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                        Yevmiye Kayıtları ve Fiş İşlemleri
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('accounting.transactions.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add_task</span>
                            Yeni Fiş Oluştur
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
                    ['label' => 'Toplam Fiş', 'value' => $stats['total_count'], 'icon' => 'history', 'color' => 'blue'],
                    ['label' => 'Onaylı Kayıt', 'value' => $stats['approved_count'], 'icon' => 'verified', 'color' => 'green'],
                    ['label' => 'İşlem Hacmi', 'value' => '₺' . number_format($stats['total_volume'], 0), 'icon' => 'account_balance', 'color' => 'purple'],
                    ['label' => 'Bu Ayki Kayıt', 'value' => $stats['monthly_count'], 'icon' => 'event_repeat', 'color' => 'orange']
                ] as $stat)
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $stat['color'] }}-500/20 to-{{ $stat['color'] }}-500/5 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:border-{{ $stat['color'] }}-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-400">
                                <span class="material-symbols-outlined text-[24px]">{{ $stat['icon'] }}</span>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-white mb-1">{{ $stat['value'] }}</div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">{{ $stat['label'] }}</div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- Filters & Search -->
            <x-card class="p-4 border-white/10 bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('accounting.transactions.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Fiş no veya açıklama ile ara..." 
                               class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:border-primary/50 focus:ring-0 transition-all">
                    </div>
                    
                    <!-- Type Filter -->
                    <div class="w-full md:w-48 relative">
                        <select name="type" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary/50 focus:ring-0 appearance-none">
                            <option value="">Tüm Fiş Tipleri</option>
                            <option value="regular" {{ request('type') == 'regular' ? 'selected' : '' }}>Mahsup Fişi</option>
                            <option value="collection" {{ request('type') == 'collection' ? 'selected' : '' }}>Tahsil Fişi</option>
                            <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>Tediye Fişi</option>
                            <option value="opening" {{ request('type') == 'opening' ? 'selected' : '' }}>Açılış Fişi</option>
                            <option value="closing" {{ request('type') == 'closing' ? 'selected' : '' }}>Kapanış Fişi</option>
                        </select>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500 pointer-events-none">filter_list</span>
                    </div>

                    <div class="w-full md:w-48 relative" x-data="{ 
                        open: false,
                        status: '{{ request('status', '') }}',
                        statusLabel: '{{ request('status') == 'approved' ? 'Onaylı' : (request('status') == 'draft' ? 'Taslak' : 'Durum Seç') }}',
                        select(val, label) {
                            this.status = val;
                            this.statusLabel = label;
                            this.open = false;
                            $nextTick(() => { $el.closest('form').submit(); });
                        }
                    }">
                        <input type="hidden" name="status" :value="status">
                        <button type="button" @click="open = !open" 
                                class="w-full pl-4 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-left focus:border-primary/50 focus:ring-0 transition-all flex items-center justify-between">
                            <span x-text="statusLabel" class="text-sm font-medium"></span>
                            <span class="material-symbols-outlined text-slate-500 transition-transform" :class="{'rotate-180': open}">expand_more</span>
                        </button>
                        
                        <!-- Custom Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             @click.away="open = false"
                             class="absolute top-full left-0 w-full mt-2 bg-[#1e293b] border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50 backdrop-blur-xl">
                            <div class="py-1">
                                <button type="button" @click="select('', 'Tüm Durumlar')" 
                                        class="w-full px-4 py-2.5 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                                    Tüm Durumlar
                                </button>
                                <button type="button" @click="select('approved', 'Onaylı')" 
                                        class="w-full px-4 py-2.5 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors border-t border-white/5">
                                    Onaylı
                                </button>
                                <button type="button" @click="select('draft', 'Taslak')" 
                                        class="w-full px-4 py-2.5 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors border-t border-white/5">
                                    Taslak
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-card>

            <!-- Transactions Table -->
            <x-card class="p-0 border-white/10 bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02] border-b border-white/5">
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center w-32">Tarih</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Fiş No</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Fiş Tipi</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Açıklama</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Borç Toplamı</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Alacak Toplamı</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Durum</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="p-4 text-center">
                                    <div class="text-sm font-bold text-white">{{ $transaction->date->format('d.m.Y') }}</div>
                                    <div class="text-[10px] text-slate-600 font-bold uppercase">{{ $transaction->date->diffForHumans() }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 bg-primary/10 rounded text-xs font-mono font-black text-primary group-hover:bg-primary/20 transition-colors">
                                        {{ $transaction->receipt_number }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @php
                                        $typeColors = [
                                            'regular' => 'blue',
                                            'collection' => 'green',
                                            'payment' => 'red',
                                            'opening' => 'purple',
                                            'closing' => 'slate',
                                        ];
                                        $typeLabels = [
                                            'regular' => 'Mahsup',
                                            'collection' => 'Tahsil',
                                            'payment' => 'Tediye',
                                            'opening' => 'Açılış',
                                            'closing' => 'Kapanış',
                                        ];
                                        $color = $typeColors[$transaction->type] ?? 'slate';
                                        $label = $typeLabels[$transaction->type] ?? ucfirst($transaction->type);
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs font-bold bg-{{ $color }}-500/10 text-{{ $color }}-400">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-medium text-slate-300 group-hover:text-white transition-colors">
                                        {{ Str::limit($transaction->description, 60) }}
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black text-white">
                                        {{ number_format($transaction->total_debit, 2, ',', '.') }} ₺
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black text-white">
                                        {{ number_format($transaction->total_credit, 2, ',', '.') }} ₺
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    @if($transaction->is_approved)
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-500/10 text-green-400 border border-green-500/20">
                                            Onaylı
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                            Taslak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-500 italic">
                                    Kayıt bulunamadı.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                    {{ $transactions->links() }}
                </div>
                @endif
            </x-card>
        </div>
    </div>

    <!-- Live Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const target = document.getElementById('live-clock');
            if (target) target.textContent = `${hours}:${minutes}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</x-app-layout>
