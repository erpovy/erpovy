<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Muhasebe Paneli
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        {{ now()->translatedFormat('d F Y') }}
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>
                
                <!-- Quick Mini Stats -->
                <div class="hidden lg:flex items-center gap-3">
                    <div class="px-4 py-2 rounded-xl bg-white/5 border border-gray-200 dark:border-white/10 backdrop-blur-md flex flex-col items-end">
                        <span class="text-[10px] text-gray-600 dark:text-slate-500 uppercase tracking-wider font-bold">Kasa/Banka</span>
                        <span class="text-lg font-black text-primary leading-none">{{ number_format($liquidBalance, 0) }}₺</span>
                    </div>
                    <div class="px-4 py-2 rounded-xl bg-white/5 border border-gray-200 dark:border-white/10 backdrop-blur-md flex flex-col items-end">
                        <span class="text-[10px] text-gray-600 dark:text-slate-500 uppercase tracking-wider font-bold">Açık Fatura</span>
                        <span class="text-lg font-black text-yellow-400 leading-none">{{ $pendingInvoicesCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Row 1: Stat Cards (4 Cols) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat 1: Income -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-green-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-green-500/10 text-green-500">
                                    <span class="material-symbols-outlined text-[20px]">trending_up</span>
                                </div>
                                <div class="text-green-400 text-xs font-bold bg-green-900/30 px-2 py-1 rounded-lg border border-green-500/30 flex items-center gap-1">
                                    Gelir
                                </div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">₺{{ number_format($totalIncome, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Toplam Gelir</div>
                        </div>
                        <div class="h-8 flex items-end gap-1 mt-4 opacity-50 group-hover:opacity-100 transition-opacity">
                            <div class="flex-1 bg-green-500/50 rounded-t-sm" style="height: 40%"></div>
                            <div class="flex-1 bg-green-500/50 rounded-t-sm" style="height: 60%"></div>
                            <div class="flex-1 bg-green-500/50 rounded-t-sm" style="height: 45%"></div>
                            <div class="flex-1 bg-green-500/50 rounded-t-sm" style="height: 75%"></div>
                            <div class="flex-1 bg-green-500/50 rounded-t-sm" style="height: 90%"></div>
                            <div class="flex-1 bg-green-500 rounded-t-sm" style="height: 100%"></div>
                        </div>
                    </x-card>
                </div>

                <!-- Stat 2: Expense -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-red-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-red-500/10 text-red-500">
                                    <span class="material-symbols-outlined text-[20px]">trending_down</span>
                                </div>
                                <div class="text-red-400 text-xs font-bold bg-red-900/30 px-2 py-1 rounded-lg border border-red-500/30 flex items-center gap-1">
                                    Gider
                                </div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">₺{{ number_format($totalExpense, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Toplam Gider</div>
                        </div>
                        <div class="h-8 flex items-end gap-1 mt-4 opacity-50 group-hover:opacity-100 transition-opacity">
                            <div class="flex-1 bg-red-500/50 rounded-t-sm" style="height: 30%"></div>
                            <div class="flex-1 bg-red-500/50 rounded-t-sm" style="height: 50%"></div>
                            <div class="flex-1 bg-red-500/50 rounded-t-sm" style="height: 70%"></div>
                            <div class="flex-1 bg-red-500 rounded-t-sm" style="height: 90%"></div>
                        </div>
                    </x-card>
                </div>

                <!-- Stat 3: Net Profit -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-blue-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-blue-500/10 text-blue-500">
                                    <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                                </div>
                                <div class="text-blue-400 text-xs font-bold bg-blue-900/30 px-2 py-1 rounded-lg border border-blue-500/30 flex items-center gap-1">
                                    Net Durum
                                </div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">₺{{ number_format($netProfit, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Net Kâr/Zarar</div>
                        </div>
                        <div class="h-8 flex items-end gap-1 mt-4 opacity-50 group-hover:opacity-100 transition-opacity">
                            <div class="flex-1 bg-blue-500/50 rounded-t-sm" style="height: 50%"></div>
                            <div class="flex-1 bg-blue-500/50 rounded-t-sm" style="height: 40%"></div>
                            <div class="flex-1 bg-blue-500/50 rounded-t-sm" style="height: 80%"></div>
                            <div class="flex-1 bg-blue-500 rounded-t-sm" style="height: 60%"></div>
                        </div>
                    </x-card>
                </div>

                <!-- Stat 4: Liquid Balance -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-purple-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-purple-500/10 text-purple-500">
                                    <span class="material-symbols-outlined text-[20px]">account_balance</span>
                                </div>
                                <div class="text-purple-400 text-xs font-bold bg-purple-900/30 px-2 py-1 rounded-lg border border-purple-500/30 flex items-center gap-1">
                                    Varlıklar
                                </div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">₺{{ number_format($liquidBalance, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Kasa & Banka Toplamı</div>
                        </div>
                        <div class="h-8 flex items-end gap-1 mt-4 opacity-50 group-hover:opacity-100 transition-opacity">
                            <div class="flex-1 bg-purple-500/50 rounded-t-sm" style="height: 20%"></div>
                            <div class="flex-1 bg-purple-500/50 rounded-t-sm" style="height: 40%"></div>
                            <div class="flex-1 bg-purple-500/50 rounded-t-sm" style="height: 30%"></div>
                            <div class="flex-1 bg-purple-500 rounded-t-sm" style="height: 50%"></div>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Row 1.5: Cari Hesap Özetleri (2 Cols) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Toplam Alacaklar -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/20 to-amber-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-orange-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-orange-500/10 text-orange-500">
                                    <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                                </div>
                                <div class="text-orange-400 text-xs font-bold bg-orange-900/30 px-2 py-1 rounded-lg border border-orange-500/30">
                                    Alacaklar
                                </div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">₺{{ number_format($totalReceivables, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Toplam Müşteri Borcu</div>
                        </div>
                        <a href="{{ route('accounting.account-transactions.index') }}" 
                           class="mt-4 text-xs text-orange-400 hover:text-orange-300 font-bold flex items-center gap-1 group/link">
                            Cari Hesaplar
                            <span class="material-symbols-outlined text-[14px] group-hover/link:translate-x-1 transition-transform">arrow_forward</span>
                        </a>
                    </x-card>
                </div>

                <!-- Toplam Borçlar -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-purple-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-purple-500/10 text-purple-500">
                                    <span class="material-symbols-outlined text-[20px]">payments</span>
                                </div>
                                <div class="text-purple-400 text-xs font-bold bg-purple-900/30 px-2 py-1 rounded-lg border border-purple-500/30">
                                    Borçlar
                                </div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">₺{{ number_format($totalPayables, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Toplam Tedarikçi Borcu</div>
                        </div>
                        <a href="{{ route('accounting.account-transactions.index') }}" 
                           class="mt-4 text-xs text-purple-400 hover:text-purple-300 font-bold flex items-center gap-1 group/link">
                            Cari Hesaplar
                            <span class="material-symbols-outlined text-[14px] group-hover/link:translate-x-1 transition-transform">arrow_forward</span>
                        </a>
                    </x-card>
                </div>
            </div>

            <!-- Row 2: Charts & Recent Transactions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Monthly Trend Chart -->
                <div class="lg:col-span-2">
                    <x-card class="h-full p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl flex flex-col">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">bar_chart</span>
                                    Gelir / Gider Analizi
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-slate-500">Son 6 aylık finansal trendler</p>
                            </div>
                            <div class="px-3 py-1 bg-white/5 rounded-lg border border-gray-200 dark:border-white/10 text-xs font-bold text-gray-600 dark:text-slate-400">
                                Nakit Akışı
                            </div>
                        </div>
                        
                        <div class="flex-1 flex items-end justify-between gap-6 px-4 h-64">
                            @php
                                $maxAmount = max($monthlyReport->max('income'), $monthlyReport->max('expense')) ?: 1;
                            @endphp
                            @foreach($monthlyReport as $report)
                                <div class="flex-1 group cursor-pointer flex flex-col justify-end h-full">
                                    <div class="flex gap-1 items-end h-full justify-center">
                                        <!-- Income Bar -->
                                        <div class="flex-1 bg-gradient-to-t from-green-500/40 to-green-500 rounded-t-lg transition-all duration-500 group-hover:shadow-[0_0_15px_rgba(34,197,94,0.3)]"
                                             style="height: {{ ($report->income / $maxAmount) * 100 }}%">
                                             <!-- Tooltip -->
                                            <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-gray-900 dark:text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap border border-gray-200 dark:border-white/10 pointer-events-none z-10">
                                                +₺{{ number_format($report->income, 0) }}
                                            </div>
                                        </div>
                                        <!-- Expense Bar -->
                                        <div class="flex-1 bg-gradient-to-t from-red-500/40 to-red-500 rounded-t-lg transition-all duration-500 group-hover:shadow-[0_0_15px_rgba(239,68,68,0.3)]"
                                             style="height: {{ ($report->expense / $maxAmount) * 100 }}%">
                                             <!-- Tooltip -->
                                            <div class="absolute -top-16 left-1/2 -translate-x-1/2 bg-slate-800 text-gray-900 dark:text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap border border-gray-200 dark:border-white/10 pointer-events-none z-10">
                                                -₺{{ number_format($report->expense, 0) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3 text-[10px] font-bold text-gray-600 dark:text-slate-500 group-hover:text-gray-900 dark:text-white transition-colors uppercase">
                                        {{ \Carbon\Carbon::parse($report->month)->translatedFormat('M') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                </div>

                <!-- Recent Transactions -->
                <div class="lg:col-span-1">
                    <x-card class="h-full p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl flex flex-col">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-purple-400">history</span>
                                Son İşlemler
                            </h3>
                        </div>

                        <div class="flex-1 overflow-y-auto pr-2 space-y-1 custom-scrollbar">
                            @forelse($recentTransactions as $transaction)
                            <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition-colors group cursor-pointer">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-white/5 text-gray-600 dark:text-slate-400 group-hover:bg-primary/10 group-hover:text-primary transition-all">
                                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm text-gray-900 dark:text-white font-medium truncate">{{ $transaction->description ?: 'Genel İşlem' }}</div>
                                    <div class="text-[10px] text-gray-600 dark:text-slate-500 font-bold uppercase">{{ $transaction->date->diffForHumans() }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">₺{{ number_format($transaction->total_debit ?: $transaction->total_credit, 0) }}</div>
                                </div>
                            </div>
                            @empty
                            <div class="flex flex-col items-center justify-center h-full text-gray-600 dark:text-slate-500 py-8 opacity-50">
                                <span class="material-symbols-outlined text-4xl mb-2">history</span>
                                <div class="text-sm">İşlem bulunmuyor.</div>
                            </div>
                            @endforelse
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Row 3: Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach([
                    ['route' => 'accounting.invoices.create', 'icon' => 'post_add', 'label' => 'Fatura Kes', 'color' => 'blue'],
                    ['route' => 'accounting.transactions.create', 'icon' => 'add_card', 'label' => 'Yeni Fiş', 'color' => 'green'],
                    ['route' => 'accounting.accounts.index', 'icon' => 'account_tree', 'label' => 'Hesap Planı', 'color' => 'purple'],
                    ['route' => 'accounting.invoices.index', 'icon' => 'description', 'label' => 'E-Arşiv / Belge', 'color' => 'orange']
                ] as $action)
                    <a href="{{ route($action['route']) }}" class="group relative overflow-hidden rounded-2xl">
                        <div class="absolute inset-0 bg-white/5 border border-gray-200 dark:border-white/10 transition-colors duration-300 group-hover:border-{{ $action['color'] }}-500/30 group-hover:bg-{{ $action['color'] }}-500/5"></div>
                        
                        <div class="relative p-6 flex items-center justify-center gap-4">
                            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-{{ $action['color'] }}-500/20 to-{{ $action['color'] }}-500/10 text-{{ $action['color'] }}-400 group-hover:scale-110 transition-transform duration-300">
                                <span class="material-symbols-outlined text-[24px]">{{ $action['icon'] }}</span>
                            </div>
                            <div class="font-black text-gray-900 dark:text-white text-sm uppercase tracking-wide group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                {{ $action['label'] }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Row 3.5: Cari Hesap Detayları (2 Cols) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- En Yüksek Borçlu Müşteriler -->
                <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/5 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/[0.02]">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-orange-500">person</span>
                            En Yüksek Borçlu Müşteriler
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @forelse($topDebtors as $debtor)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-gray-100 dark:bg-white/10 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-orange-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-orange-500 text-[18px]">person</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $debtor->name }}</div>
                                        <div class="text-xs text-gray-600 dark:text-slate-500">{{ $debtor->type === 'customer' ? 'Müşteri' : 'Tedarikçi' }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-black text-orange-400">₺{{ number_format($debtor->balance, 2, ',', '.') }}</div>
                                    <a href="{{ route('accounting.account-transactions.show', $debtor->id) }}" 
                                       class="text-xs text-gray-600 dark:text-slate-500 hover:text-primary transition-colors">Ekstre →</a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-600 dark:text-slate-500 py-8">Borçlu müşteri bulunmuyor</div>
                        @endforelse
                    </div>
                </x-card>

                <!-- En Yüksek Alacaklı Tedarikçiler -->
                <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/5 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/[0.02]">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-500">business</span>
                            En Yüksek Alacaklı Tedarikçiler
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @forelse($topCreditors as $creditor)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-gray-100 dark:bg-white/10 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-purple-500 text-[18px]">business</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $creditor->name }}</div>
                                        <div class="text-xs text-gray-600 dark:text-slate-500">{{ $creditor->type === 'customer' ? 'Müşteri' : 'Tedarikçi' }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-black text-purple-400">₺{{ number_format(abs($creditor->balance), 2, ',', '.') }}</div>
                                    <a href="{{ route('accounting.account-transactions.show', $creditor->id) }}" 
                                       class="text-xs text-gray-600 dark:text-slate-500 hover:text-primary transition-colors">Ekstre →</a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-600 dark:text-slate-500 py-8">Alacaklı tedarikçi bulunmuyor</div>
                        @endforelse
                    </div>
                </x-card>
            </div>

            <!-- Row 4: Recent Invoices List -->
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/5 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-gray-50 dark:bg-white/[0.02]">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">description</span>
                        Son Faturalar
                    </h3>
                    <a href="{{ route('accounting.invoices.index') }}" class="text-xs text-primary font-bold uppercase tracking-wider hover:text-gray-900 dark:text-white transition-colors">Tümünü Gör</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-white/[0.02]">
                                <th class="p-4 text-[10px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Fatura No</th>
                                <th class="p-4 text-[10px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Müşteri / Kurum</th>
                                <th class="p-4 text-[10px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Tarih</th>
                                <th class="p-4 text-[10px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">Tutar</th>
                                <th class="p-4 text-[10px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">Durum</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($recentInvoices as $invoice)
                            <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors group cursor-pointer">
                                <td class="p-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">{{ $invoice->invoice_number }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-medium text-gray-700 dark:text-slate-300">{{ $invoice->contact->name ?? 'Müşteri Bilgisi Yok' }}</div>
                                </td>
                                <td class="p-4 text-xs text-gray-600 dark:text-slate-500 font-mono">
                                    {{ optional($invoice->issue_date)->format('d.m.Y') ?? 'Belirtilmedi' }}
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">₺{{ number_format($invoice->total_amount, 2, ',', '.') }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                        {{ $invoice->status === 'paid' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' }}">
                                        {{ $invoice->status === 'paid' ? 'Ödendi' : 'Bekliyor' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-600 dark:text-slate-500 italic">Henüz fatura kaydı bulunmuyor.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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

