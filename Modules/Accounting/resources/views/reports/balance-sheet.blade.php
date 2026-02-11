<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-cyan-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Bilanço
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">account_balance</span>
                        Aktif/Pasif Dengesi ve Mali Durum
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
                <!-- Toplam Aktif -->
                <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-green-500/20 to-emerald-500/20 text-green-400">
                            <span class="material-symbols-outlined text-[32px]">trending_up</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1">Toplam Aktif (Varlıklar)</h3>
                    <p class="text-3xl font-black text-green-400">{{ number_format($total_assets, 2) }}₺</p>
                </x-card>

                <!-- Toplam Pasif -->
                <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-orange-500/20 to-red-500/20 text-orange-400">
                            <span class="material-symbols-outlined text-[32px]">trending_down</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1">Toplam Pasif (Yükümlülükler)</h3>
                    <p class="text-3xl font-black text-orange-400">{{ number_format($total_liabilities, 2) }}₺</p>
                </x-card>

                <!-- Özkaynaklar -->
                <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 text-blue-400">
                            <span class="material-symbols-outlined text-[32px]">account_balance_wallet</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1">Özkaynaklar</h3>
                    <p class="text-3xl font-black text-blue-400">{{ number_format($total_equity, 2) }}₺</p>
                </x-card>
            </div>

            <!-- Aktifler (Varlıklar) -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-400">arrow_upward</span>
                        Aktifler (Varlıklar)
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-slate-400">{{ $as_of_date }} tarihi itibariyle</span>
                </div>

                @if($assets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-transparent">
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Hesap Kodu</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Hesap Adı</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Bakiye</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assets as $asset)
                                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-slate-300 font-mono">{{ $asset['code'] }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $asset['name'] }}</td>
                                        <td class="py-3 px-4 text-sm text-green-400 font-semibold text-right">{{ number_format($asset['balance'], 2) }}₺</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-green-500/10 border-t-2 border-green-500/30">
                                    <td colspan="2" class="py-3 px-4 text-sm font-bold text-gray-900 dark:text-white">TOPLAM AKTİF</td>
                                    <td class="py-3 px-4 text-lg font-black text-green-400 text-right">{{ number_format($total_assets, 2) }}₺</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[48px] opacity-50">inbox</span>
                        <p class="mt-2">Aktif kaydı bulunamadı.</p>
                    </div>
                @endif
            </x-card>

            <!-- Pasifler (Yükümlülükler) -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-400">arrow_downward</span>
                        Pasifler (Yükümlülükler)
                    </h3>
                </div>

                @if($liabilities->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-transparent">
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Hesap Kodu</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Hesap Adı</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Bakiye</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($liabilities as $liability)
                                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-slate-300 font-mono">{{ $liability['code'] }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $liability['name'] }}</td>
                                        <td class="py-3 px-4 text-sm text-orange-400 font-semibold text-right">{{ number_format($liability['balance'], 2) }}₺</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-orange-500/10 border-t-2 border-orange-500/30">
                                    <td colspan="2" class="py-3 px-4 text-sm font-bold text-gray-900 dark:text-white">TOPLAM PASİF</td>
                                    <td class="py-3 px-4 text-lg font-black text-orange-400 text-right">{{ number_format($total_liabilities, 2) }}₺</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[48px] opacity-50">inbox</span>
                        <p class="mt-2">Pasif kaydı bulunamadı.</p>
                    </div>
                @endif
            </x-card>

            <!-- Özkaynaklar -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-400">account_balance_wallet</span>
                        Özkaynaklar
                    </h3>
                </div>

                @if($equity->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-transparent">
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Hesap Kodu</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Hesap Adı</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-400">Bakiye</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($equity as $eq)
                                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="py-3 px-4 text-sm text-gray-700 dark:text-slate-300 font-mono">{{ $eq['code'] }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $eq['name'] }}</td>
                                        <td class="py-3 px-4 text-sm text-blue-400 font-semibold text-right">{{ number_format($eq['balance'], 2) }}₺</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-blue-500/10 border-t-2 border-blue-500/30">
                                    <td colspan="2" class="py-3 px-4 text-sm font-bold text-gray-900 dark:text-white">TOPLAM ÖZKAYNAKLAR</td>
                                    <td class="py-3 px-4 text-lg font-black text-blue-400 text-right">{{ number_format($total_equity, 2) }}₺</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-[48px] opacity-50">inbox</span>
                        <p class="mt-2">Özkaynak kaydı bulunamadı.</p>
                    </div>
                @endif
            </x-card>

            <!-- Bilanço Dengesi -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl border-{{ $total_assets == $total_liabilities_equity ? 'green' : 'red' }}-500/30">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-600 dark:text-slate-300 mb-2">Toplam Aktif</h3>
                        <p class="text-4xl font-black text-green-400">{{ number_format($total_assets, 2) }}₺</p>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-600 dark:text-slate-300 mb-2">Toplam Pasif + Özkaynaklar</h3>
                        <p class="text-4xl font-black text-blue-400">{{ number_format($total_liabilities_equity, 2) }}₺</p>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    @if($total_assets == $total_liabilities_equity)
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-500/20 border border-green-500/30">
                            <span class="material-symbols-outlined text-green-400">check_circle</span>
                            <span class="text-green-400 font-semibold">Bilanço Dengede</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-500/20 border border-red-500/30">
                            <span class="material-symbols-outlined text-red-400">error</span>
                            <span class="text-red-400 font-semibold">Bilanço Dengesiz! Fark: {{ number_format(abs($total_assets - $total_liabilities_equity), 2) }}₺</span>
                        </div>
                    @endif
                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-2">{{ $as_of_date }} tarihi itibariyle</p>
                </div>
            </x-card>

        </div>
    </div>
</x-app-layout>
