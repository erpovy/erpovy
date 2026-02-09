<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-white tracking-tight">
                {{ __('Üretim Alanı (Shopfloor)') }}
            </h2>
            <div class="text-slate-400 text-sm font-medium">
                {{ now()->translatedFormat('d F Y, l') }}
            </div>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ openModal: false }">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-12 space-y-8">
            
            <!-- Live Status Monitor -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
                <!-- Total Stations -->
                <x-card class="p-8 relative overflow-hidden group border-white/10 bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-blue-500/30 hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-blue-500/10 text-blue-500 ring-1 ring-blue-500/20">
                                <span class="material-symbols-outlined text-[24px]">precision_manufacturing</span>
                            </div>
                            <div class="text-slate-400 text-xs font-black uppercase tracking-widest">Toplam İstasyon</div>
                        </div>
                        <div class="text-5xl font-black text-white tracking-tight mb-2">{{ $stats['total'] }}</div>
                        <div class="text-blue-400 text-sm mt-4 flex items-center font-bold bg-blue-500/10 w-fit px-3 py-1.5 rounded-lg border border-blue-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">factory</span>
                            Tüm Makineler
                        </div>
                    </div>
                </x-card>

                <!-- Active -->
                <x-card class="p-8 relative overflow-hidden group border-white/10 bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-green-500/30 hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-green-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-green-500/10 text-green-500 ring-1 ring-green-500/20">
                                <span class="material-symbols-outlined text-[24px]">settings_power</span>
                            </div>
                            <div class="text-slate-400 text-xs font-black uppercase tracking-widest">Aktif / Çalışıyor</div>
                        </div>
                        <div class="text-5xl font-black text-white tracking-tight mb-2">{{ $stats['active'] }}</div>
                        <div class="text-green-400 text-sm mt-4 flex items-center font-bold bg-green-500/10 w-fit px-3 py-1.5 rounded-lg border border-green-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">bolt</span>
                            Üretimde
                        </div>
                    </div>
                </x-card>

                <!-- Maintenance -->
                <x-card class="p-8 relative overflow-hidden group border-white/10 bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-yellow-500/30 hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-yellow-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-yellow-500/10 text-yellow-500 ring-1 ring-yellow-500/20">
                                <span class="material-symbols-outlined text-[24px]">build</span>
                            </div>
                            <div class="text-slate-400 text-xs font-black uppercase tracking-widest">Bakımda</div>
                        </div>
                        <div class="text-5xl font-black text-white tracking-tight mb-2">{{ $stats['maintenance'] }}</div>
                        <div class="text-yellow-400 text-sm mt-4 flex items-center font-bold bg-yellow-500/10 w-fit px-3 py-1.5 rounded-lg border border-yellow-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">handyman</span>
                            Servis Dışı
                        </div>
                    </div>
                </x-card>

                <!-- Offline -->
                <x-card class="p-8 relative overflow-hidden group border-white/10 bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-red-500/30 hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-red-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-red-500/10 text-red-500 ring-1 ring-red-500/20">
                                <span class="material-symbols-outlined text-[24px]">power_off</span>
                            </div>
                            <div class="text-slate-400 text-xs font-black uppercase tracking-widest">Kapalı / Arızalı</div>
                        </div>
                        <div class="text-5xl font-black text-white tracking-tight mb-2">{{ $stats['offline'] }}</div>
                        <div class="text-red-400 text-sm mt-4 flex items-center font-bold bg-red-500/10 w-fit px-3 py-1.5 rounded-lg border border-red-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">do_not_disturb_on</span>
                            Kullanılamaz
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Header for Grid -->
             <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-white flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-white/10 text-white">
                        <span class="material-symbols-outlined text-[24px]">grid_view</span>
                    </div>
                    İş İstasyonları
                </h3>
                <button @click="openModal = true" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Yeni İstasyon
                </button>
            </div>

            <!-- Station Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($stations as $station)
                     <x-card class="p-8 relative overflow-hidden group border-white/10 bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:bg-white/10 hover:-translate-y-2 hover:shadow-2xl">
                         <!-- Status Indicator -->
                         <div class="absolute top-0 right-0 p-6">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-500 shadow-[0_0_15px_rgba(34,197,94,0.6)]',
                                    'maintenance' => 'bg-yellow-500 shadow-[0_0_15px_rgba(234,179,8,0.6)]',
                                    'offline' => 'bg-red-500 shadow-[0_0_15px_rgba(239,68,68,0.6)]',
                                ];
                            @endphp
                            <div class="w-4 h-4 rounded-full {{ $statusColors[$station->status] ?? 'bg-gray-500' }} animate-pulse"></div>
                        </div>

                        <div class="flex flex-col h-full justify-between relative z-10">
                            <div>
                                <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform duration-500 border border-white/5 group-hover:border-white/20">
                                    @if($station->type == 'machine')
                                        <span class="material-symbols-outlined text-4xl">smart_toy</span>
                                    @elseif($station->type == 'assembly')
                                        <span class="material-symbols-outlined text-4xl">handyman</span>
                                    @elseif($station->type == 'packaging')
                                        <span class="material-symbols-outlined text-4xl">inventory_2</span>
                                    @else
                                        <span class="material-symbols-outlined text-4xl">precision_manufacturing</span>
                                    @endif
                                </div>
                                
                                <h4 class="text-2xl font-black text-white mb-1 group-hover:text-primary transition-colors">{{ $station->name }}</h4>
                                <div class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-6 border-b border-white/5 pb-6">{{ $station->code }}</div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-400 font-medium">Konum</span>
                                    <span class="text-white font-bold">{{ $station->location ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-400 font-medium">Kapasite</span>
                                    <span class="text-white font-bold">{{ number_format($station->capacity, 0) }} / gün</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-400 font-medium">Saatlik Maliyet</span>
                                    <div class="px-2 py-1 rounded bg-white/5 border border-white/5 text-xs font-mono text-white">
                                        {{ number_format($station->hourly_rate, 2) }} ₺
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-card>
                @empty
                    <div class="col-span-full text-center py-20 bg-white/5 rounded-[2.5rem] border border-white/10">
                        <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="material-symbols-outlined text-5xl text-slate-600">domain_disabled</span>
                        </div>
                        <p class="text-slate-400 font-medium text-xl">Henüz iş istasyonu tanımlanmamış.</p>
                        <button @click="openModal = true" class="text-primary hover:text-primary/80 font-bold mt-4 transition-colors">İlk istasyonu ekle</button>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-8">
                {{ $stations->links() }}
            </div>
        </div>

        <!-- Create Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openModal = false"></div>
            
            <div class="relative bg-[#1e1e2d] rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-white/10 transform transition-all"
                 x-show="openModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="px-8 py-6 border-b border-white/10 flex justify-between items-center bg-white/[0.02]">
                    <h3 class="text-xl font-black text-white">Yeni İş İstasyonu Tanımla</h3>
                    <button @click="openModal = false" class="text-slate-500 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form action="{{ route('manufacturing.shopfloor.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Code -->
                        <div>
                             <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">İstasyon Kodu</label>
                            <input type="text" name="code" class="w-full rounded-xl border-white/10 bg-black/20 text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" placeholder="Örn: CNC-01" required>
                        </div>
                        
                        <!-- Name -->
                        <div>
                             <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">İstasyon Adı</label>
                            <input type="text" name="name" class="w-full rounded-xl border-white/10 bg-black/20 text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" placeholder="Örn: CNC Kesim 1" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Type -->
                        <div>
                             <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tip</label>
                            <select name="type" class="w-full rounded-xl border-white/10 bg-black/20 text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                                <option value="machine" class="bg-[#1e1e2d]">Makine</option>
                                <option value="assembly" class="bg-[#1e1e2d]">Montaj Hattı</option>
                                <option value="packaging" class="bg-[#1e1e2d]">Paketleme</option>
                                <option value="other" class="bg-[#1e1e2d]">Diğer</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                             <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Durum</label>
                            <select name="status" class="w-full rounded-xl border-white/10 bg-black/20 text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                                <option value="active" class="bg-[#1e1e2d]">Aktif (Çalışıyor)</option>
                                <option value="maintenance" class="bg-[#1e1e2d]">Bakımda</option>
                                <option value="offline" class="bg-[#1e1e2d]">Kapalı / Arızalı</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Capacity -->
                        <div>
                             <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Günlük Kapasite</label>
                            <input type="number" name="capacity" min="0" class="w-full rounded-xl border-white/10 bg-black/20 text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                        </div>

                        <!-- Hourly Rate -->
                        <div>
                             <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Saatlik Maliyet (₺)</label>
                            <input type="number" name="hourly_rate" min="0" step="0.01" class="w-full rounded-xl border-white/10 bg-black/20 text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4">
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                         <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Konum</label>
                        <input type="text" name="location" class="w-full rounded-xl border-white/10 bg-black/20 text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" placeholder="Örn: Blok A, Zemin Kat">
                    </div>

                    <div class="pt-6 flex justify-end gap-4 border-t border-white/10">
                        <button type="button" @click="openModal = false" class="px-6 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 font-bold transition-colors">İptal</button>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-1">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
