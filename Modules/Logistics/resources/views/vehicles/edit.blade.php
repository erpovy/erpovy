<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-blue-500/10 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-4">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Aracı Düzenle
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        {{ $vehicle->plate_number }} - Araç Bilgileri Güncelleme
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logistics.vehicles.index') }}" class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 px-4 py-2 rounded-xl text-sm font-bold text-gray-700 dark:text-white hover:bg-gray-50 transition-all flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-arrow-left"></i>
                        Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-[#0f172a]/40 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl shadow-glass overflow-hidden shadow-2xl shadow-indigo-500/5">
                <form action="{{ route('logistics.vehicles.update', $vehicle) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Plate Number -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Plaka *</label>
                            <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all placeholder:text-gray-400"
                                placeholder="Örn: 34 ABC 123">
                            @error('plate_number') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Vehicle Type -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Araç Tipi *</label>
                            <select name="type" required
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                                <option value="Tır" {{ $vehicle->type == 'Tır' ? 'selected' : '' }}>Tır</option>
                                <option value="Kamyon" {{ $vehicle->type == 'Kamyon' ? 'selected' : '' }}>Kamyon</option>
                                <option value="Kamyonet" {{ $vehicle->type == 'Kamyonet' ? 'selected' : '' }}>Kamyonet</option>
                                <option value="Panelvan" {{ $vehicle->type == 'Panelvan' ? 'selected' : '' }}>Panelvan</option>
                                <option value="Binek" {{ $vehicle->type == 'Binek' ? 'selected' : '' }}>Binek</option>
                            </select>
                        </div>

                        <!-- Brand -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Marka</label>
                            <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all"
                                placeholder="Örn: Mercedes-Benz">
                        </div>

                        <!-- Model -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Model</label>
                            <input type="text" name="model" value="{{ old('model', $vehicle->model) }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all"
                                placeholder="Örn: Actros 1845">
                        </div>

                        <!-- Capacity -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Kapasite (KG)</label>
                            <input type="number" name="capacity_weight" value="{{ old('capacity_weight', $vehicle->capacity_weight) }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all"
                                placeholder="Örn: 24000">
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Durum *</label>
                            <select name="status" required
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                                <option value="available" {{ $vehicle->status == 'available' ? 'selected' : '' }}>Müsait</option>
                                <option value="on_route" {{ $vehicle->status == 'on_route' ? 'selected' : '' }}>Yolda</option>
                                <option value="maintenance" {{ $vehicle->status == 'maintenance' ? 'selected' : '' }}>Bakımda</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-white/5">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-indigo-500/20 transition-all active:scale-[0.98]">
                            Değişiklikleri Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
