<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/5 via-orange-500/5 to-yellow-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Servis ve Bakım Özeti
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">minor_crash</span>
                        Araç takibi, periyodik bakımlar ve servis geçmişi
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="#" class="group flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 text-white font-black text-xs uppercase tracking-widest transition-all hover:scale-[1.05] active:scale-[0.95] shadow-lg shadow-amber-500/20">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        YENİ ARAÇ KAYDI
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <!-- Overdue Alerts -->
            @if($overdueVehicles->count() > 0)
                <div class="mb-8 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-700">
                    <div class="w-12 h-12 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-500">
                        <span class="material-symbols-outlined text-[28px] animate-pulse">priority_high</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-black text-rose-500 uppercase tracking-tighter">Kritik Bakım Uyarıları</h4>
                        <p class="text-[11px] text-rose-500/80 font-bold uppercase tracking-widest">
                            {{ $overdueVehicles->count() }} Araç için bakım süresi geçti veya kilometre sınırı aşıldı!
                        </p>
                    </div>
                    <div class="flex -space-x-2">
                        @foreach($overdueVehicles as $overdue)
                            <div class="w-8 h-8 rounded-lg bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-[10px] font-black text-rose-500 backdrop-blur-sm" title="{{ $overdue->plate_number }}">
                                {{ substr($overdue->plate_number, 0, 2) }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Toplam Araç -->
                <x-card class="p-6 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white/10 dark:bg-[#1a2332]/60 backdrop-blur-xl transition-all hover:bg-white/20 dark:hover:bg-[#1a2332]/80">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-30 transition-opacity">
                        <span class="material-symbols-outlined text-[64px] text-amber-500">directions_car</span>
                    </div>
                    <div class="relative z-10 text-left">
                        <div class="text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] mb-2 flex items-center gap-2 text-left">
                            <span class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                            Toplam Araç
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-zinc-50 mb-1 leading-none text-left">{{ $stats['total_vehicles'] }}</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest text-left">Kayıtlı Filo Sayısı</div>
                    </div>
                </x-card>

                <!-- Devam Eden İşlemler -->
                <x-card class="p-6 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white/10 dark:bg-[#1a2332]/60 backdrop-blur-xl transition-all hover:bg-white/20 dark:hover:bg-[#1a2332]/80">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-30 transition-opacity">
                        <span class="material-symbols-outlined text-[64px] text-blue-500">engineering</span>
                    </div>
                    <div class="relative z-10 text-left">
                        <div class="text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] mb-2 flex items-center gap-2 text-left">
                            <span class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"></span>
                            Aktif Servisler
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-zinc-50 mb-1 leading-none text-left">{{ $stats['active_services'] }}</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest text-left">Şu An Serviste Olanlar</div>
                    </div>
                </x-card>

                <!-- Onarım Bekleyen -->
                <x-card class="p-6 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white/10 dark:bg-[#1a2332]/60 backdrop-blur-xl transition-all hover:bg-white/20 dark:hover:bg-[#1a2332]/80">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-30 transition-opacity">
                        <span class="material-symbols-outlined text-[64px] text-rose-500">report_problem</span>
                    </div>
                    <div class="relative z-10 text-left">
                        <div class="text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] mb-2 flex items-center gap-2 text-left">
                            <span class="w-2 h-2 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]"></span>
                            Bekleyen Kayıtlar
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-zinc-50 mb-1 leading-none text-left">{{ $stats['pending_repairs'] }}</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest text-left">Henüz Başlanmamış Kayıtlar</div>
                    </div>
                </x-card>

                <!-- Aylık Maliyet -->
                <x-card class="p-6 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white/10 dark:bg-[#1a2332]/60 backdrop-blur-xl transition-all hover:bg-white/20 dark:hover:bg-[#1a2332]/80">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-30 transition-opacity">
                        <span class="material-symbols-outlined text-[64px] text-emerald-500">payments</span>
                    </div>
                    <div class="relative z-10 text-left">
                        <div class="text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] mb-2 flex items-center gap-2 text-left">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            Aylık Gider
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-zinc-50 mb-1 leading-none text-left">₺{{ number_format($stats['monthly_cost'], 0, ',', '.') }}</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest text-left">Bu Ayki Harcama</div>
                    </div>
                </x-card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Middle: Cost Analysis Chart -->
                <div class="lg:col-span-2 space-y-8">
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <div class="text-left">
                                <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em]">Servis Maliyet Analizi</h3>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Son 6 Aylık Harcama Grafiği</p>
                            </div>
                            <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-500">
                                <span class="material-symbols-outlined text-[20px]">analytics</span>
                            </div>
                        </div>
                        <div id="costHistoryChart" class="h-[300px]"></div>
                    </x-card>

                    <!-- Recent Activity -->
                    <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-white/5 flex items-center justify-between">
                            <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em]">Son Servis İşlemleri</h3>
                            <a href="#" class="text-[10px] font-black text-amber-500 uppercase tracking-widest hover:underline text-left">Tümünü Gör</a>
                        </div>
                        <div class="overflow-x-auto text-left">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-200 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4 text-left">Araç</th>
                                        <th class="px-6 py-4 text-left">İşlem Türü</th>
                                        <th class="px-6 py-4 text-left">Tarih</th>
                                        <th class="px-6 py-4 text-left">Tutar</th>
                                        <th class="px-6 py-4 text-left">Durum</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/5 text-left">
                                    @forelse($recentServices as $service)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors text-left text-sm font-bold">
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col text-left">
                                                    <span class="text-sm font-black text-gray-900 dark:text-white text-left">{{ $service->vehicle->plate_number }}</span>
                                                    <span class="text-[10px] font-bold text-slate-500 uppercase text-left">{{ $service->vehicle->brand }} {{ $service->vehicle->model }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-bold text-gray-700 dark:text-slate-300 text-left">{{ $service->service_type }}</td>
                                            <td class="px-6 py-4 text-xs font-medium text-slate-500 text-left">{{ $service->service_date->format('d.m.Y') }}</td>
                                            <td class="px-6 py-4 text-sm font-black text-gray-900 dark:text-white font-mono text-left">₺{{ number_format($service->total_cost, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-left">
                                                @if($service->status == 'completed')
                                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">TAMAMLANDI</span>
                                                @elseif($service->status == 'in_progress')
                                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-blue-500/10 text-blue-500 border border-blue-500/20 animate-pulse">DEVAM EDİYOR</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-amber-500/10 text-amber-500 border border-amber-500/20">BEKLEMEDE</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-bold uppercase tracking-widest opacity-30 text-xs text-left">Kayıtlı servis işlemi bulunmuyor.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>

                <!-- Right Column: Maintenance Planning -->
                <div class="lg:col-span-1 space-y-8 text-left">
                    <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden text-left">
                        <div class="p-6 border-b border-gray-200 dark:border-white/5 flex items-center justify-between text-left">
                            <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em] text-left">Planlanan Bakımlar</h3>
                            <span class="material-symbols-outlined text-amber-500 text-[20px] text-left">calendar_month</span>
                        </div>
                        <div class="p-4 space-y-4 text-left">
                            @forelse($upcomingMaintenance as $maintenance)
                                <div class="p-4 rounded-xl border border-gray-200 dark:border-white/5 bg-white/5 flex items-center gap-4 group hover:border-amber-500/30 transition-all text-left">
                                    <div class="w-12 h-12 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500 text-left">
                                        <span class="material-symbols-outlined text-[24px]">event_repeat</span>
                                    </div>
                                    <div class="flex-1 min-w-0 text-left">
                                        <div class="text-sm font-black text-gray-900 dark:text-white truncate text-left">{{ $maintenance->vehicle->plate_number }}</div>
                                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest text-left">{{ $maintenance->next_planned_date?->format('d.m.Y') ?? 'Tarih Belirtilmemiş' }}</div>
                                    </div>
                                    <div class="text-[9px] font-black text-amber-500 bg-amber-500/10 px-2 py-1 rounded text-left">
                                        {{ $maintenance->next_planned_date?->diffForHumans() }}
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center text-left">
                                    <span class="material-symbols-outlined text-slate-500 text-[48px] mb-4 opacity-10 text-left">event_available</span>
                                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] text-left">Planlanmış bakım bulunmuyor.</p>
                                </div>
                            @endforelse
                        </div>
                    </x-card>

                    <!-- Quick Actions -->
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-gradient-to-br from-amber-500/10 to-transparent text-left">
                        <h4 class="text-[10px] font-black uppercase text-amber-500 tracking-[0.2em] mb-4 text-left">Hızlı İşlemler</h4>
                        <div class="grid grid-cols-2 gap-3 text-left">
                            <button class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-amber-500/50 transition-all group text-left">
                                <span class="material-symbols-outlined text-amber-500 group-hover:scale-110 transition-transform">add_task</span>
                                <span class="text-[10px] font-bold text-gray-600 dark:text-slate-400 text-left">Servis Aç</span>
                            </button>
                            <button class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-blue-500/50 transition-all group text-left">
                                <span class="material-symbols-outlined text-blue-500 group-hover:scale-110 transition-transform">minor_crash</span>
                                <span class="text-[10px] font-bold text-gray-600 dark:text-slate-400 text-left">Arıza Bildir</span>
                            </button>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const costData = @json($costHistory);
            
            const options = {
                series: [{
                    name: 'Toplam Maliyet',
                    data: costData.map(item => item.total)
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    background: 'transparent',
                    foreColor: '#64748b'
                },
                colors: ['#f59e0b'],
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    colors: ['#f59e0b']
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [20, 100, 100, 100]
                    }
                },
                grid: {
                    borderColor: '#1e293b',
                    strokeDashArray: 4,
                    padding: { left: 0, right: 0 }
                },
                xaxis: {
                    categories: costData.map(item => item.month),
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return "₺" + val.toLocaleString('tr-TR');
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return "₺" + val.toLocaleString('tr-TR');
                        }
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#costHistoryChart"), options);
            chart.render();
        });
    </script>
    @endpush
</x-app-layout>
