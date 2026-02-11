<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 via-emerald-500/5 to-green-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Gelir Tablosu
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">trending_up</span>
                        Gelir/Gider Analizi ve Net Kar/Zarar
                    </p>
                </div>
                <a href="{{ route('accounting.reports.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-900 dark:text-white text-sm font-medium transition-all">
                    <span class="material-symbols-outlined text-[18px] align-middle">arrow_back</span>
                    Raporlara Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Özet Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Toplam Gelir -->
                <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-green-500/20 to-emerald-500/20 text-green-400">
                            <span class="material-symbols-outlined text-[32px]">payments</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1">Toplam Gelir</h3>
                    <p class="text-3xl font-black text-green-400">{{ number_format($total_revenue, 2) }}₺</p>
                </x-card>

                <!-- Toplam Gider -->
                <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-red-500/20 to-orange-500/20 text-red-400">
                            <span class="material-symbols-outlined text-[32px]">shopping_cart</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1">Toplam Gider</h3>
                    <p class="text-3xl font-black text-red-400">{{ number_format($total_expense, 2) }}₺</p>
                </x-card>

                <!-- Net Kar/Zarar -->
                <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 text-{{ $net_income >= 0 ? 'blue' : 'orange' }}-400">
                            <span class="material-symbols-outlined text-[32px]">{{ $net_income >= 0 ? 'trending_up' : 'trending_down' }}</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1">Net {{ $net_income >= 0 ? 'Kar' : 'Zarar' }}</h3>
                    <p class="text-3xl font-black text-{{ $net_income >= 0 ? 'blue' : 'orange' }}-400">{{ number_format(abs($net_income), 2) }}₺</p>
                </x-card>
            </div>

            <!-- Gelirler Tablosu -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-400">arrow_upward</span>
                        Gelirler (6xx Hesaplar)
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-slate-400">{{ $start_date }} - {{ $end_date }}</span>
                </div>

                @if($revenues->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-transparent">
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Hesap Kodu</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Hesap Adı</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Tutar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revenues as $revenue)
                                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-slate-300 font-mono">{{ $revenue['code'] }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $revenue['name'] }}</td>
                                        <td class="py-3 px-4 text-sm text-green-400 font-semibold text-right">{{ number_format($revenue['amount'], 2) }}₺</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-green-500/10 border-t-2 border-green-500/30">
                                    <td colspan="2" class="py-3 px-4 text-sm font-bold text-gray-900 dark:text-white">TOPLAM GELİR</td>
                                    <td class="py-3 px-4 text-lg font-black text-green-400 text-right">{{ number_format($total_revenue, 2) }}₺</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[48px] opacity-50">inbox</span>
                        <p class="mt-2">Bu dönemde gelir kaydı bulunamadı.</p>
                    </div>
                @endif
            </x-card>

            <!-- Giderler Tablosu -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-red-400">arrow_downward</span>
                        Giderler (7xx Hesaplar)
                    </h3>
                </div>

                @if($expenses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-transparent">
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Hesap Kodu</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Hesap Adı</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Tutar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-slate-300 font-mono">{{ $expense['code'] }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $expense['name'] }}</td>
                                        <td class="py-3 px-4 text-sm text-red-400 font-semibold text-right">{{ number_format($expense['amount'], 2) }}₺</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-red-500/10 border-t-2 border-red-500/30">
                                    <td colspan="2" class="py-3 px-4 text-sm font-bold text-gray-900 dark:text-white">TOPLAM GİDER</td>
                                    <td class="py-3 px-4 text-lg font-black text-red-400 text-right">{{ number_format($total_expense, 2) }}₺</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[48px] opacity-50">inbox</span>
                        <p class="mt-2">Bu dönemde gider kaydı bulunamadı.</p>
                    </div>
                @endif
            </x-card>

            <!-- Net Kar/Zarar Özeti -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl border-{{ $net_income >= 0 ? 'blue' : 'orange' }}-500/30">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-600 dark:text-slate-300 mb-2">Dönem Sonu Net {{ $net_income >= 0 ? 'Kar' : 'Zarar' }}</h3>
                    <p class="text-5xl font-black text-{{ $net_income >= 0 ? 'blue' : 'orange' }}-400 mb-4">{{ number_format(abs($net_income), 2) }}₺</p>
                    <p class="text-sm text-gray-500 dark:text-slate-400">{{ $start_date }} - {{ $end_date }} dönemi</p>
                </div>
            </x-card>

        </div>
    </div>
</x-app-layout>
