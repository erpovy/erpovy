<x-app-layout>
<div class="px-4 py-8 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-black text-gray-900 dark:text-gray-100 flex items-center gap-3">
                <span class="material-symbols-outlined text-purple-500 text-3xl">shopping_cart</span>
                Satın Alma Siparişleri
            </h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">
                Tedarikçilere verilen tüm siparişlerin listesi ve yönetim paneli.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('purchasing.orders.create') }}" class="group relative flex items-center justify-center gap-2 rounded-xl bg-purple-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-purple-500/30 transition-all hover:bg-purple-700 hover:scale-105 active:scale-95">
                <span class="material-symbols-outlined text-[20px]">add</span>
                YENİ SİPARİŞ OLUŞTUR
                <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </a>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mt-8">
        <div class="relative overflow-hidden rounded-2xl bg-white/50 dark:bg-white/5 p-6 border border-gray-100 dark:border-white/10 backdrop-blur-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Sipariş</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-white">{{ $orders->count() }}</p>
                </div>
                <div class="p-3 bg-purple-500/10 rounded-xl">
                    <span class="material-symbols-outlined text-purple-500">receipt_long</span>
                </div>
            </div>
        </div>
        <div class="relative overflow-hidden rounded-2xl bg-white/50 dark:bg-white/5 p-6 border border-gray-100 dark:border-white/10 backdrop-blur-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bekleyenler</p>
                    <p class="mt-2 text-3xl font-black text-amber-500">{{ $orders->where('status', 'sent')->count() }}</p>
                </div>
                <div class="p-3 bg-amber-500/10 rounded-xl">
                    <span class="material-symbols-outlined text-amber-500">pending_actions</span>
                </div>
            </div>
        </div>
        <div class="relative overflow-hidden rounded-2xl bg-white/50 dark:bg-white/5 p-6 border border-gray-100 dark:border-white/10 backdrop-blur-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Teslim Alınanlar</p>
                    <p class="mt-2 text-3xl font-black text-emerald-500">{{ $orders->where('status', 'received')->count() }}</p>
                </div>
                <div class="p-3 bg-emerald-500/10 rounded-xl">
                    <span class="material-symbols-outlined text-emerald-500">inventory_2</span>
                </div>
            </div>
        </div>
        <div class="relative overflow-hidden rounded-2xl bg-white/50 dark:bg-white/5 p-6 border border-gray-100 dark:border-white/10 backdrop-blur-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Toplam Tutar</p>
                    <p class="mt-2 text-3xl font-black text-gray-900 dark:text-white">{{ number_format($orders->sum('total_amount'), 2) }} ₺</p>
                </div>
                <div class="p-3 bg-blue-500/10 rounded-xl">
                    <span class="material-symbols-outlined text-blue-500">payments</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sipariş Listesi -->
    <div class="mt-8 overflow-hidden bg-white/50 dark:bg-white/5 rounded-3xl border border-gray-100 dark:border-white/10 backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                <thead class="bg-gray-50/50 dark:bg-white/5">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sipariş No</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tedarikçi</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tarih</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tutar</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-4 text-right text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">#{{ $order->order_number }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $order->supplier->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $order->order_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                {{ number_format($order->total_amount, 2) }} ₺
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'draft' => 'bg-gray-500/10 text-gray-500',
                                        'sent' => 'bg-amber-500/10 text-amber-500',
                                        'received' => 'bg-emerald-500/10 text-emerald-500',
                                        'cancelled' => 'bg-rose-500/10 text-rose-500',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusClasses[$order->status] ?? 'bg-gray-500/10 text-gray-500' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('purchasing.orders.show', $order) }}" class="p-2 text-gray-400 hover:text-purple-500 transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    <a href="{{ route('purchasing.orders.edit', $order) }}" class="p-2 text-gray-400 hover:text-blue-500 transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                Henüz satın alma siparişi bulunmuyor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>
