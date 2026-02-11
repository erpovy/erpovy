<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Satış Yönetimi
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">point_of_sale</span>
                        Performans ve Satış Operasyonları
                        <span class="w-1 h-1 rounded-full bg-gray-400 dark:bg-slate-600"></span>
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('sales.sales.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                            Yeni Satış Kaydı
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Row 1: Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['label' => 'Toplam Satış', 'value' => $stats['total_sales'], 'icon' => 'payments', 'color' => 'blue', 'type' => 'currency'],
                    ['label' => 'Sipariş Sayısı', 'value' => $stats['total_orders'], 'icon' => 'shopping_cart', 'color' => 'green', 'type' => 'count'],
                    ['label' => 'Tahsilat Bekleyen', 'value' => $stats['pending_amount'], 'icon' => 'pending_actions', 'color' => 'orange', 'type' => 'currency'],
                    ['label' => 'Bu Ayki Hacim', 'value' => $stats['monthly_sales'], 'icon' => 'trending_up', 'color' => 'purple', 'type' => 'currency']
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

            <!-- Row 2: Charts & Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Quick Sales Actions -->
                <div class="lg:col-span-1 space-y-6">
                    <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-6 uppercase tracking-widest flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">bolt</span>
                            Hızlı Menü
                        </h3>
                        <div class="grid grid-cols-1 gap-4">
                            @foreach([
                                ['label' => 'Satış Noktası', 'icon' => 'point_of_sale', 'color' => 'blue', 'route' => route('sales.pos.index')],
                                ['label' => 'Teklif Hazırla', 'icon' => 'description', 'color' => 'green', 'route' => route('sales.quotes.create')],
                                ['label' => 'Abonelikler', 'icon' => 'autorenew', 'color' => 'purple', 'route' => route('sales.subscriptions.index')],
                                ['label' => 'İadeler', 'icon' => 'keyboard_return', 'color' => 'red', 'route' => route('sales.returns.index')]
                            ] as $action)
                            <a href="{{ $action['route'] }}" class="group flex items-center gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-{{ $action['color'] }}-500/30 hover:bg-{{ $action['color'] }}-500/5 transition-all">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-{{ $action['color'] }}-500/10 text-{{ $action['color'] }}-400 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined">{{ $action['icon'] }}</span>
                                </div>
                                <span class="font-black text-gray-600 dark:text-slate-300 group-hover:text-gray-900 dark:group-hover:text-white uppercase tracking-wider text-sm">{{ $action['label'] }}</span>
                            </a>
                            @endforeach
                        </div>
                    </x-card>
                </div>

                <!-- Recent Sales List -->
                <div class="lg:col-span-2">
                    <x-card class="h-full p-8 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl flex flex-col">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-400">history</span>
                                    Son Satış Faaliyetleri
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-slate-500 mt-1">Sistem üzerinden gerçekleşen son işlemler</p>
                            </div>
                            <a href="#" class="px-4 py-2 bg-gray-100 dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10 text-xs font-black text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors uppercase tracking-widest">Tümünü Gör</a>
                        </div>

                        <div class="flex-1 space-y-4">
                            @forelse($recentSales as $sale)
                            <div class="flex items-center gap-6 p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-white/5 border border-transparent hover:border-gray-200 dark:hover:border-white/10 transition-all group">
                                <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-500 dark:text-slate-500 group-hover:bg-primary/10 group-hover:text-primary transition-all">
                                    <span class="material-symbols-outlined text-[28px]">receipt_long</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-black text-gray-900 dark:text-white group-hover:text-primary transition-colors truncate">
                                        {{ $sale->contact->name ?? 'Genel Müşteri' }}
                                    </div>
                                    <div class="flex items-center gap-3 mt-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        <span>{{ $sale->invoice_number }}</span>
                                        <span class="w-1 h-1 rounded-full bg-gray-400 dark:bg-slate-700"></span>
                                        <span>{{ $sale->issue_date->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-black text-gray-900 dark:text-white">₺{{ number_format($sale->total_amount, 0, ',', '.') }}</div>
                                    <div class="text-[9px] font-black uppercase tracking-widest mt-1 px-2 py-0.5 rounded {{ $sale->status === 'paid' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' }}">
                                        {{ $sale->status === 'paid' ? 'Tahsil Edildi' : 'Beklemede' }}
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="flex flex-col items-center justify-center py-20 opacity-30">
                                <span class="material-symbols-outlined text-6xl mb-4">shopping_basket</span>
                                <p class="font-black uppercase tracking-widest text-sm">Henüz satış bulunmuyor</p>
                            </div>
                            @endforelse
                        </div>
                    </x-card>
                </div>
            </div>
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
