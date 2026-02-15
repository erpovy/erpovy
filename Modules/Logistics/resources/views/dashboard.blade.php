<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-cyan-500/10 to-indigo-500/10 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-4">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Lojistik & Sevkiyat
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
                        Operasyonel Durum ve Sevkiyat Takibi
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logistics.shipments.create') }}" class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 px-4 py-2 rounded-xl text-sm font-bold text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-white/10 transition-all flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-plus text-blue-500"></i>
                        Yeni Sevkiyat
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Shipments -->
            <a href="{{ route('logistics.shipments.index') }}" class="bg-white dark:bg-[#0f172a]/20 backdrop-blur-xl border border-gray-200 dark:border-white/10 p-6 rounded-3xl shadow-glass group hover:border-blue-500/50 transition-all duration-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                        <i class="fa-solid fa-truck-fast text-blue-500 text-xl"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Toplam Sevkiyat</h3>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-black text-gray-900 dark:text-white leading-none tracking-tight">{{ $totalShipments }}</span>
                </div>
            </a>

            <!-- Active Routes -->
            <a href="{{ route('logistics.routes.index') }}" class="bg-white dark:bg-[#0f172a]/20 backdrop-blur-xl border border-gray-200 dark:border-white/10 p-6 rounded-3xl shadow-glass group hover:border-cyan-500/50 transition-all duration-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-cyan-500/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                        <i class="fa-solid fa-route text-cyan-500 text-xl"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Aktif Rotalar</h3>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-black text-gray-900 dark:text-white leading-none tracking-tight">{{ $activeRoutes }}</span>
                </div>
            </a>

            <!-- Available Vehicles -->
            <a href="{{ route('logistics.vehicles.index') }}" class="bg-white dark:bg-[#0f172a]/20 backdrop-blur-xl border border-gray-200 dark:border-white/10 p-6 rounded-3xl shadow-glass group hover:border-indigo-500/50 transition-all duration-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-500/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                        <i class="fa-solid fa-car text-indigo-500 text-xl"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Araç Yönetimi</h3>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-black text-gray-900 dark:text-white leading-none tracking-tight">{{ $availableVehicles }} / {{ $totalVehicles }}</span>
                    <span class="text-xs text-gray-500 mb-1 font-bold">Müsait</span>
                </div>
            </a>

            <!-- Pending Deliveries -->
            <a href="{{ route('logistics.shipments.index', ['status' => 'pending']) }}" class="bg-white dark:bg-[#0f172a]/20 backdrop-blur-xl border border-gray-200 dark:border-white/10 p-6 rounded-3xl shadow-glass group hover:border-amber-500/50 transition-all duration-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                        <i class="fa-solid fa-clock text-amber-500 text-xl"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 dark:text-gray-400 text-sm font-bold uppercase tracking-wider mb-1">Bekleyen Teslimat</h3>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-black text-gray-900 dark:text-white leading-none tracking-tight">{{ $pendingShipments }}</span>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Shipments -->
            <div class="bg-white dark:bg-[#0f172a]/20 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl shadow-glass overflow-hidden h-full">
                <div class="p-6 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-blue-500"></i>
                        Son Sevkiyatlar
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentShipments as $shipment)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5 hover:border-blue-500/30 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center">
                                        <i class="fa-solid fa-box text-blue-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">#{{ $shipment->tracking_number }}</p>
                                        <p class="text-xs text-gray-500">{{ $shipment->contact->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @php
                                        $shipmentStatusClasses = [
                                            'pending' => 'bg-amber-500/10 text-amber-500',
                                            'in_transit' => 'bg-blue-500/10 text-blue-500',
                                            'delivered' => 'bg-emerald-500/10 text-emerald-500',
                                            'cancelled' => 'bg-rose-500/10 text-rose-500',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $shipmentStatusClasses[$shipment->status] ?? 'bg-gray-500/10 text-gray-500' }}">
                                        {{ $shipment->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400 italic">Henüz sevkiyat bulunmuyor.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Active Routes -->
            <div class="bg-white dark:bg-[#0f172a]/20 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl shadow-glass overflow-hidden h-full">
                <div class="p-6 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-cyan-500"></i>
                        Günlük Rotalar
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentRoutes as $route)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5 hover:border-cyan-500/30 transition-all">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-cyan-500/10 rounded-xl flex items-center justify-center">
                                        <i class="fa-solid fa-route text-cyan-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $route->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $route->vehicle->plate_number ?? 'Araçsız' }}</p>
                                    </div>
                                </div>
                                <div class="text-right text-xs text-gray-400">
                                    {{ $route->planned_date->format('d.m.Y') }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400 italic">Planlı rota bulunmuyor.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
