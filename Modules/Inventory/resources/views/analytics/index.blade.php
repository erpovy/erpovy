<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <span>Stok Analitik Dashboard</span>
            <div class="flex gap-2">
                <a href="{{ route('inventory.analytics.export.excel') }}" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white text-sm rounded-lg transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">table_chart</span>
                    Excel
                </a>
                <a href="{{ route('inventory.analytics.export.pdf') }}" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-sm rounded-lg transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">picture_as_pdf</span>
                    PDF
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Metrik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <x-card>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-400">Toplam Stok Değeri</p>
                        <p class="text-2xl font-bold text-white mt-1">₺{{ number_format($totalStockValue, 2) }}</p>
                    </div>
                    <div class="p-3 bg-blue-500/10 rounded-lg">
                        <span class="material-symbols-outlined text-3xl text-blue-400">inventory_2</span>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-400">Kritik Ürünler</p>
                        <p class="text-2xl font-bold text-red-400 mt-1">{{ $criticalProducts }}</p>
                    </div>
                    <div class="p-3 bg-red-500/10 rounded-lg">
                        <span class="material-symbols-outlined text-3xl text-red-400">warning</span>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-400">Atıl Stoklar</p>
                        <p class="text-2xl font-bold text-orange-400 mt-1">{{ $obsoleteProducts }}</p>
                    </div>
                    <div class="p-3 bg-orange-500/10 rounded-lg">
                        <span class="material-symbols-outlined text-3xl text-orange-400">schedule</span>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-400">Ort. Devir Hızı</p>
                        <p class="text-2xl font-bold text-green-400 mt-1">{{ number_format($avgTurnover, 1) }}x</p>
                    </div>
                    <div class="p-3 bg-green-500/10 rounded-lg">
                        <span class="material-symbols-outlined text-3xl text-green-400">sync</span>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- ABC Dağılımı -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-card>
            <div class="p-6">
                <h3 class="text-lg font-bold text-white mb-4">ABC Sınıflandırması</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">A Sınıfı (Yüksek Değer)</span>
                        <span class="px-3 py-1 bg-red-500/10 text-red-400 rounded-full text-sm font-semibold">{{ $abcDistribution['A'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">B Sınıfı (Orta Değer)</span>
                        <span class="px-3 py-1 bg-yellow-500/10 text-yellow-400 rounded-full text-sm font-semibold">{{ $abcDistribution['B'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">C Sınıfı (Düşük Değer)</span>
                        <span class="px-3 py-1 bg-green-500/10 text-green-400 rounded-full text-sm font-semibold">{{ $abcDistribution['C'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Kritik Ürünler Tablosu -->
    <x-card>
        <div class="p-6">
            <h3 class="text-lg font-bold text-white mb-4">Detaylı Stok Analizi</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Ürün</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Kategori</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Stok</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Birim</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">ABC</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Gün</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Devir</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Risk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Durum</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-400 uppercase">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($criticalProductsList as $item)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-4 py-3 text-sm text-white">{{ $item['name'] }}</td>
                                <td class="px-4 py-3 text-sm text-slate-400">{{ $item['category'] }}</td>
                                <td class="px-4 py-3 text-center text-sm">
                                    <span class="text-white font-semibold">{{ $item['current_stock'] }}</span>
                                    <span class="text-slate-500">/{{ $item['min_stock'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-slate-400">
                                    {{ $item['unit'] ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($item['abc_class'] == 'A')
                                        <span class="px-2 py-1 bg-red-500/10 text-red-400 rounded-full text-xs font-semibold">A</span>
                                    @elseif($item['abc_class'] == 'B')
                                        <span class="px-2 py-1 bg-yellow-500/10 text-yellow-400 rounded-full text-xs font-semibold">B</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-500/10 text-green-400 rounded-full text-xs font-semibold">C</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-white">{{ $item['days_of_stock'] }}</td>
                                <td class="px-4 py-3 text-center text-sm text-white">{{ number_format($item['turnover'], 1) }}x</td>
                                <td class="px-4 py-3 text-center">
                                    @if($item['stockout_risk'] >= 80)
                                        <span class="text-2xl" title="Kritik Risk">🔴</span>
                                    @elseif($item['stockout_risk'] >= 60)
                                        <span class="text-2xl" title="Yüksek Risk">🟠</span>
                                    @elseif($item['obsolescence_risk'] >= 75)
                                        <span class="text-2xl" title="Atıl Stok">⚠️</span>
                                    @else
                                        <span class="text-2xl" title="Normal">🟢</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-400">{{ $item['status'] }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('inventory.analytics.product', $item['id']) }}" class="text-primary-400 hover:text-primary-300 text-sm">Detay</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-card>
</x-app-layout>
