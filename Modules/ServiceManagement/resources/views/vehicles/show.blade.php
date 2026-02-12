<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/5 via-orange-500/5 to-yellow-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div class="flex items-center gap-4">
                    <a href="{{ route('servicemanagement.vehicles.index') }}" class="p-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 hover:bg-amber-500 hover:text-white transition-all">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                            {{ $vehicle->plate_number }}
                        </h2>
                        <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2 uppercase tracking-widest">
                            {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('servicemanagement.vehicles.edit', $vehicle->id) }}" class="group flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/20 font-black text-xs uppercase tracking-widest transition-all hover:bg-amber-500 hover:text-white">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        DÜZENLE
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ showServiceForm: false }">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Vehicle Info & Quick Actions -->
                <div class="space-y-8">
                    <!-- Vehicle Card -->
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-[10px] font-black uppercase text-amber-500 tracking-[0.2em]">ARAÇ BİLGİLERİ</h3>
                            <div class="flex flex-col items-end gap-1 text-right">
                                @if($vehicle->status == 'active')
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-right">AKTİF</span>
                                @elseif($vehicle->status == 'maintenance')
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded bg-blue-500/10 text-blue-500 border border-blue-500/20 text-right">SERVİSTE</span>
                                @else
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded bg-rose-500/10 text-rose-500 border border-rose-500/20 text-right">PASİF</span>
                                @endif
                                
                                @php $health = $vehicle->maintenance_status; @endphp
                                @if($health == 'overdue')
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded bg-rose-500/20 text-rose-600 animate-pulse text-right">KRİTİK BAKIM</span>
                                @elseif($health == 'upcoming')
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded bg-amber-500/20 text-amber-600 text-right">BAKIM YAKLAŞTI</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-white/5 text-left">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest text-left">GÜNCEL KM</span>
                                <span class="text-sm font-black text-gray-900 dark:text-white font-mono text-left">{{ number_format($vehicle->current_mileage, 0, ',', '.') }} km</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-white/5 text-left">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest text-left">ŞASİ NO</span>
                                <span class="text-xs font-bold text-gray-900 dark:text-white uppercase text-left">{{ $vehicle->vin ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-white/5 text-left">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest text-left text-left">BAKIM DURUMU</span>
                                <span class="text-xs font-black uppercase tracking-tight {{ $health == 'overdue' ? 'text-rose-500' : ($health == 'upcoming' ? 'text-amber-500' : 'text-emerald-500') }} text-left">
                                    {{ $health == 'overdue' ? 'GECİKMİŞ' : ($health == 'upcoming' ? 'PLANLAMADA' : 'SAĞLIKLI') }}
                                </span>
                            </div>
                        </div>

                        <button @click="showServiceForm = !showServiceForm" class="w-full mt-8 px-6 py-4 rounded-xl bg-amber-500 text-white font-black text-xs uppercase tracking-widest transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add_task</span>
                            YENİ SERVİS KAYDI
                        </button>
                    </x-card>

                    <!-- TCO & Analytics Cards -->
                    <div class="grid grid-cols-1 gap-4">
                        <x-card class="p-5 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-xl relative overflow-hidden group">
                            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                <span class="material-symbols-outlined text-[80px] text-emerald-500 text-left">payments</span>
                            </div>
                            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 text-left text-left">TOPLAM SERVİS MALİYETİ</div>
                            <div class="text-2xl font-black text-gray-900 dark:text-white text-left">₺{{ number_format($totalCost, 2, ',', '.') }}</div>
                            <div class="text-[9px] font-bold text-emerald-500 uppercase mt-1 text-left">TÜM ZAMANLAR</div>
                        </x-card>

                        <x-card class="p-5 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-xl relative overflow-hidden group text-left">
                            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-left">
                                <span class="material-symbols-outlined text-[80px] text-blue-500 text-left">speed</span>
                            </div>
                            <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 text-left text-left">KM BAŞINA MALİYET</div>
                            <div class="text-2xl font-black text-gray-900 dark:text-white text-left">₺{{ number_format($costPerKm, 2, ',', '.') }}</div>
                            <div class="text-[9px] font-bold text-blue-500 uppercase mt-1 text-left text-left">VERİMLİLİK SKORU</div>
                        </x-card>
                    </div>

                    <!-- Maintenance Planning Card -->
                    @php
                        $nextService = $vehicle->serviceRecords()->whereNotNull('next_planned_date')->where('next_planned_date', '>=', now())->orderBy('next_planned_date')->first();
                    @endphp
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-gradient-to-br from-blue-500/10 to-transparent backdrop-blur-2xl text-left">
                        <h3 class="text-[10px] font-black uppercase text-blue-500 tracking-[0.2em] mb-4 text-left">PLANLANAN BAKIM</h3>
                        @if($nextService)
                            <div class="flex items-center gap-4 text-left">
                                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-500 text-left">
                                    <span class="material-symbols-outlined text-[24px]">event_repeat</span>
                                </div>
                                <div class="flex-1 text-left">
                                    <div class="text-sm font-black text-gray-900 dark:text-white uppercase text-left">{{ $nextService->service_type }}</div>
                                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest text-left">Tarih: {{ $nextService->next_planned_date->format('d.m.Y') }}</div>
                                    @if($nextService->next_planned_mileage)
                                        <div class="text-[10px] font-bold text-blue-500 uppercase tracking-widest text-left">KM: {{ number_format($nextService->next_planned_mileage, 0, ',', '.') }}</div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="py-4 text-center text-left">
                                <span class="material-symbols-outlined text-slate-500 text-[32px] mb-2 opacity-20 text-left">history_toggle_off</span>
                                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest opacity-50 text-left">Henüz bir planlama yok.</p>
                            </div>
                        @endif
                    </x-card>
                </div>

                <!-- Right Column: Service Record Form & History -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Add Service Record Form (Hidden by default) -->
                    <div x-show="showServiceForm" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <x-card class="p-8 border-amber-500/50 dark:border-amber-500/30 bg-white/10 dark:bg-amber-500/5 backdrop-blur-3xl ring-1 ring-amber-500/20 shadow-2xl shadow-amber-500/10">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter">Servis Kaydı Oluştur</h3>
                                <button @click="showServiceForm = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                                    <span class="material-symbols-outlined">close</span>
                                </button>
                            </div>

                            <form action="{{ route('servicemanagement.service-records.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">İŞLEM TÜRÜ</label>
                                        <input type="text" name="service_type" required class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white" placeholder="Periyodik Bakım, Lastik Değişimi vb.">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">İŞLEM TARİHİ</label>
                                        <input type="date" name="service_date" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">İŞLEMDEKİ KM</label>
                                        <input type="number" name="mileage_at_service" required value="{{ $vehicle->current_mileage }}" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-mono focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">TOPLAM TUTAR (₺)</label>
                                        <input type="number" step="0.01" name="total_cost" required value="0" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-black focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                                    </div>
                                </div>

                                <div class="space-y-2 mb-6">
                                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">AÇIKLAMA</label>
                                    <textarea name="description" rows="3" required class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white" placeholder="Yapılan işlemlerin detayları..."></textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-t border-gray-100 dark:border-white/5 pt-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">BİR SONRAKİ BAKIM TARİHİ</label>
                                        <input type="date" name="next_planned_date" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">BİR SONRAKİ BAKIM KM</label>
                                        <input type="number" name="next_planned_mileage" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-mono focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest px-4 py-2 bg-white/5 rounded-lg border border-white/10">DURUM</label>
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="status" value="completed" id="st_comp" checked class="text-amber-500 focus:ring-amber-500 bg-white/5 border-white/10">
                                            <label for="st_comp" class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase">Tamamlandı</label>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="status" value="in_progress" id="st_prog" class="text-amber-500 focus:ring-amber-500 bg-white/5 border-white/10">
                                            <label for="st_prog" class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase">Devam Ediyor</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="px-12 py-4 rounded-xl bg-amber-500 text-white font-black text-xs uppercase tracking-widest transition-all hover:scale-[1.05] active:scale-[0.95] shadow-lg shadow-amber-500/20">
                                        KAYDI KAYDET
                                    </button>
                                </div>
                            </form>
                        </x-card>
                    </div>

                    <!-- Service History Table -->
                    <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden text-left">
                        <div class="p-6 border-b border-gray-200 dark:border-white/5 flex items-center justify-between text-left">
                            <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em] text-left">Servis Geçmişi</h3>
                            <span class="material-symbols-outlined text-slate-400 text-[20px] text-left">history</span>
                        </div>
                        <div class="overflow-x-auto text-left">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-200 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest text-left">
                                    <tr>
                                        <th class="px-6 py-4 text-left">Tarih / KM</th>
                                        <th class="px-6 py-4 text-left">İşlem Detayı</th>
                                        <th class="px-6 py-4 text-right">Maliyet</th>
                                        <th class="px-6 py-4 text-left">Durum</th>
                                        <th class="px-6 py-4 text-right">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/5 text-left">
                                    @forelse($vehicle->serviceRecords as $record)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group text-left">
                                            <td class="px-6 py-4 text-left">
                                                <div class="flex flex-col text-left">
                                                    <span class="text-sm font-black text-gray-900 dark:text-white text-left">{{ $record->service_date->format('d.m.Y') }}</span>
                                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest text-left">{{ number_format($record->mileage_at_service, 0, ',', '.') }} km</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-left">
                                                <div class="flex flex-col max-w-md text-left">
                                                    <span class="text-sm font-black text-gray-800 dark:text-zinc-50 uppercase truncate text-left">{{ $record->service_type }}</span>
                                                    <span class="text-[10px] font-medium text-slate-500 line-clamp-1 text-left">{{ $record->description }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-black text-gray-900 dark:text-white font-mono uppercase">
                                                ₺{{ number_format($record->total_cost, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-left">
                                                @if($record->status == 'completed')
                                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 text-left">TAMAMLANDI</span>
                                                @elseif($record->status == 'in_progress')
                                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-blue-500/10 text-blue-500 border border-blue-500/20 animate-pulse text-left">DEVAM EDİYOR</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-amber-500/10 text-amber-500 border border-amber-500/20 text-left">BEKLEMEDE</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity text-right">
                                                    <form action="{{ route('servicemanagement.service-records.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Bu kaydı silmek istediğinize emin misiniz?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-bold uppercase tracking-widest opacity-30 text-xs text-left">Bu araç için servis kaydı bulunmuyor.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>

                    <!-- Cost History Chart for Vehicle -->
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl text-left">
                        <div class="flex items-center justify-between mb-6 text-left">
                            <div class="text-left">
                                <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em] text-left">Maliyet Analizi</h3>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest text-left">Son 6 Aylık Servis Giderleri</p>
                            </div>
                            <div class="p-2 rounded-lg bg-blue-500/10 text-blue-500 text-left">
                                <span class="material-symbols-outlined text-[20px]">area_chart</span>
                            </div>
                        </div>
                        <div id="vehicleCostChart" class="h-[300px] text-left"></div>
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
                    name: 'Maliyet',
                    data: costData.map(item => item.total)
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: { show: false },
                    background: 'transparent',
                    foreColor: '#64748b'
                },
                colors: ['#3b82f6'],
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        columnWidth: '40%',
                    }
                },
                dataLabels: { enabled: false },
                grid: {
                    borderColor: '#1e293b',
                    strokeDashArray: 4,
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

            const chart = new ApexCharts(document.querySelector("#vehicleCostChart"), options);
            chart.render();
        });
    </script>
    @endpush
</x-app-layout>
