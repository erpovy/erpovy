<x-app-layout>
<div class="px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between bg-white/50 dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/10 backdrop-blur-xl shadow-glass">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-black text-gray-900 dark:text-gray-100 flex items-center gap-3">
                <span class="material-symbols-outlined text-purple-500 text-4xl">dashboard</span>
                Satın Alma Özeti
            </h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-400 font-medium">
                Tedarik zinciri ve alım süreçlerine dair genel bakış ve performans metrikleri.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 flex gap-3">
            <a href="{{ route('purchasing.orders.create') }}" class="group relative flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-tr from-purple-600 to-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-purple-500/30 transition-all hover:scale-105 active:scale-95">
                <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                YENİ SİPARİŞ
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Toplam Harcama -->
        <div class="relative group overflow-hidden rounded-3xl bg-white/50 dark:bg-white/5 p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl transition-all hover:shadow-2xl hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Toplam Harcama</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-white">{{ number_format($stats['total_amount'], 2) }} ₺</p>
                </div>
                <div class="p-4 bg-purple-500/10 rounded-2xl group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-purple-500 text-3xl icon-filled">payments</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-transparent opacity-30"></div>
        </div>

        <!-- Sipariş Sayısı -->
        <div class="relative group overflow-hidden rounded-3xl bg-white/50 dark:bg-white/5 p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl transition-all hover:shadow-2xl hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Toplam Sipariş</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-white">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="p-4 bg-blue-500/10 rounded-2xl group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-blue-500 text-3xl icon-filled">receipt_long</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-transparent opacity-30"></div>
        </div>

        <!-- Teslim Alınanlar -->
        <div class="relative group overflow-hidden rounded-3xl bg-white/50 dark:bg-white/5 p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl transition-all hover:shadow-2xl hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Teslim Alınanlar</p>
                    <p class="mt-2 text-3xl font-black text-emerald-500">{{ $stats['received_count'] }}</p>
                </div>
                <div class="p-4 bg-emerald-500/10 rounded-2xl group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-emerald-500 text-3xl icon-filled">inventory_2</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-transparent opacity-30"></div>
        </div>

        <!-- Bekleyen Siparişler -->
        <div class="relative group overflow-hidden rounded-3xl bg-white/50 dark:bg-white/5 p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl transition-all hover:shadow-2xl hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Bekleyen Gönderim</p>
                    <p class="mt-2 text-3xl font-black text-amber-500">{{ $stats['pending_count'] }}</p>
                </div>
                <div class="p-4 bg-amber-500/10 rounded-2xl group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-amber-500 text-3xl icon-filled">pending_actions</span>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-amber-500 to-transparent opacity-30"></div>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Monthly Purchases Chart -->
        <div class="lg:col-span-2 bg-white/50 dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/10 backdrop-blur-xl shadow-glass">
            <h2 class="text-xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-purple-500">show_chart</span>
                Satın Alma Trendi
            </h2>
            <div class="h-[300px] flex items-end justify-between gap-2 px-4">
                @foreach($monthlyPurchases as $month)
                    <div class="flex-1 flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="absolute -top-10 scale-0 group-hover:scale-100 transition-all bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-3 py-1 rounded-lg text-xs font-bold whitespace-nowrap z-10">
                            {{ number_format($month->total, 2) }} ₺
                        </div>
                        <!-- Bar -->
                        <div class="w-full bg-gradient-to-t from-purple-500/20 to-purple-500/80 rounded-t-xl transition-all group-hover:brightness-125"
                             style="height: {{ ($month->total / ($monthlyPurchases->max('total') ?: 1)) * 240 }}px">
                        </div>
                        <!-- Label -->
                        <span class="mt-4 text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase rotate-45 lg:rotate-0">
                            {{ $month->month }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Suppliers -->
        <div class="bg-white/50 dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/10 backdrop-blur-xl shadow-glass">
            <h2 class="text-xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-500">star_rate</span>
                En İyi Tedarikçiler
            </h2>
            <div class="space-y-6">
                @forelse($topSuppliers as $supplier)
                    <div class="flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-gray-100 to-gray-200 dark:from-white/5 dark:to-white/10 flex items-center justify-center font-black text-gray-400 group-hover:text-primary transition-colors">
                            {{ substr($supplier->supplier->name ?? '?', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                {{ $supplier->supplier->name ?? 'Bilinmeyen' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $supplier->order_count }} Sipariş
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-gray-900 dark:text-white">
                                {{ number_format($supplier->total_spent, 2) }} ₺
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 italic py-8">Veri bulunmuyor.</p>
                @endforelse
            </div>
            
            <a href="#" class="mt-8 block text-center py-3 rounded-2xl bg-gray-100/50 dark:bg-white/5 text-xs font-black text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors uppercase tracking-widest">
                TÜM TEDARİKÇİLERİ GÖR
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white/50 dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/10 backdrop-blur-xl shadow-glass">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-500 active-icon">history</span>
                Son Siparişler
            </h2>
            <a href="{{ route('purchasing.orders.index') }}" class="text-xs font-black text-purple-500 hover:text-purple-600 uppercase tracking-widest">
                TÜMÜNÜ GÖR
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5">
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">No</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Tedarikçi</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Tarih</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Tutar</th>
                        <th class="px-4 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Durum</th>
                        <th class="px-4 py-4 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach($recentOrders as $order)
                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">#{{ $order->order_number }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="material-symbols-outlined text-[18px] opacity-40">business</span>
                                    {{ $order->supplier->name ?? '---' }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->order_date->format('d.m.Y') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-black text-gray-900 dark:text-white">
                                {{ number_format($order->total_amount, 2) }} ₺
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                    $statusIcons = [
                                        'draft' => ['icon' => 'edit_note', 'class' => 'text-gray-500 bg-gray-500/10'],
                                        'sent' => ['icon' => 'forward_to_inbox', 'class' => 'text-amber-500 bg-amber-500/10'],
                                        'received' => ['icon' => 'check_circle', 'class' => 'text-emerald-500 bg-emerald-500/10'],
                                        'cancelled' => ['icon' => 'cancel', 'class' => 'text-rose-500 bg-rose-500/10'],
                                    ];
                                    $status = $statusIcons[$order->status] ?? ['icon' => 'help', 'class' => 'text-gray-400 bg-gray-400/10'];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $status['class'] }}">
                                    <span class="material-symbols-outlined text-[14px]">{{ $status['icon'] }}</span>
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('purchasing.orders.show', $order) }}" class="p-2 text-gray-400 hover:text-purple-500 transition-colors opacity-0 group-hover:opacity-100">
                                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>
