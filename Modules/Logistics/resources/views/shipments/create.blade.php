<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-cyan-500/10 to-indigo-500/10 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-4">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Yeni Sevkiyat Oluştur
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Sevkiyat Bilgilerini Tanımlayın
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logistics.shipments.index') }}" class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 px-4 py-2 rounded-xl text-sm font-bold text-gray-700 dark:text-white hover:bg-gray-50 transition-all flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-arrow-left"></i>
                        Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-[#0f172a]/40 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl shadow-glass overflow-hidden shadow-2xl shadow-blue-500/5">
                <form action="{{ route('logistics.shipments.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contact / Customer -->
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Müşteri / Alıcı *</label>
                            <select name="contact_id" required
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                                <option value="">Müşteri Seçiniz</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}" {{ old('contact_id') == $contact->id ? 'selected' : '' }}>
                                        {{ $contact->name }} ({{ $contact->company_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tracking Number -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Takip Numarası *</label>
                            <input type="text" name="tracking_number" value="{{ old('tracking_number', 'TRK-' . strtoupper(Str::random(8))) }}" required
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all placeholder:text-gray-400"
                                placeholder="Örn: TRK123456">
                            @error('tracking_number') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Başlangıç Durumu *</label>
                            <select name="status" required
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                                <option value="pending">Beklemede</option>
                                <option value="in_transit">Yola Çıktı</option>
                            </select>
                        </div>

                        <!-- Origin -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Çıkış Noktası *</label>
                            <input type="text" name="origin" value="{{ old('origin') }}" required
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all"
                                placeholder="Örn: İstanbul Depo">
                        </div>

                        <!-- Destination -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Varış Noktası *</label>
                            <input type="text" name="destination" value="{{ old('destination') }}" required
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all"
                                placeholder="Örn: Ankara Bölge Müdürlüğü">
                        </div>

                        <!-- Weight -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Ağırlık (KG)</label>
                            <input type="number" step="0.01" name="weight_kg" value="{{ old('weight_kg') }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                        </div>

                        <!-- Estimated Delivery -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Tahmini Teslimat Tarihi</label>
                            <input type="date" name="estimated_delivery" value="{{ old('estimated_delivery') }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-white/5">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-blue-500/20 transition-all active:scale-[0.98]">
                            Sevkiyatı Başlat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
