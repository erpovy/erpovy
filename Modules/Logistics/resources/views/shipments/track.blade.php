<x-app-layout>
    <div class="py-12 bg-gradient-to-b from-gray-50 to-white dark:from-[#0f172a] dark:to-black min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-600 rounded-3xl shadow-xl shadow-indigo-500/20 mb-6 border-4 border-white dark:border-white/10">
                    <span class="material-icons-outlined text-white text-4xl">travel_explore</span>
                </div>
                <h2 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">Kargo Takip</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Sevkiyatınızın durumunu anlık olarak sorgulayın.</p>
            </div>

            <!-- Search Form -->
            <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-[2.5rem] p-4 shadow-sm mb-8">
                <form action="{{ route('logistics.shipments.track') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <span class="material-icons-outlined absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input type="text" name="number" value="{{ $trackingNumber }}" required
                            class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl pl-14 pr-6 py-4 text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all font-bold"
                            placeholder="Takip Numarasını Giriniz (Örn: TRK-...)">
                    </div>
                    <button type="submit" 
                        class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl transition-all shadow-lg shadow-indigo-500/30 uppercase tracking-widest text-sm">
                        SORGULA
                    </button>
                </form>
            </div>

            @if($trackingNumber)
                @if($shipment)
                    <!-- Shipment Details Card -->
                    <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-sm animate-fade-in">
                        <div class="bg-indigo-600 p-8 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-8 opacity-10">
                                <span class="material-icons-outlined text-[120px]">local_shipping</span>
                            </div>
                            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div>
                                    <p class="text-indigo-200 text-xs font-black uppercase tracking-widest mb-1">Takip Numarası</p>
                                    <h3 class="text-2xl font-black tracking-tight">#{{ $shipment->tracking_number }}</h3>
                                </div>
                                <div class="bg-white/20 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/20">
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Beklemede',
                                            'in_transit' => 'Yolda / Transfer Merkezinde',
                                            'delivered' => 'Teslim Edildi',
                                            'cancelled' => 'İptal Edildi',
                                        ];
                                    @endphp
                                    <p class="text-xs font-black uppercase tracking-widest opacity-80 mb-1">Güncel Durum</p>
                                    <p class="font-black text-lg">{{ $statusLabels[$shipment->status] ?? $shipment->status }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 space-y-8">
                            <!-- Progress Steps -->
                            <div class="relative flex items-center justify-between px-4">
                                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-100 dark:bg-white/10 -translate-y-1/2"></div>
                                <div class="absolute top-1/2 left-0 h-1 bg-indigo-500 -translate-y-1/2 transition-all duration-1000" 
                                     style="width: {{ $shipment->status == 'delivered' ? '100' : ($shipment->status == 'in_transit' ? '50' : '5') }}%"></div>
                                
                                @foreach(['pending', 'in_transit', 'delivered'] as $idx => $step)
                                    <div class="relative z-10 flex flex-col items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white dark:border-gray-800 transition-colors duration-500
                                            {{ ($shipment->status == $step || ($shipment->status == 'delivered') || ($shipment->status == 'in_transit' && $step == 'pending')) ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-400 dark:bg-white/10' }}">
                                            <span class="material-icons-outlined text-sm">
                                                {{ $step == 'pending' ? 'inventory_2' : ($step == 'in_transit' ? 'local_shipping' : 'done_all') }}
                                            </span>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-tighter text-gray-500">{{ $statusLabels[$step] }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="border-gray-100 dark:border-white/5">

                            <!-- Detailed Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <div class="flex gap-4">
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-white/5 rounded-2xl flex items-center justify-center text-gray-400">
                                            <span class="material-icons-outlined">location_on</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Çıkış Noktası</p>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $shipment->origin }}</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-white/5 rounded-2xl flex items-center justify-center text-indigo-500">
                                            <span class="material-icons-outlined">flag</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Varış Noktası</p>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $shipment->destination }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex gap-4">
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-white/5 rounded-2xl flex items-center justify-center text-gray-400">
                                            <span class="material-icons-outlined">person</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Alıcı / Müşteri</p>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $shipment->contact->name ?? 'Firma Yetkilisi' }}</p>
                                        </div>
                                    </div>
                                    @if($shipment->estimated_delivery)
                                    <div class="flex gap-4">
                                        <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500">
                                            <span class="material-icons-outlined">event_available</span>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Tahmini Teslimat</p>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $shipment->estimated_delivery->format('d.m.Y') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Not Found State -->
                    <div class="text-center p-12 bg-rose-500/10 rounded-[2.5rem] border border-rose-500/20 animate-fade-in">
                        <span class="material-icons-outlined text-rose-500 text-5xl mb-4">search_off</span>
                        <h3 class="text-xl font-bold text-rose-600 dark:text-rose-400 italic">Üzgünüz, Sevkiyat Bulunamadı</h3>
                        <p class="text-rose-500/70 mt-2 font-medium">Lütfen takip numaranızı kontrol edip tekrar deneyiniz.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>
