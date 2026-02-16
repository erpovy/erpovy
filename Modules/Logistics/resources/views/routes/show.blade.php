<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 bg-indigo-500/10 text-indigo-500 text-[10px] font-black uppercase tracking-widest rounded-full border border-indigo-500/20">
                            Rota Detayı
                        </span>
                        @php
                            $statusClasses = [
                                'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-400',
                                'optimized' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400',
                                'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                                'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                            ];
                            $statusLabels = [
                                'draft' => 'Taslak',
                                'optimized' => 'Optimize Edildi',
                                'in_progress' => 'Yolda',
                                'completed' => 'Tamamlandı',
                            ];
                        @endphp
                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full {{ $statusClasses[$route->status] ?? $statusClasses['draft'] }}">
                            {{ $statusLabels[$route->status] ?? $route->status }}
                        </span>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight flex items-center gap-3">
                        {{ $route->name }}
                        <span class="material-symbols-outlined text-gray-400">route</span>
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logistics.routes.edit', $route) }}" 
                        class="px-6 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-white text-sm font-bold rounded-2xl hover:bg-gray-50 dark:hover:bg-white/10 transition-all">
                        DÜZENLE
                    </a>
                    <a href="{{ route('logistics.routes.index') }}" 
                        class="px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-bold rounded-2xl hover:opacity-90 transition-all">
                        LİSTEYE DÖN
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Route Stops & Info -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- vehicle & Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 p-6 rounded-3xl">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Atanan Araç</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                                    <span class="material-symbols-outlined">local_shipping</span>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 dark:text-white">{{ $route->vehicle->plate_number ?? 'Atanmadı' }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $route->vehicle->type ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 p-6 rounded-3xl">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Planlanan Tarih</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                                    <span class="material-symbols-outlined">calendar_today</span>
                                </div>
                                <p class="text-sm font-black text-gray-900 dark:text-white">{{ $route->planned_date->format('d.m.Y') }}</p>
                            </div>
                        </div>
                        <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 p-6 rounded-3xl">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Toplam Mesafe</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                    <span class="material-symbols-outlined">speed</span>
                                </div>
                                <p class="text-sm font-black text-gray-900 dark:text-white">{{ $route->total_distance ?? '0' }} <span class="text-xs font-normal">km</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Stops Timeline -->
                    <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-8 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-indigo-500">place</span>
                            Durak Akışı
                        </h3>
                        <div class="space-y-0 relative">
                            <div class="absolute left-4 top-0 h-full w-0.5 bg-gray-100 dark:bg-white/5"></div>
                            @forelse($route->stops ?? [] as $index => $stop)
                            <div class="relative pl-12 pb-10 group last:pb-0">
                                <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-white dark:bg-gray-800 border-2 border-indigo-500 flex items-center justify-center text-[10px] font-black text-indigo-500 z-10 group-hover:bg-indigo-500 group-hover:text-white transition-all shadow-lg shadow-indigo-500/10">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 bg-gray-50/50 dark:bg-white/2 rounded-2xl border border-transparent hover:border-indigo-500/20 transition-all">
                                    <div>
                                        <h4 class="text-sm font-black text-gray-900 dark:text-white">{{ $stop['location'] }}</h4>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="flex items-center gap-1 text-[10px] text-gray-400 uppercase font-black">
                                                <span class="material-symbols-outlined text-sm">schedule</span>
                                                Varış: {{ $stop['estimated_arrival'] ?? 'Belirtilmedi' }}
                                            </span>
                                        </div>
                                    </div>
                                    @if(isset($stop['shipment_id']))
                                        @php $s = $route->shipments->firstWhere('id', $stop['shipment_id']); @endphp
                                        @if($s)
                                        <a href="{{ route('logistics.shipments.show', $s->id) }}" class="flex items-center gap-3 p-3 bg-white dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10 hover:border-indigo-500/50 transition-all">
                                            <span class="material-symbols-outlined text-indigo-500 text-sm">inventory_2</span>
                                            <span class="text-[10px] font-black text-gray-500">{{ $s->tracking_number }}</span>
                                        </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <p class="text-xs text-gray-400 italic">Durak planı bulunmuyor.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Column: Connections -->
                <div class="space-y-8">
                    <!-- Linked Shipments -->
                    <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                        <h3 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-6">Bağlı Sevkiyatlar ({{ $route->shipments->count() }})</h3>
                        <div class="space-y-4">
                            @forelse($route->shipments as $shipment)
                            <a href="{{ route('logistics.shipments.show', $shipment->id) }}" class="block p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10 hover:border-indigo-500/50 transition-all group">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-black text-gray-900 dark:text-white group-hover:text-indigo-500 transition-colors">{{ $shipment->tracking_number }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-white dark:bg-white/10 text-gray-500 font-bold uppercase tracking-widest">
                                        {{ $shipment->status }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-400 truncate">{{ $shipment->destination }}</p>
                            </a>
                            @empty
                            <div class="text-center py-6">
                                <span class="material-symbols-outlined text-gray-300 text-4xl mb-2">inventory_2</span>
                                <p class="text-xs text-gray-400 italic">Henüz sevkiyat atanmamış.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-8xl">local_shipping</span>
                        </div>
                        <h3 class="text-sm font-black uppercase tracking-widest mb-2 relative z-10">Rota Yönetimi</h3>
                        <p class="text-indigo-100 text-xs mb-6 relative z-10 leading-relaxed">Rota durumunu güncelleyerek bağlı tüm sevkiyatları otomatik senkronize edebilirsiniz.</p>
                        
                        @if($route->status !== 'completed')
                        <form action="{{ route('logistics.routes.update', $route) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $route->name }}">
                            <input type="hidden" name="planned_date" value="{{ $route->planned_date->format('Y-m-d') }}">
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="w-full py-4 bg-white text-indigo-600 font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-indigo-50 transition-all shadow-lg relative z-10">
                                ROTAYI TAMAMLA
                            </button>
                        </form>
                        @else
                        <div class="py-4 bg-indigo-500/20 rounded-2xl border border-indigo-400/30 text-center relative z-10">
                            <span class="text-xs font-black uppercase tracking-widest">BU ROTA TAMAMLANDI</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
