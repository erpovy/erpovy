<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/5 via-orange-500/5 to-yellow-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Araç Listesi
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">directions_car</span>
                        Kayıtlı tüm araçların yönetimi ve durumu
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('servicemanagement.vehicles.create') }}" class="group flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 text-white font-black text-xs uppercase tracking-widest transition-all hover:scale-[1.05] active:scale-[0.95] shadow-lg shadow-amber-500/20">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        YENİ ARAÇ EKLE
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <!-- Search and Filter -->
            <x-card class="mb-8 p-4 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('servicemanagement.vehicles.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Plaka, marka veya model ara..." 
                            class="w-full pl-11 pr-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                    </div>
                    
                    <div class="w-full md:w-48">
                        <select name="status" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                            <option value="">Tüm Durumlar</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Serviste</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                        </select>
                    </div>

                    <button type="submit" class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-xs uppercase tracking-widest rounded-xl transition-all hover:bg-slate-800 dark:hover:bg-slate-100">
                        FİLTRELE
                    </button>
                    
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('servicemanagement.vehicles.index') }}" class="px-6 py-3 bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-black text-xs uppercase tracking-widest rounded-xl transition-all hover:bg-gray-200 dark:hover:bg-white/10 flex items-center justify-center">
                            SIFIRLA
                        </a>
                    @endif
                </form>
            </x-card>

            <!-- Vehicle Table -->
            <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-200 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Araç Bilgileri</th>
                                <th class="px-6 py-4">Son Bakım</th>
                                <th class="px-6 py-4">Kilometre</th>
                                <th class="px-6 py-4">Sağlık Durumu</th>
                                <th class="px-6 py-4">Durum</th>
                                <th class="px-6 py-4 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($vehicles as $vehicle)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
                                                <span class="material-symbols-outlined text-[24px]">directions_car</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $vehicle->plate_number }}</span>
                                                <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</span>
                                                <div class="text-[10px] font-bold text-amber-500 uppercase mt-1 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[12px]">person</span>
                                                    {{ $vehicle->customer ? $vehicle->customer->name : 'ŞİRKET ARACI' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-700 dark:text-slate-300">
                                                {{ $vehicle->serviceRecords->first()?->service_date->format('d.m.Y') ?? '-' }}
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-500 uppercase">
                                                {{ $vehicle->serviceRecords->first()?->service_type ?? 'Kayıt Yok' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 text-sm font-black text-gray-900 dark:text-white font-mono">
                                            {{ number_format($vehicle->current_mileage, 0, ',', '.') }}
                                            <span class="text-[10px] font-bold text-slate-500 uppercase">km</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $health = $vehicle->maintenance_status; @endphp
                                        @if($health == 'overdue')
                                            <div class="flex items-center gap-2 text-rose-500">
                                                <span class="material-symbols-outlined text-[18px] animate-pulse">report</span>
                                                <span class="text-[10px] font-black uppercase tracking-tight">KRİTİK / GECİKMİŞ</span>
                                            </div>
                                        @elseif($health == 'upcoming')
                                            <div class="flex items-center gap-2 text-amber-500">
                                                <span class="material-symbols-outlined text-[18px]">event_upcoming</span>
                                                <span class="text-[10px] font-black uppercase tracking-tight">BAKIM YAKLAŞTI</span>
                                            </div>
                                        @elseif($health == 'healthy')
                                            <div class="flex items-center gap-2 text-emerald-500">
                                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                                <span class="text-[10px] font-black uppercase tracking-tight">SAĞLIKLI</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 text-slate-500">
                                                <span class="material-symbols-outlined text-[18px]">help_center</span>
                                                <span class="text-[10px] font-black uppercase tracking-tight">BELİRSİZ</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($vehicle->status == 'active')
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tight rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">AKTİF</span>
                                        @elseif($vehicle->status == 'maintenance')
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tight rounded-full bg-blue-500/10 text-blue-500 border border-blue-500/20 animate-pulse">SERVİSTE</span>
                                        @else
                                            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tight rounded-full bg-rose-500/10 text-rose-500 border border-rose-500/20">PASİF</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('servicemanagement.vehicles.show', $vehicle->id) }}" class="p-2 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                            </a>
                                            <a href="{{ route('servicemanagement.vehicles.edit', $vehicle->id) }}" class="p-2 rounded-lg bg-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-white transition-all">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                            </a>
                                            <form action="{{ route('servicemanagement.vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Bu aracı silmek istediğinize emin misiniz?')">
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
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-bold uppercase tracking-widest opacity-30 text-xs">
                                        <span class="material-symbols-outlined text-[48px] mb-4 block">no_crash</span>
                                        Henüz araç kaydı bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($vehicles->hasPages())
                    <div class="p-6 border-t border-gray-200 dark:border-white/5">
                        {{ $vehicles->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
