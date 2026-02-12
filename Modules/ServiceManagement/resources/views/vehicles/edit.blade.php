<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/5 via-orange-500/5 to-yellow-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div class="flex items-center gap-4">
                    <a href="{{ route('servicemanagement.vehicles.show', $vehicle->id) }}" class="p-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 hover:bg-amber-500 hover:text-white transition-all">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                            Aracı Düzenle
                        </h2>
                        <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                            {{ $vehicle->plate_number }} - Bilgileri güncelle
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8 max-w-4xl">
            <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl p-8">
                <form action="{{ route('servicemanagement.vehicles.update', $vehicle->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Müşteri -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">MÜŞTERİ (ARAÇ SAHİBİ)</label>
                            <select name="customer_id" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                                <option value="">Şirket Aracı (Dahili)</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $vehicle->customer_id) == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Plaka -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">PLAKA NUMARASI</label>
                            <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required 
                                class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white uppercase"
                                placeholder="34 ABC 123">
                            @error('plate_number') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <!-- Brand -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">MARKA</label>
                            <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" required 
                                class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white"
                                placeholder="Örn: Ford, Mercedes">
                        </div>

                        <!-- Model -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">MODEL</label>
                            <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required 
                                class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white"
                                placeholder="Örn: Transit, Sprinter">
                        </div>

                        <!-- Year -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">ÜRETİM YILI</label>
                            <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" 
                                class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                        </div>

                        <!-- VIN -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">ŞASİ NO (VIN)</label>
                            <input type="text" name="vin" value="{{ old('vin', $vehicle->vin) }}" 
                                class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white uppercase">
                        </div>

                        <!-- Color -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">RENK</label>
                            <input type="text" name="color" value="{{ old('color', $vehicle->color) }}" 
                                class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                        </div>

                        <!-- Current Mileage -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">GÜNCEL KM</label>
                            <input type="number" name="current_mileage" value="{{ old('current_mileage', $vehicle->current_mileage) }}" required 
                                class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-mono focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">DURUM</label>
                            <select name="status" required 
                                class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">
                                <option value="active" {{ old('status', $vehicle->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="maintenance" {{ old('status', $vehicle->status) == 'maintenance' ? 'selected' : '' }}>Serviste</option>
                                <option value="inactive" {{ old('status', $vehicle->status) == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-2 mb-8">
                        <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">NOTLAR</label>
                        <textarea name="notes" rows="4" 
                            class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 outline-none transition-all dark:text-white">{{ old('notes', $vehicle->notes) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('servicemanagement.vehicles.show', $vehicle->id) }}" class="px-8 py-4 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-black text-xs uppercase tracking-widest transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                            İPTAL
                        </a>
                        <button type="submit" class="px-12 py-4 rounded-xl bg-amber-500 text-white font-black text-xs uppercase tracking-widest transition-all hover:scale-[1.05] active:scale-[0.95] shadow-lg shadow-amber-500/20">
                            GÜNCELLE
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
