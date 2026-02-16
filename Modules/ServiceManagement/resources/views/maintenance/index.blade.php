<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    Bakım Takvimi & Planlama
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">event_repeat</span>
                    Kritik ve yaklaşan bakım zamanı gelen araçlar
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6">
            <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-white/5 flex items-center justify-between">
                    <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em]">BAKIM BEKLEYEN ARAÇLAR</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-200 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Araç / Plaka</th>
                                <th class="px-6 py-4">Güncel KM</th>
                                <th class="px-6 py-4">Planlanan KM / Tarih</th>
                                <th class="px-6 py-4">Kalan KM / Gün</th>
                                <th class="px-6 py-4">Durum</th>
                                <th class="px-6 py-4 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($vehicles as $vehicle)
                                @php 
                                    $next = $vehicle->next_service_record;
                                    $health = $vehicle->maintenance_status;
                                @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-gray-900 dark:text-white">{{ $vehicle->plate_number }}</span>
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $vehicle->brand }} {{ $vehicle->model }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-black text-gray-900 dark:text-white font-mono uppercase">{{ number_format($vehicle->current_mileage, 0, ',', '.') }} km</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            @if($next->next_planned_mileage)
                                                <span class="text-sm font-black text-gray-900 dark:text-white font-mono uppercase">{{ number_format($next->next_planned_mileage, 0, ',', '.') }} km</span>
                                            @endif
                                            @if($next->next_planned_date)
                                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $next->next_planned_date->format('d.m.Y') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            @if($next->next_planned_mileage)
                                                @php $diffKm = $next->next_planned_mileage - $vehicle->current_mileage; @endphp
                                                <span class="text-xs font-black {{ $diffKm <= 0 ? 'text-rose-500' : ($diffKm <= 1000 ? 'text-amber-500' : 'text-emerald-500') }}">
                                                    {{ $diffKm <= 0 ? 'AŞILDI (' . number_format(abs($diffKm), 0, ',', '.') . ' km)' : number_format($diffKm, 0, ',', '.') . ' km kaldı' }}
                                                </span>
                                            @endif
                                            @if($next->next_planned_date)
                                                @php $diffDays = now()->diffInDays($next->next_planned_date, false); @endphp
                                                <span class="text-[10px] font-bold {{ $diffDays < 0 ? 'text-rose-500' : ($diffDays <= 15 ? 'text-amber-500' : 'text-slate-500') }}">
                                                    {{ $diffDays < 0 ? 'GECİKTİ (' . abs($diffDays) . ' gün)' : $diffDays . ' gün kaldı' }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($health == 'overdue')
                                            <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-rose-500/10 text-rose-500 border border-rose-500/20 animate-pulse">KRİTİK</span>
                                        @elseif($health == 'upcoming')
                                            <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-amber-500/10 text-amber-500 border border-amber-500/20">YAKLAŞTI</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('servicemanagement.vehicles.show', $vehicle->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-500/10 text-amber-500 border border-amber-500/20 font-black text-[10px] uppercase tracking-widest transition-all hover:bg-amber-500 hover:text-white">
                                            KAYIT AÇ
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-bold uppercase tracking-widest opacity-30 text-xs">Bakım zamanı gelen araç bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
