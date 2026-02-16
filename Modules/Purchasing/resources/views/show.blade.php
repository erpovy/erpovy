<x-app-layout>
<div class="px-4 py-8 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('purchasing.orders.index') }}" class="p-2 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white">Sipariş: #{{ $order->order_number }}</h1>
                    <p class="text-gray-500 dark:text-gray-400">{{ $order->supplier->name ?? 'N/A' }} | {{ $order->order_date->format('d.m.Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4" x-data="{ showReceiveModal: false }">
                @if($order->status == 'received')
                    <form action="{{ route('purchasing.orders.convert-to-invoice', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 rounded-xl bg-purple-600 text-sm font-bold text-white shadow-lg shadow-purple-500/30 hover:bg-purple-700 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined">description</span>
                            FATURAYA DÖNÜŞTÜR
                        </button>
                    </form>
                @endif

                @if($order->status == 'sent')
                    <button @click="showReceiveModal = true" class="px-6 py-3 rounded-xl bg-emerald-600 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined">check_circle</span>
                        MAL KABULÜ YAP
                    </button>

                    <!-- Mal Kabul Modalı -->
                    <template x-teleport="body">
                        <div x-show="showReceiveModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak>
                            <div @click.away="showReceiveModal = false" class="bg-white dark:bg-slate-900 rounded-3xl p-8 max-w-md w-full border border-gray-100 dark:border-white/10 shadow-2xl">
                                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-4">Mal Kabulü Onayı</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6 text-sm">Ürünlerin hangi depoya giriş yapacağını seçiniz. Bu işlem stokları kalıcı olarak güncelleyecektir.</p>
                                
                                <form action="{{ route('purchasing.orders.receive', $order) }}" method="POST">
                                    @csrf
                                    <div class="mb-6">
                                        <label class="block text-xs font-black text-gray-400 uppercase mb-2">Hedef Depo</label>
                                        <select name="warehouse_id" required class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 transition-all dark:text-white">
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <button type="button" @click="showReceiveModal = false" class="flex-1 px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 text-sm font-bold text-gray-500 hover:bg-gray-50 transition-all">VAZGEÇ</button>
                                        <button type="submit" class="flex-1 px-4 py-3 rounded-xl bg-emerald-600 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition-all">ONAYLA</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </template>
                @endif
                <a href="{{ route('purchasing.orders.edit', $order) }}" class="px-6 py-3 rounded-xl border border-gray-200 dark:border-white/10 text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-all">Düzenle</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <!-- Kalemler -->
                <div class="bg-white/50 dark:bg-white/5 rounded-3xl overflow-hidden border border-gray-100 dark:border-white/10 backdrop-blur-xl">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                        <thead class="bg-gray-50/50 dark:bg-white/5">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Ürün</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Miktar</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Birim Fiyat</th>
                                <th class="px-6 py-4 text-center text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">KDV</th>
                                <th class="px-6 py-4 text-right text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Toplam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-purple-500/10 rounded-xl flex items-center justify-center">
                                                <span class="material-symbols-outlined text-purple-500">inventory</span>
                                            </div>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-300">{{ number_format($item->quantity, 2) }}</td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-600 dark:text-gray-300">{{ number_format($item->unit_price, 2) }} ₺</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-300">%{{ $item->tax_rate }}</td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white">{{ number_format($item->total_amount, 2) }} ₺</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($order->notes)
                    <div class="bg-white/50 dark:bg-white/5 rounded-3xl p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl">
                        <h3 class="text-sm font-black text-gray-400 uppercase mb-4">Sipariş Notu</h3>
                        <p class="text-gray-600 dark:text-gray-300 italic">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="space-y-8">
                <!-- Durum ve Özet -->
                <div class="bg-white/50 dark:bg-white/5 rounded-3xl p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl">
                    <h3 class="text-sm font-black text-gray-400 uppercase mb-6 tracking-widest">Hesap Özeti</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Durum</span>
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
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ara Toplam</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ number_format($order->total_amount - $order->tax_amount, 2) }} ₺</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">KDV Toplamı</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ number_format($order->tax_amount, 2) }} ₺</span>
                        </div>
                        <div class="h-px bg-gray-200 dark:bg-white/10 my-4"></div>
                        <div class="flex justify-between text-xl font-black text-purple-600">
                            <span>TOPLAM</span>
                            <span>{{ number_format($order->total_amount, 2) }} ₺</span>
                        </div>
                    </div>
                </div>

                <!-- Tedarikçi Detay -->
                <div class="bg-white/50 dark:bg-white/5 rounded-3xl p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl">
                    <h3 class="text-sm font-black text-gray-400 uppercase mb-6 tracking-widest">Tedarikçi Bilgileri</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">İsim</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $order->supplier->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">E-posta</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->supplier->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">Adres</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 capitalize">{{ $order->supplier->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
