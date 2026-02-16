<x-app-layout>
    <div class="py-12" x-data="{ 
        stops: {{ json_encode($route->stops ?? [['location' => '', 'estimated_arrival' => '']]) }},
        addStop() {
            this.stops.push({ location: '', estimated_arrival: '' });
        },
        removeStop(index) {
            this.stops.splice(index, 1);
            if (this.stops.length === 0) this.addStop();
        }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Rotayı Düzenle</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 font-medium">Rota detaylarını ve durak planını güncelleyin.</p>
                </div>
                <a href="{{ route('logistics.routes.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition-colors flex items-center font-bold text-sm">
                    <span class="material-symbols-outlined mr-1">arrow_back</span>
                    VAZGEÇ
                </a>
            </div>

            <form action="{{ route('logistics.routes.update', $route) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- General Info -->
                <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Rota Adı *</label>
                            <input type="text" name="name" required value="{{ old('name', $route->name) }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all"
                                placeholder="Örn: Ankara - İstanbul Ekspres Hattı">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Araç Ataması</label>
                            <select name="vehicle_id" 
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                                <option value="">Araç Seçiniz (Opsiyonel)</option>
                                @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ $route->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->plate_number }} ({{ $vehicle->type }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Planlanan Tarih *</label>
                            <input type="date" name="planned_date" required value="{{ old('planned_date', $route->planned_date->format('Y-m-d')) }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Rota Durumu *</label>
                            <select name="status" required
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                                <option value="draft" {{ $route->status == 'draft' ? 'selected' : '' }}>Taslak</option>
                                <option value="optimized" {{ $route->status == 'optimized' ? 'selected' : '' }}>Optimize Edildi</option>
                                <option value="in_progress" {{ $route->status == 'in_progress' ? 'selected' : '' }}>Yolda</option>
                                <option value="completed" {{ $route->status == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                            </select>
                        </div>

                        <!-- Shipment Selection -->
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center justify-between">
                                Bu Rotaya Bağlı Sevkiyatlar
                                <span class="text-[10px] text-indigo-500 normal-case font-medium">Bu rotadakiler ve boşta olanlar listelenir.</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-48 overflow-y-auto p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10">
                                @forelse($availableShipments as $shipment)
                                <label class="flex items-center gap-3 p-3 bg-white dark:bg-white/5 rounded-xl border border-gray-200 dark:border-white/10 cursor-pointer hover:border-indigo-500/50 transition-all group {{ $shipment->route_id == $route->id ? 'ring-2 ring-indigo-500/20 border-indigo-500/30' : '' }}">
                                    <input type="checkbox" name="shipment_ids[]" value="{{ $shipment->id }}" 
                                        {{ $shipment->route_id == $route->id ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-gray-900 dark:text-white group-hover:text-indigo-500 transition-colors">{{ $shipment->tracking_number }}</span>
                                        <span class="text-[10px] text-gray-500">{{ $shipment->destination }}</span>
                                    </div>
                                </label>
                                @empty
                                <div class="md:col-span-2 py-4 text-center">
                                    <p class="text-xs text-gray-400 italic">Atanabilir sevkiyat bulunamadı.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Toplam Mesafe (KM)</label>
                            <input type="number" step="0.01" name="total_distance" value="{{ old('total_distance', $route->total_distance) }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all"
                                placeholder="Örn: 450.50">
                        </div>
                    </div>
                </div>

                <!-- Stops Section -->
                <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                            <span class="material-symbols-outlined mr-2 text-indigo-500">place</span>
                            Durak Planı
                        </h3>
                        <button type="button" @click="addStop()" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-xs font-black rounded-xl transition-all uppercase tracking-widest">
                            <span class="material-symbols-outlined text-sm mr-1">add_location</span>
                            DURAK EKLE
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(stop, index) in stops" :key="index">
                            <div class="flex flex-col md:flex-row gap-4 items-end bg-gray-50/50 dark:bg-white/2 p-4 rounded-2xl relative group animate-fade-in">
                                <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-gray-800 border-2 border-indigo-500 rounded-full flex items-center justify-center text-[10px] font-black text-indigo-500 z-10" x-text="index + 1"></div>
                                
                                <div class="flex-1 space-y-2 w-full">
                                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-400">Konum / Adres</label>
                                    <input type="text" :name="`stops[${index}][location]`" x-model="stop.location" required
                                        class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/30 transition-all"
                                        placeholder="Örn: İstanbul Yenibosna Depo">
                                </div>

                                <div class="w-full md:w-48 space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-wider text-gray-400">Tahmini Varış</label>
                                    <input type="time" :name="`stops[${index}][estimated_arrival]`" x-model="stop.estimated_arrival"
                                        class="w-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm text-gray-900 dark:text-white outline-none focus:ring-2 focus:ring-indigo-500/30 transition-all">
                                </div>

                                <button type="button" @click="removeStop(index)" 
                                    class="p-2.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 rounded-xl transition-colors md:mb-0 mb-2">
                                    <span class="material-symbols-outlined text-sm">remove_circle_outline</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-4 p-4">
                    <button type="submit" 
                        class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl transition-all shadow-lg shadow-indigo-500/30 uppercase tracking-widest text-sm">
                        DEĞİŞİKLİKLERİ KAYDET
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>
