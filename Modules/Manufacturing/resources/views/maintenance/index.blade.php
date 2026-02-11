<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight">
                {{ __('Bakım Yönetimi (Maintenance)') }}
            </h2>
            <div class="text-gray-600 dark:text-slate-400 text-sm font-medium">
                {{ now()->translatedFormat('d F Y, l') }}
            </div>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ openModal: false, completeModal: false, selectedRecord: null }">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-12 space-y-8">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
                <!-- Planned -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-blue-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-blue-500/10 text-blue-500 ring-1 ring-blue-500/20">
                                <span class="material-symbols-outlined text-[24px]">event_upcoming</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Planlı Bakım</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $stats['planned'] }}</div>
                        <div class="text-blue-400 text-sm mt-4 flex items-center font-bold bg-blue-500/10 w-fit px-3 py-1.5 rounded-lg border border-blue-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">schedule</span>
                            Gelecek Program
                        </div>
                    </div>
                </x-card>

                <!-- In Process -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-yellow-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-yellow-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-yellow-500/10 text-yellow-500 ring-1 ring-yellow-500/20">
                                <span class="material-symbols-outlined text-[24px]">handyman</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Devam Eden</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $stats['in_process'] }}</div>
                        <div class="text-yellow-400 text-sm mt-4 flex items-center font-bold bg-yellow-500/10 w-fit px-3 py-1.5 rounded-lg border border-yellow-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">engineering</span>
                            Şu An Yapılıyor
                        </div>
                    </div>
                </x-card>

                <!-- Completed -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-green-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-green-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-green-500/10 text-green-500 ring-1 ring-green-500/20">
                                <span class="material-symbols-outlined text-[24px]">fact_check</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Tamamlanan</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $stats['completed'] }}</div>
                        <div class="text-green-400 text-sm mt-4 flex items-center font-bold bg-green-500/10 w-fit px-3 py-1.5 rounded-lg border border-green-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">done_all</span>
                            Başarılı İşlemler
                        </div>
                    </div>
                </x-card>

                <!-- Cost -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-purple-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-purple-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-purple-500/10 text-purple-500 ring-1 ring-purple-500/20">
                                <span class="material-symbols-outlined text-[24px]">payments</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Toplam Maliyet</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2 flex items-baseline gap-2">
                             {{ number_format($stats['total_cost'], 2) }} <span class="text-2xl text-gray-500 dark:text-slate-500">₺</span>
                        </div>
                        <div class="text-purple-400 text-sm mt-4 flex items-center font-bold bg-purple-500/10 w-fit px-3 py-1.5 rounded-lg border border-purple-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">monetization_on</span>
                            Bakım Giderleri
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- List Section -->
             <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden rounded-[2.5rem] shadow-2xl">
                <div class="p-8 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-gray-50 dark:bg-white/[0.02]">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-[24px]">calendar_month</span>
                        </div>
                        Bakım Takvimi & Kayıtlar
                    </h3>
                    <button @click="openModal = true" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Bakım Planla
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                     <table class="w-full text-left">
                        <thead class="bg-gray-100 dark:bg-white/5 text-xs uppercase text-gray-700 dark:text-slate-400 font-bold tracking-wider">
                            <tr>
                                <th class="px-8 py-6">Durum</th>
                                <th class="px-8 py-6">Başlık / Açıklama</th>
                                <th class="px-8 py-6">İş İstasyonu</th>
                                <th class="px-8 py-6">Tip & Öncelik</th>
                                <th class="px-8 py-6">Tarih</th>
                                <th class="px-8 py-6">Maliyet</th>
                                <th class="px-8 py-6 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($records as $record)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-8 py-6">
                                         @php
                                            $statusClasses = [
                                                'planned' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                                'in_process' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                                'completed' => 'bg-green-500/10 text-green-500 border-green-500/20',
                                            ];
                                            $statusLabels = [
                                                'planned' => 'Planlandı',
                                                'in_process' => 'Sürüyor',
                                                'completed' => 'Tamamlandı',
                                            ];
                                        @endphp
                                        <div class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] uppercase font-black tracking-widest border {{ $statusClasses[$record->status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20' }}">
                                            {{ $statusLabels[$record->status] ?? $record->status }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                         <div class="flex flex-col">
                                            <span class="text-gray-900 dark:text-white font-bold">{{ $record->title }}</span>
                                            <span class="text-xs text-gray-500 dark:text-slate-500 font-bold mt-1 truncate max-w-[200px]">{{ $record->description }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-gray-900 dark:text-white font-bold">{{ $record->workStation->name ?? 'Silinmiş İstasyon' }}</span>
                                            <span class="text-xs text-gray-500 dark:text-slate-500 font-bold">{{ $record->workStation->code ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs font-black uppercase text-gray-600 dark:text-slate-400 tracking-wider">{{ $record->type }}</span>
                                             @php
                                                $priorityColors = [
                                                    'low' => 'text-slate-500',
                                                    'medium' => 'text-yellow-500',
                                                    'high' => 'text-red-500',
                                                ];
                                            @endphp
                                            <span class="text-[10px] font-black uppercase tracking-widest {{ $priorityColors[$record->priority] ?? 'text-slate-500' }}">
                                                {{ strtoupper($record->priority) }} Öncelik
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-gray-900 dark:text-white font-bold">{{ $record->start_date->format('d.m.Y') }}</span>
                                            <span class="text-xs text-gray-500 dark:text-slate-500 font-bold">{{ $record->start_date->format('H:i') }}</span>
                                        </div>
                                        @if($record->end_date)
                                            <span class="text-[10px] text-slate-600 font-bold mt-1 block">Bitiş: {{ $record->end_date->format('d.m.Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-gray-900 dark:text-white font-bold">
                                        {{ number_format($record->cost, 2) }} ₺
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if($record->status != 'completed')
                                            <div class="flex justify-end gap-3">
                                                @if($record->status == 'planned')
                                                    <form action="{{ route('manufacturing.maintenance.update', $record->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="action" value="start">
                                                        <button type="submit" class="p-2 text-yellow-500 bg-yellow-500/10 hover:bg-yellow-500/20 rounded-lg transition-colors border border-yellow-500/20" title="Başlat">
                                                            <span class="material-symbols-outlined text-[20px]">play_arrow</span>
                                                        </button>
                                                    </form>
                                                @endif

                                                <button @click="selectedRecord = {{ $record }}; completeModal = true" class="p-2 text-green-500 bg-green-500/10 hover:bg-green-500/20 rounded-lg transition-colors border border-green-500/20" title="Tamamla">
                                                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                                </button>
                                            </div>
                                        @else
                                            <span class="material-symbols-outlined text-green-500 text-[24px]">done_all</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-8 py-16 text-center">
                                        <div class="w-20 h-20 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="material-symbols-outlined text-4xl text-slate-600">engineering</span>
                                        </div>
                                        <p class="text-gray-500 dark:text-slate-500 font-medium text-lg">Henüz bakım kaydı oluşturulmamış.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 border-t border-gray-200 dark:border-white/5">
                    {{ $records->links() }}
                </div>
            </x-card>
        </div>

        <!-- Create Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openModal = false"></div>
            
            <div class="relative bg-white dark:bg-[#1e1e2d] rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-gray-200 dark:border-white/10 transform transition-all"
                 x-show="openModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="px-8 py-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center bg-gray-50 dark:bg-white/[0.02]">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Yeni Bakım Planla</h3>
                    <button @click="openModal = false" class="text-gray-500 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form action="{{ route('manufacturing.maintenance.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Station -->
                        <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">İş İstasyonu / Makine</label>
                            <select name="work_station_id" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                                <option value="" class="bg-[#1e1e2d]">Seçiniz...</option>
                                @foreach($stations as $station)
                                    <option value="{{ $station->id }}" class="bg-[#1e1e2d]">{{ $station->code }} - {{ $station->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Title -->
                        <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Başlık</label>
                            <input type="text" name="title" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" placeholder="Örn: Haftalık Yağlama ve Kontrol" required>
                        </div>

                        <!-- Type -->
                        <div>
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Bakım Tipi</label>
                            <select name="type" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                                <option value="preventive" class="bg-[#1e1e2d]">Önleyici (Periyodik)</option>
                                <option value="corrective" class="bg-[#1e1e2d]">Düzeltici (Arıza)</option>
                                <option value="emergency" class="bg-[#1e1e2d]">Acil Durum</option>
                            </select>
                        </div>

                        <!-- Priority -->
                         <div>
                              <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Öncelik</label>
                            <select name="priority" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                                <option value="low" class="bg-[#1e1e2d]">Düşük</option>
                                <option value="medium" selected class="bg-[#1e1e2d]">Orta</option>
                                <option value="high" class="bg-[#1e1e2d]">Yüksek</option>
                            </select>
                        </div>

                        <!-- Date -->
                        <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Planlanan Başlangıç</label>
                            <input type="datetime-local" name="start_date" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Açıklama</label>
                            <textarea name="description" rows="3" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4"></textarea>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end gap-4 border-t border-gray-200 dark:border-white/10">
                        <button type="button" @click="openModal = false" class="px-6 py-3 rounded-xl text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 font-bold transition-colors">İptal</button>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-1">
                            Planla
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Complete Modal -->
        <div x-show="completeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="completeModal = false"></div>
            
            <div class="relative bg-white dark:bg-[#1e1e2d] rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-200 dark:border-white/10 transform transition-all"
                 x-show="completeModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="px-8 py-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center bg-gray-50 dark:bg-white/[0.02]">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Bakımı Tamamla</h3>
                    <button @click="completeModal = false" class="text-gray-500 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form x-bind:action="'/manufacturing/maintenance/'+selectedRecord?.id" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="complete">
                    
                    <div class="p-4 bg-primary/10 border border-primary/20 rounded-xl mb-6">
                        <p class="text-sm font-bold text-primary flex items-center gap-2">
                             <span class="material-symbols-outlined text-[18px]">info</span>
                             <span x-text="selectedRecord?.title"></span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                         <!-- Cost -->
                        <div>
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Maliyet (₺)</label>
                            <input type="number" name="cost" min="0" step="0.01" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" placeholder="0.00" required>
                        </div>
                        
                        <!-- Technician -->
                        <div>
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Teknisyen / Yapan Kişi</label>
                            <input type="text" name="technician_name"  class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" value="{{ auth()->user()->name }}">
                        </div>

                         <!-- Result Note -->
                        <div>
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Sonuç Notu</label>
                            <textarea name="description" rows="2" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" placeholder="Yapılan işlemler..."></textarea>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end gap-4 border-t border-gray-200 dark:border-white/10">
                        <button type="button" @click="completeModal = false" class="px-6 py-3 rounded-xl text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 font-bold transition-colors">İptal</button>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-green-600 hover:bg-green-500 text-white font-bold shadow-lg shadow-green-600/30 transition-all transform hover:-translate-y-1">
                            Bakımı Tamamla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
