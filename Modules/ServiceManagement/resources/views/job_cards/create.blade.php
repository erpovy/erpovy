<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-cyan-500/5 to-sky-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div class="flex items-center gap-4">
                    <a href="{{ route('servicemanagement.job-cards.index') }}" class="p-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 hover:bg-blue-500 hover:text-white transition-all">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                            Yeni İş Emri
                        </h2>
                        <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span>
                            Servis kabul ve iş emri açılışı
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8 max-w-4xl">
            <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl p-8">
                <form action="{{ route('servicemanagement.job-cards.store') }}" method="POST">
                    @csrf
                    
                    <!-- Araç ve Müşteri Seçimi -->
                    <div class="mb-8 p-6 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-500">directions_car</span>
                            ARAÇ BİLGİLERİ
                        </h3>
                        
                        <div class="space-y-4">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">ARAÇ SEÇİNİZ</label>
                            <select name="vehicle_id" required class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white font-mono">
                                <option value="">Bir araç seçin...</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->plate_number }} - {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->customer ? $vehicle->customer->name : 'Dahili' }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 pl-1">* Listede olmayan araçlar için önce Araç Kartı oluşturmalısınız.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">GİRİŞ KM</label>
                                <input type="number" name="odometer_reading" value="{{ old('odometer_reading') }}" 
                                    class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm font-mono focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white"
                                    placeholder="Örn: 145000">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">YAKIT SEVİYESİ (0-1)</label>
                                <select name="fuel_level" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">
                                    <option value="0.00">Boş</option>
                                    <option value="0.25">Çeyrek Depo</option>
                                    <option value="0.50" selected>Yarım Depo</option>
                                    <option value="0.75">Çeyrek Depo (Doluya Yakın)</option>
                                    <option value="1.00">Tam Dolu</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- İş Emri Detayları -->
                    <div class="mb-8 p-6 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-orange-500">assignment</span>
                            İŞ DETAYLARI
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">GİRİŞ TARİHİ</label>
                                <input type="datetime-local" name="entry_date" value="{{ old('entry_date', now()->format('Y-m-d\TH:i')) }}" required 
                                    class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">TAHMİNİ TESLİM</label>
                                <input type="datetime-local" name="expected_completion_date" value="{{ old('expected_completion_date') }}" 
                                    class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">ÖNCELİK</label>
                                <select name="priority" required class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">
                                    <option value="low">Düşük</option>
                                    <option value="normal" selected>Normal</option>
                                    <option value="high">Yüksek</option>
                                    <option value="urgent">Acil</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">MÜŞTERİ ŞİKAYETİ / İSTEKLER</label>
                            <textarea name="customer_complaint" rows="4" required placeholder="Müşterinin belirttiği arıza veya talep..."
                                class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">{{ old('customer_complaint') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('servicemanagement.job-cards.index') }}" class="px-8 py-4 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-black text-xs uppercase tracking-widest transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                            İPTAL
                        </a>
                        <button type="submit" class="px-12 py-4 rounded-xl bg-blue-500 text-white font-black text-xs uppercase tracking-widest transition-all hover:scale-[1.05] active:scale-[0.95] shadow-lg shadow-blue-500/20">
                            İŞ EMRİ OLUŞTUR
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
