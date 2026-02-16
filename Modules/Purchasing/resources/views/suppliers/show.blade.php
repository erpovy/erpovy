<x-app-layout>
<div class="px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between bg-white/50 dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/10 backdrop-blur-xl shadow-glass">
        <div class="flex items-center gap-6">
            <a href="{{ route('purchasing.suppliers.index') }}" class="p-3 bg-gray-100 dark:bg-white/5 rounded-2xl text-gray-400 hover:text-purple-500 transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    {{ $supplier->name }}
                </h1>
                <p class="mt-1 text-sm text-gray-700 dark:text-gray-400 font-medium">
                    Tedarikçi satın alma geçmişi ve finansal özet.
                </p>
            </div>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 flex gap-3">
            <a href="{{ route('purchasing.orders.create', ['supplier_id' => $supplier->id]) }}" class="group relative flex items-center justify-center gap-2 rounded-2xl bg-purple-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-purple-500/30 transition-all hover:scale-105 active:scale-95">
                <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                SİPARİŞ OLUŞTUR
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Supplier Info -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white/50 dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/10 backdrop-blur-xl shadow-glass">
                <h2 class="text-xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">contact_page</span>
                    İletişim Bilgileri
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center gap-3 p-4 bg-gray-100/50 dark:bg-white/5 rounded-2xl">
                        <span class="material-symbols-outlined text-gray-400">mail</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $supplier->email ?: '---' }}</span>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gray-100/50 dark:bg-white/5 rounded-2xl">
                        <span class="material-symbols-outlined text-gray-400">phone</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $supplier->phone ?: '---' }}</span>
                    </div>
                    <div class="flex flex-col gap-1 p-4 bg-gray-100/50 dark:bg-white/5 rounded-2xl">
                        <span class="text-[10px] font-black text-gray-500 uppercase">Adres</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $supplier->address ?: '---' }}</span>
                    </div>
                    @if($supplier->tax_number)
                    <div class="p-4 bg-gray-100/50 dark:bg-white/5 rounded-2xl grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-[10px] font-black text-gray-500 uppercase">Vergi No</span>
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $supplier->tax_number }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-gray-500 uppercase">Vergi Dairesi</span>
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-medium">{{ $supplier->tax_office }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-600 to-indigo-700 p-8 rounded-3xl shadow-xl text-white">
                <p class="text-sm font-black opacity-80 uppercase tracking-widest">Tahmini Cari Bakiye</p>
                <h3 class="mt-2 text-4xl font-black">{{ number_format($supplier->balance ?: 0, 2) }} ₺</h3>
                <p class="mt-4 text-xs opacity-60 italic">* Bu bakiye tüm modüllerdeki cari hareketlerin toplamıdır.</p>
            </div>
        </div>

        <!-- Purchase History -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white/50 dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/10 backdrop-blur-xl shadow-glass">
                <h2 class="text-xl font-black text-gray-900 dark:text-white mb-8 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-500">history</span>
                    Satın Alma Geçmişi
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-white/5">
                                <th class="px-4 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Sipariş No</th>
                                <th class="px-4 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Tarih</th>
                                <th class="px-4 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Tutar</th>
                                <th class="px-4 py-4 text-left text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Durum</th>
                                <th class="px-4 py-4 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse($orders as $order)
                                <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">#{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->order_date->format('d.m.Y') }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-black text-gray-900 dark:text-white">
                                        {{ number_format($order->total_amount, 2) }} ₺
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
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
                                    <td class="px-4 py-4 text-right">
                                        <a href="{{ route('purchasing.orders.show', $order) }}" class="p-2 text-gray-400 hover:text-purple-500 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500 dark:text-gray-400 italic">
                                        Bu tedarikçiye ait sipariş bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
