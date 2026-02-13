<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-emerald-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Satış Kontrol Paneli
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">point_of_sale</span>
                        Gelir takibi ve satış kanalları performansı
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('accounting.invoices.create', ['type' => 'sales']) }}" class="flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-500/20">
                        <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                        Satış Oluştur
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Temel Performans Göstergeleri -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Toplam Ciro -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-emerald-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-500">
                                <span class="material-symbols-outlined text-[24px]">payments</span>
                            </div>
                            <div class="text-emerald-400 text-xs font-bold bg-emerald-900/30 px-2 py-1 rounded-lg border border-emerald-500/30">Ciro</div>
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">₺{{ number_format($totalSalesAmount, 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Toplam Faturalandırılan Satış</div>
                    </x-card>
                </div>

                <!-- Bekleyen Teklifler -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-blue-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-blue-500/10 text-blue-500">
                                <span class="material-symbols-outlined text-[24px]">description</span>
                            </div>
                            <div class="text-blue-400 text-xs font-bold bg-blue-900/30 px-2 py-1 rounded-lg border border-blue-500/30">Teklifler</div>
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ number_format($pendingQuotesCount) }}</div>
                        <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Onay Bekleyen Teklifler</div>
                    </x-card>
                </div>

                <!-- Aktif Abonelikler -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-purple-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-purple-500/10 text-purple-500">
                                <span class="material-symbols-outlined text-[24px]">sync</span>
                            </div>
                            <div class="text-purple-400 text-xs font-bold bg-purple-900/30 px-2 py-1 rounded-lg border border-purple-500/30">Abonelik</div>
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ number_format($activeSubscriptionsCount) }}</div>
                        <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Aktif Tekrarlayan Gelir</div>
                    </x-card>
                </div>

                <!-- Aktif Kiralamalar -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/20 to-amber-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-orange-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-orange-500/10 text-orange-500">
                                <span class="material-symbols-outlined text-[24px]">key</span>
                            </div>
                            <div class="text-orange-400 text-xs font-bold bg-orange-900/30 px-2 py-1 rounded-lg border border-orange-500/30">Kiralama</div>
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ number_format($activeRentalsCount) }}</div>
                        <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Devam Eden Kiralama İşlemi</div>
                    </x-card>
                </div>
            </div>

            <!-- Satış Analizi ve Kanallar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Satış Trend Grafiği -->
                <div class="lg:col-span-2">
                    <x-card class="h-full p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-500">analytics</span>
                                    Aylık Satış Performansı
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-slate-500">Son 6 ayın tahsilat trendi</p>
                            </div>
                        </div>

                        <div class="h-64 flex items-end justify-between gap-4 px-4 overflow-hidden">
                            @php
                                $monthsData = collect();
                                for($i = 5; $i >= 0; $i--) {
                                    $m = now()->subMonths($i)->format('Y-m');
                                    $monthsData->put($m, [
                                        'total' => 0,
                                        'label' => now()->subMonths($i)->translatedFormat('M')
                                    ]);
                                }

                                foreach($monthlySales as $sale) {
                                    if($monthsData->has($sale->month)) {
                                        $d = $monthsData->get($sale->month);
                                        $d['total'] = $sale->total;
                                        $monthsData->put($sale->month, $d);
                                    }
                                }

                                $maxSales = collect($monthsData->values())->max('total') ?: 1;
                            @endphp

                            @foreach($monthsData as $month => $data)
                                <div class="flex-1 group flex flex-col justify-end h-full relative">
                                    <div class="w-full bg-emerald-500/30 rounded-t-2xl transition-all duration-500 group-hover:bg-emerald-500 group-hover:shadow-[0_0_20px_rgba(16,185,129,0.3)]" 
                                         style="height: {{ ($data['total'] / $maxSales) * 100 }}%"></div>
                                    <div class="text-[10px] font-bold text-gray-500 text-center mt-3 uppercase tracking-widest group-hover:text-emerald-400">{{ $data['label'] }}</div>
                                    
                                    <!-- Tooltip -->
                                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-900/90 backdrop-blur-sm border border-emerald-500/20 text-white text-[10px] py-1.5 px-3 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity z-10 whitespace-nowrap shadow-xl">
                                        ₺{{ number_format($data['total'], 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                </div>

                <!-- Teklif Dağılımı -->
                <div class="lg:col-span-1">
                    <x-card class="h-full p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2 mb-8">
                            <span class="material-symbols-outlined text-blue-500">query_stats</span>
                            Teklif Havuzu
                        </h3>

                        <div class="space-y-6">
                            @php
                                $totalQuotesCount = $quoteStages->sum('total') ?: 1;
                            @endphp
                            @forelse($quoteStages as $stage)
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="font-bold text-gray-700 dark:text-slate-400 capitalize">{{ $stage->status }}</span>
                                        <span class="text-gray-900 dark:text-white font-black">{{ number_format($stage->total) }}</span>
                                    </div>
                                    <div class="h-2 w-full bg-gray-200 dark:bg-white/5 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full shadow-[0_0_5px_rgba(59,130,246,0.5)]" 
                                             style="width: {{ ($stage->total / $totalQuotesCount) * 100 }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 opacity-30">
                                    <span class="material-symbols-outlined text-5xl block mb-2">request_quote</span>
                                    Henüz teklif yok
                                </div>
                            @endforelse
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Son Satışlar ve Yaklaşan Tahsilatlar -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Son Satışlar -->
                <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-400">verified</span>
                            Son Satış İşlemleri
                        </h3>
                        <a href="{{ route('sales.sales.index') }}" class="text-xs font-bold text-primary hover:underline">Tümünü Gör</a>
                    </div>

                    <div class="space-y-4">
                        @foreach($recentSales as $sale)
                            <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/5 transition-all group border border-transparent hover:border-white/5">
                                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined">receipt_long</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-black text-gray-900 dark:text-white truncate">{{ $sale->invoice_number }}</h4>
                                    <p class="text-[10px] text-gray-500 font-medium uppercase">{{ $sale->contact?->full_name }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">₺{{ number_format($sale->total_amount, 2, ',', '.') }}</div>
                                    <div class="text-[10px] px-2 py-0.5 rounded-full inline-block bg-emerald-500/20 text-emerald-400 font-bold uppercase">
                                        {{ $sale->status }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card>

                <!-- Abonelik Takvimi -->
                <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-400">event_repeat</span>
                            Tahsilat Takvimi
                        </h3>
                        <a href="{{ route('sales.subscriptions.index') }}" class="text-xs font-bold text-primary hover:underline">Abonelikler</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($upcomingBillings as $bill)
                            <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/5 transition-all group border border-transparent hover:border-white/5">
                                <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined">calendar_today</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-black text-gray-900 dark:text-white truncate">{{ $bill->name }}</h4>
                                    <p class="text-[10px] text-gray-500 font-medium uppercase">Tarih: {{ $bill->next_billing_date->format('d.m.Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">₺{{ number_format($bill->price, 2, ',', '.') }}</div>
                                    <div class="text-[10px] text-gray-500 italic">{{ $bill->billing_interval }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 opacity-30">
                                <span class="material-symbols-outlined text-5xl block mb-2">event_busy</span>
                                Yaklaşan tahsilat yok
                            </div>
                        @endforelse
                    </div>
                </x-card>
            </div>

            <!-- Hızlı Aksiyonlar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach([
                    ['route' => 'sales.pos.index', 'icon' => 'shopping_basket', 'label' => 'POS Satış', 'color' => 'emerald'],
                    ['route' => 'sales.quotes.index', 'icon' => 'request_quote', 'label' => 'Teklifler', 'color' => 'blue'],
                    ['route' => 'sales.subscriptions.index', 'icon' => 'sync', 'label' => 'Abonelikler', 'color' => 'purple'],
                    ['route' => 'sales.returns.index', 'icon' => 'assignment_return', 'label' => 'Satış İadesi', 'color' => 'rose']
                ] as $action)
                    <a href="{{ route($action['route']) }}" class="group relative overflow-hidden rounded-3xl">
                        <div class="absolute inset-0 bg-white/5 border border-gray-200 dark:border-white/10 transition-colors duration-300 group-hover:border-{{ $action['color'] }}-500/30 group-hover:bg-{{ $action['color'] }}-500/5"></div>
                        
                        <div class="relative p-7 flex items-center gap-5">
                            <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-{{ $action['color'] }}-500/20 to-{{ $action['color'] }}-500/10 text-{{ $action['color'] }}-400 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-xl">
                                <span class="material-symbols-outlined text-[28px]">{{ $action['icon'] }}</span>
                            </div>
                            <div class="font-black text-gray-900 dark:text-white text-sm uppercase tracking-widest group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                {{ $action['label'] }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </div>

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
