<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center gap-6 py-2">
                <a href="{{ route('sales.rentals.index') }}" class="flex items-center justify-center w-12 h-12 rounded-2xl bg-white/5 border border-white/10 text-slate-400 hover:text-white hover:bg-white/10 hover:border-primary/30 transition-all group/back">
                    <span class="material-symbols-outlined text-[24px] group-hover/back:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Yeni Kiralama Kaydı
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
                        Sisteme yeni bir kiralama sözleşmesi tanımlayın
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        daily_price: 0,
        start_date: '{{ date('Y-m-d') }}',
        end_date: '',
        get total_days() {
            if (!this.start_date || !this.end_date) return 0;
            const start = new Date(this.start_date);
            const end = new Date(this.end_date);
            const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            return diff > 0 ? diff : 0;
        },
        get total_amount() {
            return (this.total_days * this.daily_price).toLocaleString('tr-TR', { minimumFractionDigits: 2 });
        }
    }">
        <div class="container mx-auto max-w-6xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Form -->
                <div class="lg:col-span-2 space-y-6">
                    <x-card class="p-8 !bg-[#0f172a]/40 border-white/5 backdrop-blur-3xl relative overflow-hidden shadow-2xl">
                        <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                            <span class="material-symbols-outlined text-[160px]">contract</span>
                        </div>
                        
                        <form action="{{ route('sales.rentals.store') }}" method="POST" class="space-y-8 relative">
                            @csrf
                            
                            <!-- Section: Basic Info -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center border border-primary/20">
                                        <span class="material-symbols-outlined text-primary text-[20px]">person_add</span>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Müşteri ve Ürün</h3>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Temel Sözleşme Tarafları</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3 relative">
                                        <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                            <span class="material-symbols-outlined text-[18px] text-primary">person</span>
                                            Müşteri Seçimi
                                        </label>
                                        <div class="relative">
                                            <select name="contact_id" 
                                                    class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-5 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none appearance-none font-medium" 
                                                    style="background-color: #1e293b !important;" required>
                                                <option value="" class="bg-[#0f172a] text-slate-500">Müşteri Seçin...</option>
                                                @foreach($contacts as $contact)
                                                    <option value="{{ $contact->id }}" class="bg-[#0f172a] text-white">{{ $contact->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined pointer-events-none text-sm">expand_more</span>
                                        </div>
                                    </div>

                                    <div class="space-y-3 relative">
                                        <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                            <span class="material-symbols-outlined text-[18px] text-primary">inventory_2</span>
                                            Ürün / Ekipman
                                        </label>
                                        <div class="relative">
                                            <select name="product_id" 
                                                    class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-5 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none appearance-none font-medium" 
                                                    style="background-color: #1e293b !important;">
                                                <option value="" class="bg-[#0f172a] text-slate-500">Ürün Seçin (Opsiyonel)...</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" class="bg-[#0f172a] text-white">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined pointer-events-none text-sm">expand_more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Pricing & Dates -->
                            <div class="space-y-6 pt-6 border-t border-white/5">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 shadow-inner">
                                        <span class="material-symbols-outlined text-emerald-500 text-[20px]">payments</span>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Kiralama Detayları</h3>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Fiyatlandırma ve Süre Bilgisi</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                            <span class="material-symbols-outlined text-[18px] text-emerald-500">payments</span>
                                            Günlük Birim Fiyat
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="daily_price" x-model.number="daily_price"
                                                   class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-5 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none font-bold" 
                                                   style="background-color: #1e293b !important;"
                                                   placeholder="0,00" required>
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500 font-black text-sm">₺</span>
                                        </div>
                                    </div>

                                    <div class="space-y-3 relative">
                                        <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                            <span class="material-symbols-outlined text-[18px] text-primary">stars</span>
                                            Durum
                                        </label>
                                        <div class="relative">
                                            <select name="status" 
                                                    class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-5 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none appearance-none font-medium" 
                                                    style="background-color: #1e293b !important;" required>
                                                <option value="active" class="bg-[#0f172a] text-white">Aktif</option>
                                                <option value="completed" class="bg-[#0f172a] text-white">Tamamlandı</option>
                                                <option value="overdue" class="bg-[#0f172a] text-white">Gecikmiş</option>
                                                <option value="cancelled" class="bg-[#0f172a] text-white">İptal Edildi</option>
                                            </select>
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined pointer-events-none text-sm">expand_more</span>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                            <span class="material-symbols-outlined text-[18px] text-slate-400">calendar_today</span>
                                            Başlangıç Tarihi
                                        </label>
                                        <div class="relative">
                                            <input type="date" name="start_date" x-model="start_date"
                                                   class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-5 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none font-medium" 
                                                   style="background-color: #1e293b !important; color-scheme: dark;" required>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                            <span class="material-symbols-outlined text-[18px] text-slate-400">event_available</span>
                                            Planlanan Bitiş Tarihi
                                        </label>
                                        <div class="relative">
                                            <input type="date" name="end_date" x-model="end_date"
                                                   class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-5 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none font-medium"
                                                   style="background-color: #1e293b !important; color-scheme: dark;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 pt-6 border-t border-white/5">
                                <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">description</span>
                                    Sözleşme Notları
                                </label>
                                <textarea name="notes" rows="4" 
                                          class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none resize-none font-medium" 
                                          style="background-color: #1e293b !important;"
                                          placeholder="Kiralama ile ilgili özel şartlar..."></textarea>
                            </div>

                            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
                                <p class="hidden lg:block text-[11px] text-slate-500 font-bold uppercase tracking-widest italic leading-relaxed max-w-[300px]">
                                    * Lütfen tüm kiralama bilgilerinizin doğruluğunu teyit ediniz.
                                </p>
                                <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                                    <a href="{{ route('sales.rentals.index') }}" 
                                       class="w-full md:w-40 h-14 flex items-center justify-center rounded-2xl border border-white/10 text-slate-400 font-bold text-[11px] uppercase tracking-widest hover:bg-white/5 hover:text-white transition-all">
                                        İPTAL
                                    </a>
                                    <button type="submit" 
                                            class="w-full md:w-72 h-14 flex items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-primary to-blue-600 text-white font-bold text-[11px] uppercase tracking-widest hover:opacity-90 active:scale-95 shadow-2xl shadow-primary/20 transition-all whitespace-nowrap">
                                        <span>KAYDI TAMAMLA</span>
                                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </x-card>
                </div>

                <!-- Right Column: Summary Card -->
                <div class="space-y-6">
                    <x-card class="p-6 !bg-[#0f172a]/40 border-white/5 backdrop-blur-3xl sticky top-24 overflow-hidden group shadow-2xl">
                        <!-- Background Glow -->
                        <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/10 rounded-full blur-3xl group-hover:bg-primary/20 transition-all duration-700"></div>

                        <div class="relative space-y-6 text-center">
                            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-4">Hesaplama Özeti</h3>
                            
                            <div class="py-10 px-4 rounded-3xl !bg-[#0f172a]/60 border border-white/5 backdrop-blur-md shadow-inner">
                                <div class="text-4xl font-black text-white mb-2 flex items-center justify-center gap-1">
                                    <span class="text-xl text-emerald-500 font-bold">₺</span>
                                    <span x-text="total_amount">0,00</span>
                                </div>
                                <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Tahmini Toplam Tutarı</div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl !bg-[#0f172a]/60 border border-white/5">
                                    <div class="text-xl font-black text-white mb-1" x-text="total_days">0</div>
                                    <div class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Toplam Gün</div>
                                </div>
                                <div class="p-4 rounded-2xl !bg-[#0f172a]/60 border border-white/5">
                                    <div class="text-xl font-black text-white mb-1">₺<span x-text="daily_price.toLocaleString('tr-TR')">0</span></div>
                                    <div class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Birim Fiyat</div>
                                </div>
                            </div>

                            <div class="pt-4 space-y-4">
                                <div class="flex items-center justify-between text-[11px] px-2 border-b border-white/5 pb-3">
                                    <span class="text-slate-500 font-bold uppercase tracking-wider">Kira Periyodu</span>
                                    <span class="text-white font-black" x-text="(start_date ? start_date : '--') + ' / ' + (end_date ? end_date : '---')">-- / --</span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] px-2">
                                    <span class="text-slate-500 font-bold uppercase tracking-wider">KDV Durumu</span>
                                    <span class="text-emerald-500 font-black uppercase tracking-widest">%20 Hariç</span>
                                </div>
                            </div>

                            <div class="mt-8 p-5 rounded-2xl bg-primary/5 border border-primary/10">
                                <div class="flex gap-4 text-left">
                                    <div class="w-8 h-8 rounded-lg bg-primary/20 flex-shrink-0 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-primary text-sm">info</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 leading-relaxed font-medium">
                                        Toplam tutar, girdiğiniz tarihler arasındaki tam gün sayısı üzerinden hesaplanmaktadır.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
