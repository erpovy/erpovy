<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-100 via-gray-50 to-gray-100 dark:from-primary/5 dark:via-purple-500/5 dark:to-blue-500/5 animate-pulse"></div>
            <div class="relative flex justify-between items-center py-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-center text-gray-500 dark:text-slate-400 shadow-sm dark:shadow-none">
                        <span class="material-symbols-outlined text-[24px]">analytics</span>
                    </div>
                    <div>
                        <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1 font-display">
                            Stok Analitik
                        </h2>
                        <p class="text-gray-500 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary">insights</span>
                            Envanter sağlığı ve risk analizi
                        </p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('inventory.analytics.export.excel') }}" 
                       class="flex items-center gap-2 px-5 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-white/10 text-gray-700 dark:text-slate-300 font-bold text-sm hover:bg-emerald-50 dark:hover:bg-emerald-500/10 hover:border-emerald-200 dark:hover:border-emerald-500/30 hover:text-emerald-700 dark:hover:text-emerald-400 transition-all shadow-sm dark:shadow-none group">
                        <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">table_chart</span>
                        <span class="hidden sm:inline">Excel</span>
                    </a>
                    <a href="{{ route('inventory.analytics.export.pdf') }}" 
                       class="flex items-center gap-2 px-5 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-white/10 text-gray-700 dark:text-slate-300 font-bold text-sm hover:bg-red-50 dark:hover:bg-red-500/10 hover:border-red-200 dark:hover:border-red-500/30 hover:text-red-700 dark:hover:text-red-400 transition-all shadow-sm dark:shadow-none group">
                        <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">picture_as_pdf</span>
                        <span class="hidden sm:inline">PDF</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen transition-colors duration-300">
        <div class="container mx-auto px-6 lg:px-8 max-w-[1600px]">
            
            <!-- Metrik Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Toplam Stok Değeri -->
                <div class="p-6 bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 rounded-3xl relative overflow-hidden group hover:border-blue-300 dark:hover:border-blue-500/30 transition-all shadow-sm dark:shadow-2xl">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 duration-500">
                        <span class="material-symbols-outlined text-[80px] text-blue-500">inventory_2</span>
                    </div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-500/20 flex items-center justify-center mb-4 border border-blue-100 dark:border-blue-500/30">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-[24px]">payments</span>
                        </div>
                        <p class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1">Toplam Stok Değeri</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                            <span class="text-lg text-gray-400 dark:text-slate-500 align-top">₺</span>{{ number_format($totalStockValue, 2) }}
                        </p>
                    </div>
                </div>

                <!-- Kritik Ürünler -->
                <div class="p-6 bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 rounded-3xl relative overflow-hidden group hover:border-red-300 dark:hover:border-red-500/30 transition-all shadow-sm dark:shadow-2xl">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 duration-500">
                        <span class="material-symbols-outlined text-[80px] text-red-500">warning</span>
                    </div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-500/20 flex items-center justify-center mb-4 border border-red-100 dark:border-red-500/30">
                            <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-[24px]">gpp_maybe</span>
                        </div>
                        <p class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1">Kritik Ürünler</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $criticalProducts }}</p>
                    </div>
                </div>

                <!-- Atıl Stoklar -->
                <div class="p-6 bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 rounded-3xl relative overflow-hidden group hover:border-orange-300 dark:hover:border-orange-500/30 transition-all shadow-sm dark:shadow-2xl">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 duration-500">
                        <span class="material-symbols-outlined text-[80px] text-orange-500">schedule</span>
                    </div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-500/20 flex items-center justify-center mb-4 border border-orange-100 dark:border-orange-500/30">
                            <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-[24px]">hourglass_empty</span>
                        </div>
                        <p class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1">Atıl Stoklar</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $obsoleteProducts }}</p>
                    </div>
                </div>

                <!-- Ort. Devir Hızı -->
                <div class="p-6 bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 rounded-3xl relative overflow-hidden group hover:border-emerald-300 dark:hover:border-emerald-500/30 transition-all shadow-sm dark:shadow-2xl">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 duration-500">
                        <span class="material-symbols-outlined text-[80px] text-emerald-500">sync</span>
                    </div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-500/20 flex items-center justify-center mb-4 border border-emerald-100 dark:border-emerald-500/30">
                            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[24px]">speed</span>
                        </div>
                        <p class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1">Ort. Devir Hızı</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($avgTurnover, 1) }}<span class="text-lg text-gray-400 dark:text-slate-500 ml-1">x</span></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Sol Kolon: Kritik Stok Tablosu -->
                <div class="lg:col-span-8">
                    <div class="bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 rounded-3xl shadow-sm dark:shadow-2xl overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-8 bg-red-500 rounded-full"></span>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">Detaylı Stok Analizi</h3>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5">
                                        <th class="px-6 py-4 text-left text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">Ürün</th>
                                        <th class="px-6 py-4 text-left text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">Kategori</th>
                                        <th class="px-6 py-4 text-center text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">Stok</th>
                                        <th class="px-6 py-4 text-center text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">ABC</th>
                                        <th class="px-6 py-4 text-center text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">Risk</th>
                                        <th class="px-6 py-4 text-right text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                    @foreach($criticalProductsList as $item)
                                        <tr class="group hover:bg-blue-50/50 dark:hover:bg-blue-500/5 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/10 flex items-center justify-center text-gray-500 dark:text-slate-400 font-bold text-xs">
                                                        {{ substr($item['name'], 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $item['name'] }}</div>
                                                        <div class="text-[10px] text-gray-500 dark:text-slate-500 font-mono">{{ $item['code'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 rounded-md bg-gray-100 dark:bg-white/5 text-[10px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider border border-gray-200 dark:border-white/5">
                                                    {{ $item['category'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex flex-col items-center">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $item['current_stock'] }} <span class="text-[10px] text-gray-500">{{ $item['unit'] ?? '' }}</span></div>
                                                    <div class="text-[10px] text-gray-400">Min: {{ $item['min_stock'] }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                 @if($item['abc_class'] == 'A')
                                                    <span class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 flex items-center justify-center font-black text-xs border border-red-200 dark:border-red-500/30 shadow-sm ml-auto mr-auto">A</span>
                                                @elseif($item['abc_class'] == 'B')
                                                    <span class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center font-black text-xs border border-amber-200 dark:border-amber-500/30 shadow-sm ml-auto mr-auto">B</span>
                                                @else
                                                    <span class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black text-xs border border-emerald-200 dark:border-emerald-500/30 shadow-sm ml-auto mr-auto">C</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($item['stockout_risk'] >= 80)
                                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400">
                                                        <span class="relative flex h-2 w-2">
                                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                        </span>
                                                        <span class="text-[10px] font-bold uppercase tracking-wide">Kritik</span>
                                                    </div>
                                                @elseif($item['stockout_risk'] >= 60)
                                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-50 dark:bg-orange-500/10 border border-orange-200 dark:border-orange-500/20 text-orange-700 dark:text-orange-400">
                                                        <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                                                        <span class="text-[10px] font-bold uppercase tracking-wide">Yüksek</span>
                                                    </div>
                                                @else
                                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400">
                                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                        <span class="text-[10px] font-bold uppercase tracking-wide">Normal</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('inventory.analytics.product', $item['id']) }}" 
                                                   class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-500/20 hover:text-blue-600 dark:hover:text-blue-400 transition-all ml-auto">
                                                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sağ Kolon: ABC ve Özet -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- ABC Dağılımı -->
                    <div class="p-6 bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 rounded-3xl shadow-sm dark:shadow-2xl">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center border border-purple-100 dark:border-purple-500/20">
                                <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">bar_chart_4_bars</span>
                            </span>
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">ABC Analizi</h3>
                                <p class="text-xs text-gray-500 dark:text-slate-400 font-bold">Stok değeri dağılımı</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- A Sınıfı -->
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-[#0f172a] border border-gray-100 dark:border-white/5 relative overflow-hidden">
                                <div class="flex justify-between items-center mb-2 z-10 relative">
                                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300">A Sınıfı</span>
                                    <span class="px-2 py-1 bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 rounded-lg text-xs font-black">{{ $abcDistribution['A'] ?? 0 }} Ürün</span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-red-500 rounded-full" style="width: 70%"></div>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2 font-medium">Yüksek değerli, kritik ürünler. Yakından takip edilmeli.</p>
                            </div>

                            <!-- B Sınıfı -->
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-[#0f172a] border border-gray-100 dark:border-white/5 relative overflow-hidden">
                                <div class="flex justify-between items-center mb-2 z-10 relative">
                                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300">B Sınıfı</span>
                                    <span class="px-2 py-1 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded-lg text-xs font-black">{{ $abcDistribution['B'] ?? 0 }} Ürün</span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full" style="width: 45%"></div>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2 font-medium">Orta değerli ürünler. Periyodik kontrol yeterlidir.</p>
                            </div>

                            <!-- C Sınıfı -->
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-[#0f172a] border border-gray-100 dark:border-white/5 relative overflow-hidden">
                                <div class="flex justify-between items-center mb-2 z-10 relative">
                                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300">C Sınıfı</span>
                                    <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-black">{{ $abcDistribution['C'] ?? 0 }} Ürün</span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 20%"></div>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2 font-medium">Düşük değerli, hacimli ürünler. Otomatik sipariş önerilir.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Yardımcı Bilgi -->
                    <div class="p-6 rounded-3xl bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-500/10">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">lightbulb</span>
                            </div>
                            <p class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed font-medium">
                                <span class="font-bold">İpucu:</span> C sınıfı ürünlerde stok tutma maliyetini düşürmek için "Just-in-Time" (JIT) sipariş yöntemini değerlendirebilirsiniz.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
