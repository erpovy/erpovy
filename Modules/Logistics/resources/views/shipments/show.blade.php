<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 bg-indigo-500/10 text-indigo-500 text-[10px] font-black uppercase tracking-widest rounded-full border border-indigo-500/20">
                            Sevkiyat Detayı
                        </span>
                        @if($shipment->route_id)
                        <span class="px-3 py-1 bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-500/20">
                            Rotaya Atandı
                        </span>
                        @endif
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                        {{ $shipment->tracking_number }}
                        <span class="material-symbols-outlined text-gray-400">local_shipping</span>
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logistics.shipments.edit', $shipment) }}" 
                        class="px-6 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-white text-sm font-bold rounded-2xl hover:bg-gray-50 dark:hover:bg-white/10 transition-all">
                        DÜZENLE
                    </a>
                    <a href="{{ route('logistics.shipments.index') }}" 
                        class="px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-2xl hover:opacity-90 transition-all">
                        LİSTEYE DÖN
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Core Info -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Status Card -->
                    <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-6">Mevcut Durum</h3>
                        <div class="relative">
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 dark:bg-white/5 rounded-full"></div>
                            @php
                                $statusSteps = [
                                    'pending' => ['label' => 'Beklemede', 'icon' => 'schedule'],
                                    'processing' => ['label' => 'Hazırlanıyor', 'icon' => 'inventory_2'],
                                    'in_transit' => ['label' => 'Yolda', 'icon' => 'local_shipping'],
                                    'delivered' => ['label' => 'Teslim Edildi', 'icon' => 'check_circle'],
                                ];
                                $currentIndex = array_search($shipment->status, array_keys($statusSteps));
                            @endphp
                            <div class="flex justify-between items-center relative z-10 text-center">
                                @foreach($statusSteps as $status => $step)
                                    @php
                                        $isCompleted = array_search($status, array_keys($statusSteps)) <= $currentIndex;
                                        $isActive = $status === $shipment->status;
                                    @endphp
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-500 {{ $isCompleted ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30 ring-4 ring-indigo-500/10' : 'bg-gray-50 dark:bg-white/5 text-gray-400' }}">
                                            <span class="material-symbols-outlined text-xl">{{ $step['icon'] }}</span>
                                        </div>
                                        <span class="mt-3 text-[10px] font-black uppercase tracking-tighter {{ $isCompleted ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400' }}">
                                            {{ $step['label'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Origin & Destination -->
                        <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                            <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">map</span>
                                Güzergah Bilgisi
                            </h3>
                            <div class="space-y-6">
                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                                        <div class="w-0.5 h-12 bg-dashed border-l border-dashed border-gray-300 dark:border-white/10"></div>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase">Çıkış Noktası</p>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">{{ $shipment->origin }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-3 h-3 rounded-full border-2 border-indigo-500"></div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase">Varış Noktası</p>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white mt-1">{{ $shipment->destination }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Logistics Specs -->
                        <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                            <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">straighten</span>
                                Ölçü & Ağırlık
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50 dark:bg-white/5 rounded-2xl">
                                    <p class="text-[10px] font-black text-gray-400 uppercase">Ağırlık</p>
                                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ $shipment->weight_kg ?? '0' }} <span class="text-xs font-normal">kg</span></p>
                                </div>
                                <div class="p-4 bg-gray-50 dark:bg-white/5 rounded-2xl">
                                    <p class="text-[10px] font-black text-gray-400 uppercase">Hacim</p>
                                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ $shipment->volume_m3 ?? '0' }} <span class="text-xs font-normal">m³</span></p>
                                </div>
                                <div class="p-4 bg-gray-50 dark:bg-white/5 rounded-2xl col-span-2">
                                    <p class="text-[10px] font-black text-gray-400 uppercase">Tahmini Teslimat</p>
                                    <p class="text-lg font-black text-gray-900 dark:text-white">{{ $shipment->estimated_delivery ? $shipment->estimated_delivery->format('d.m.Y') : 'Belirtilmedi' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline / Logs -->
                    <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-6">Durum Geçmişi (Timeline)</h3>
                        <div class="space-y-8">
                            @forelse($logs as $log)
                            <div class="flex gap-4 group">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-500 border border-gray-200 dark:border-white/10">
                                        <span class="material-symbols-outlined text-sm animate-pulse">history</span>
                                    </div>
                                    <div class="w-0.5 h-full bg-gray-100 dark:bg-white/5"></div>
                                </div>
                                <div class="pb-8">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-black text-gray-900 dark:text-white">{{ $log->new_status }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $log->created_at->format('d.m.Y H:i') }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->notes }}</p>
                                    @if($log->user)
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="w-4 h-4 rounded-full bg-indigo-500/20 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[10px] text-indigo-500">person</span>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-400">{{ $log->user->name }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <p class="text-xs text-gray-400 italic">Henüz geçmiş kaydı bulunmuyor.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sidebar -->
                <div class="space-y-8">
                    <!-- Customer Card -->
                    <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-6">Müşteri Bilgisi</h3>
                        @if($shipment->contact)
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black text-xl">
                                {{ substr($shipment->contact->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-gray-900 dark:text-white">{{ $shipment->contact->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $shipment->contact->email }}</p>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-white/10 space-y-4">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-400 font-bold uppercase">Telefon:</span>
                                <span class="text-gray-900 dark:text-white font-bold">{{ $shipment->contact->phone ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-400 font-bold uppercase">Şehir:</span>
                                <span class="text-gray-900 dark:text-white font-bold">{{ $shipment->contact->city ?? '-' }}</span>
                            </div>
                        </div>
                        @else
                        <p class="text-xs text-gray-400 italic">Müşteri atanmadı.</p>
                        @endif
                    </div>

                    <!-- Route Connection -->
                    <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm overflow-hidden relative group">
                        @if($shipment->route)
                        <div class="absolute -right-4 -top-4 opacity-5 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-8xl">map</span>
                        </div>
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-6 relative z-10">Rota Bağlantısı</h3>
                        <div class="relative z-10">
                            <h4 class="text-sm font-black text-indigo-600 dark:text-indigo-400">{{ $shipment->route->name }}</h4>
                            <p class="text-[10px] text-gray-500 mt-1 uppercase font-black">Planlanan: {{ $shipment->route->planned_date->format('d.m.Y') }}</p>
                            
                            @if($shipment->route->vehicle)
                            <div class="mt-4 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl flex items-center gap-3">
                                <span class="material-symbols-outlined text-gray-400">local_shipping</span>
                                <div>
                                    <p class="text-xs font-black text-gray-900 dark:text-white">{{ $shipment->route->vehicle->plate_number }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $shipment->route->vehicle->type }}</p>
                                </div>
                            </div>
                            @endif

                            <a href="{{ route('logistics.routes.show', $shipment->route_id) }}" 
                                class="mt-6 w-full inline-flex items-center justify-center px-4 py-3 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-black rounded-xl transition-all uppercase tracking-widest">
                                ROTAYI GÖRÜNTÜLE
                            </a>
                        </div>
                        @else
                        <div class="text-center py-6">
                            <span class="material-symbols-outlined text-gray-300 text-4xl mb-3">map</span>
                            <p class="text-xs text-gray-400 italic">Bu sevkiyat henüz bir rotaya atanmamış.</p>
                            <a href="{{ route('logistics.routes.create') }}" class="mt-4 inline-block text-[10px] font-black text-indigo-500 uppercase tracking-widest hover:underline">ROTA OLUŞTUR</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
