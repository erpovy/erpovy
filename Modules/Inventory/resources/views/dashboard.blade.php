<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Stok Yönetimi Özeti
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                        Envanter sağlığı ve operasyonel bakış
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('inventory.products.create') }}" class="flex items-center gap-2 px-5 py-3 rounded-xl bg-gray-900 dark:bg-primary-600 text-white font-bold text-sm hover:bg-gray-800 dark:hover:bg-primary-500 transition-all shadow-lg shadow-gray-200/50 dark:shadow-primary-500/20">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        Yeni Ürün
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Bilgi Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Toplam Ürün -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-blue-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-blue-500/10 text-blue-500">
                                    <span class="material-symbols-outlined text-[24px]">inventory_2</span>
                                </div>
                                <div class="text-blue-400 text-xs font-bold bg-blue-900/30 px-2 py-1 rounded-lg border border-blue-500/30">Ürünler</div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ number_format($totalProducts) }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Kayıtlı Toplam Ürün</div>
                        </div>
                    </x-card>
                </div>

                <!-- Stok Değeri -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-emerald-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-500">
                                    <span class="material-symbols-outlined text-[24px]">payments</span>
                                </div>
                                <div class="text-emerald-400 text-xs font-bold bg-emerald-900/30 px-2 py-1 rounded-lg border border-emerald-500/30">Değer</div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">₺{{ number_format($totalStockValue, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Tahmini Toplam Stok Değeri</div>
                        </div>
                    </x-card>
                </div>

                <!-- Kritik Stok -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/20 to-amber-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-orange-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-orange-500/10 text-orange-500">
                                    <span class="material-symbols-outlined text-[24px]">warning</span>
                                </div>
                                <div class="text-orange-400 text-xs font-bold bg-orange-900/30 px-2 py-1 rounded-lg border border-orange-500/30">Kritik</div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ number_format($criticalStockCount) }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Eşik Değer Altı Ürünler</div>
                        </div>
                    </x-card>
                </div>

                <!-- Stokta Yok -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/20 to-rose-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-red-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-red-500/10 text-red-500">
                                    <span class="material-symbols-outlined text-[24px]">block</span>
                                </div>
                                <div class="text-red-400 text-xs font-bold bg-red-900/30 px-2 py-1 rounded-lg border border-red-500/30">Tükendi</div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ number_format($outOfStockCount) }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Stoku Tükenmiş Ürünler</div>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Grafikler ve Son Hareketler -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Haftalık Hareket Analizi -->
                <div class="lg:col-span-2">
                    <x-card class="h-full p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">insights</span>
                                    Haftalık Stok Hareketleri
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-slate-500">Giriş ve çıkış miktarlarının karşılaştırması</p>
                            </div>
                        </div>

                        <div class="h-64 flex items-end justify-between gap-4 px-4">
                            @php
                                $days = collect();
                                for($i = 6; $i >= 0; $i--) {
                                    $days->put(now()->subDays($i)->format('Y-m-d'), [
                                        'in' => 0,
                                        'out' => 0,
                                        'label' => now()->subDays($i)->translatedFormat('D')
                                    ]);
                                }

                                foreach($weeklyMovements as $mv) {
                                    if($days->has($mv->date)) {
                                        $d = $days->get($mv->date);
                                        $d[$mv->type] = $mv->total;
                                        $days->put($mv->date, $d);
                                    }
                                }

                                $maxMv = collect($days->values())->map(fn($d) => max($d['in'], $d['out']))->max() ?: 1;
                            @endphp

                            @foreach($days as $date => $data)
                                <div class="flex-1 group flex flex-col justify-end h-full relative">
                                    <div class="flex gap-1 items-end h-full justify-center">
                                        <div class="w-2 bg-emerald-500/40 rounded-t-full transition-all duration-500 group-hover:bg-emerald-500" 
                                             style="height: {{ ($data['in'] / $maxMv) * 100 }}%"></div>
                                        <div class="w-2 bg-rose-500/40 rounded-t-full transition-all duration-500 group-hover:bg-rose-500" 
                                             style="height: {{ ($data['out'] / $maxMv) * 100 }}%"></div>
                                    </div>
                                    <div class="text-[10px] font-bold text-gray-500 text-center mt-2 group-hover:text-primary transition-colors">{{ $data['label'] }}</div>
                                    
                                    <!-- Tooltip -->
                                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 bg-gray-900 border border-white/10 text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10 whitespace-nowrap">
                                        G: {{ number_format($data['in']) }} | Ç: {{ number_format($data['out']) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                </div>

                <!-- Son Aktiviteler -->
                <div class="lg:col-span-1">
                    <x-card class="h-full p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-400">history</span>
                                Son Hareketler
                            </h3>
                        </div>

                        <div class="space-y-4">
                            @forelse($recentMovements as $movement)
                                <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition-colors group">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $movement->type == 'in' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                                        <span class="material-symbols-outlined">{{ $movement->type == 'in' ? 'add_circle' : 'remove_circle' }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $movement->product?->name }}</div>
                                        <div class="text-[10px] text-gray-500 font-medium uppercase">{{ $movement->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-black text-gray-900 dark:text-white">{{ $movement->type == 'in' ? '+' : '-' }}{{ number_format($movement->quantity) }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $movement->product?->unit?->symbol }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 opacity-50">
                                    <span class="material-symbols-outlined text-4xl block mb-2">inventory</span>
                                    Henüz hareket yok.
                                </div>
                            @endforelse
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Kritik Stok Listesi -->
            <div class="grid grid-cols-1 gap-6">
                <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/5 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-white/[0.02]">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-orange-500">warning</span>
                            Kritik Stoktaki Ürünler
                        </h3>
                        <div class="text-xs font-bold text-gray-500 uppercase">Eşik Değer Altı</div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5">
                                    <th class="p-4 text-[10px] font-black uppercase tracking-wider text-gray-500">Ürün</th>
                                    <th class="p-4 text-[10px] font-black uppercase tracking-wider text-gray-500">Kategori</th>
                                    <th class="p-4 text-[10px] font-black uppercase tracking-wider text-gray-500 text-center">Mevcut Stok</th>
                                    <th class="p-4 text-[10px] font-black uppercase tracking-wider text-gray-500 text-center">Kritik Eşik</th>
                                    <th class="p-4 text-[10px] font-black uppercase tracking-wider text-gray-500 text-right">İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($criticalProducts as $product)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-500 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                                            </div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $product->name }}</div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-xs font-medium text-gray-500">{{ $product->category->name ?? '-' }}</td>
                                    <td class="p-4 text-center">
                                        <span class="px-2 py-1 rounded-md bg-red-900/30 text-red-500 text-xs font-black">{{ number_format($product->current_stock) }} {{ $product->unit->symbol ?? '' }}</span>
                                    </td>
                                    <td class="p-4 text-center text-xs font-black text-gray-400">
                                        {{ number_format($product->critical_stock_level ?? $product->min_stock_level ?? 0) }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('inventory.products.edit', $product->id) }}" class="p-2 rounded-lg hover:bg-white/10 text-gray-500 hover:text-white transition-all">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-gray-500 text-sm">Harika! Şu an kritik seviyede ürün bulunmuyor.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>

            <!-- Hızlı Aksiyonlar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach([
                    ['route' => 'inventory.products.index', 'icon' => 'list_alt', 'label' => 'Ürün Listesi', 'color' => 'blue'],
                    ['route' => 'inventory.analytics.index', 'icon' => 'analytics', 'label' => 'Analiz Raporu', 'color' => 'emerald'],
                    ['route' => 'inventory.products.import.form', 'icon' => 'upload_file', 'label' => 'Excel Yükle', 'color' => 'orange'],
                    ['route' => 'inventory.analytics.export.excel', 'icon' => 'download', 'label' => 'Stok Çıktısı', 'color' => 'purple']
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
