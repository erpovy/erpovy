<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/5 via-orange-500/5 to-yellow-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Servis Geçmişi
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">history</span>
                        Tüm araçların servis ve bakım dökümleri
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <!-- Search -->
            <x-card class="mb-8 p-4 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('servicemanagement.service-records.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Plaka veya işlem türü ara..." 
                            class="w-full pl-11 pr-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                    </div>

                    <button type="submit" class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-xs uppercase tracking-widest rounded-xl transition-all hover:bg-slate-800 dark:hover:bg-slate-100">
                        SORGULA
                    </button>
                    
                    @if(request('search'))
                        <a href="{{ route('servicemanagement.service-records.index') }}" class="px-6 py-3 bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-black text-xs uppercase tracking-widest rounded-xl transition-all hover:bg-gray-200 dark:hover:bg-white/10 flex items-center justify-center">
                            SIFIRLA
                        </a>
                    @endif
                </form>
            </x-card>

            <!-- Records Table -->
            <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-200 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Araç</th>
                                <th class="px-6 py-4">Tarih / KM</th>
                                <th class="px-6 py-4">İşlem</th>
                                <th class="px-6 py-4 text-right">Maliyet</th>
                                <th class="px-6 py-4">Durum</th>
                                <th class="px-6 py-4 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($records as $record)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $record->vehicle->plate_number }}</span>
                                            <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $record->vehicle->brand }} {{ $record->vehicle->model }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-gray-900 dark:text-white">{{ $record->service_date->format('d.m.Y') }}</span>
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ number_format($record->mileage_at_service, 0, ',', '.') }} km</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col max-w-md">
                                            <span class="text-sm font-black text-gray-800 dark:text-zinc-50 uppercase truncate">{{ $record->service_type }}</span>
                                            <span class="text-[10px] font-medium text-slate-500 line-clamp-1">{{ $record->description }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-black text-gray-900 dark:text-white font-mono uppercase">
                                        ₺{{ number_format($record->total_cost, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($record->status == 'completed')
                                            <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">TAMAMLANDI</span>
                                        @elseif($record->status == 'in_progress')
                                            <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-blue-500/10 text-blue-500 border border-blue-500/20 animate-pulse">DEVAM EDİYOR</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-amber-500/10 text-amber-500 border border-amber-500/20">BEKLEMEDE</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('servicemanagement.vehicles.show', $record->vehicle_id) }}" class="p-2 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-bold uppercase tracking-widest opacity-30 text-xs">Kayıtlı servis işlemi bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($records->hasPages())
                    <div class="p-6 border-t border-gray-200 dark:border-white/5">
                        {{ $records->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
