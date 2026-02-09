<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center gap-6 py-2">
                <a href="{{ route('sales.subscriptions.index') }}" class="flex items-center justify-center w-12 h-12 rounded-2xl bg-white/5 border border-white/10 text-slate-400 hover:text-white hover:bg-white/10 hover:border-primary/30 transition-all group/back">
                    <span class="material-symbols-outlined text-[24px] group-hover/back:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Yeni Abonelik Kaydı
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
                        Sisteme yeni bir periyodik abonelik tanımlayın
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{
        price: 0,
        billing_interval: 'monthly',
        start_date: '{{ date('Y-m-d') }}',
        status: '{{ old('status', 'active') }}',
        get mrr() {
            if (this.billing_interval === 'monthly') return this.price;
            if (this.billing_interval === 'quarterly') return this.price / 3;
            if (this.billing_interval === 'yearly') return this.price / 12;
            return 0;
        },
        get annual_value() {
            return (this.mrr * 12).toLocaleString('tr-TR', { minimumFractionDigits: 2 });
        },
        get mrr_formatted() {
            return this.mrr.toLocaleString('tr-TR', { minimumFractionDigits: 2 });
        }
    }">
        <div class="container mx-auto max-w-6xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Form -->
                <div class="lg:col-span-2 space-y-6">
                    <x-card class="p-8 !bg-[#0f172a]/40 border-white/5 backdrop-blur-3xl relative overflow-hidden shadow-2xl">
                        <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                            <span class="material-symbols-outlined text-[160px]">subscriptions</span>
                        </div>
                        
                        <form action="{{ route('sales.subscriptions.store') }}" method="POST" class="space-y-8 relative">
                            @csrf
                            
                            @if ($errors->any())
                                <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-2xl">
                                    <div class="flex items-center gap-3 text-red-400 mb-2">
                                        <span class="material-symbols-outlined">error</span>
                                        <span class="font-bold text-sm uppercase tracking-widest">Hata Tespit Edildi</span>
                                    </div>
                                    <ul class="list-disc list-inside text-xs text-red-300/80 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Section: Basic Info -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center border border-primary/20">
                                        <span class="material-symbols-outlined text-primary text-[20px]">person_add</span>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Müşteri ve Hizmet</h3>
                                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Abonelik Temel Bilgileri</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
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
                                                    <option value="{{ $contact->id }}" {{ old('contact_id') == $contact->id ? 'selected' : '' }} class="bg-[#0f172a] text-white">{{ $contact->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined pointer-events-none text-sm">expand_more</span>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                            <span class="material-symbols-outlined text-[18px] text-primary">label</span>
                                            Abonelik Adı / Hizmet
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                   class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-5 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none font-medium"
                                                   style="background-color: #1e293b !important;"
                                                   placeholder="Örn: Bulut Depolama Paketi" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Pricing & Period -->
                            <div class="space-y-6 pt-6 border-t border-white/5">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 shadow-inner">
                                        <span class="material-symbols-outlined text-emerald-500 text-[20px]">payments</span>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Fiyatlandırma ve Periyot</h3>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Maliyet ve Fatura Döngüsü</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-3">
                                        <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                            <span class="material-symbols-outlined text-[18px] text-emerald-500">payments</span>
                                            Birim Fiyat
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="price" x-model.number="price"
                                                   class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-5 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none font-bold" 
                                                   style="background-color: #1e293b !important;"
                                                   placeholder="0,00" required>
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-500 font-black text-sm">₺</span>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                            <span class="material-symbols-outlined text-[18px] text-primary">event_repeat</span>
                                            Fatura Periyodu
                                        </label>
                                        <div class="relative">
                                            <select name="billing_interval" x-model="billing_interval"
                                                    class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-5 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none appearance-none font-medium" 
                                                    style="background-color: #1e293b !important;" required>
                                                <option value="monthly" class="bg-[#0f172a] text-white">Aylık</option>
                                                <option value="quarterly" class="bg-[#0f172a] text-white">3 Aylık</option>
                                                <option value="yearly" class="bg-[#0f172a] text-white">Yıllık</option>
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
                                </div>

                                <div class="space-y-4 pt-6 border-t border-white/5">
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-[0.3em] mb-4 block">
                                        DURUM
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Aktif Butonu -->
                                        <div class="relative">
                                            <input type="radio" name="status" id="status_active" value="active" 
                                                   class="sr-only" x-model="status" @change="status = 'active'">
                                            <label for="status_active" 
                                                   :class="status === 'active' ? 'bg-emerald-600 border-emerald-400 text-white shadow-[0_0_25px_rgba(16,185,129,0.5)] scale-[1.05]' : 'bg-white/5 border-white/10 text-slate-500 hover:bg-white/10'"
                                                   class="w-full h-16 flex items-center justify-center gap-3 rounded-2xl border-2 font-black text-[11px] uppercase tracking-widest cursor-pointer transition-all duration-300 relative overflow-hidden">
                                                <span class="material-symbols-outlined text-[24px]" x-show="status === 'active'" x-transition:enter="animate-bounce">check_circle</span>
                                                <span class="relative z-10">AKTİF</span>
                                            </label>
                                        </div>

                                        <!-- Askıya Al Butonu -->
                                        <div class="relative">
                                            <input type="radio" name="status" id="status_suspended" value="suspended" 
                                                   class="sr-only" x-model="status" @change="status = 'suspended'">
                                            <label for="status_suspended" 
                                                   :class="status === 'suspended' ? 'bg-amber-600 border-amber-400 text-white shadow-[0_0_25px_rgba(245,158,11,0.5)] scale-[1.05]' : 'bg-white/5 border-white/10 text-slate-500 hover:bg-white/10'"
                                                   class="w-full h-16 flex items-center justify-center gap-3 rounded-2xl border-2 font-black text-[11px] uppercase tracking-widest cursor-pointer transition-all duration-300 relative overflow-hidden">
                                                <span class="material-symbols-outlined text-[24px]" x-show="status === 'suspended'" x-transition:enter="animate-bounce">pause_circle</span>
                                                <span class="relative z-10">ASKIDA</span>
                                            </label>
                                        </div>

                                        <!-- İptal Et Butonu -->
                                        <div class="relative">
                                            <input type="radio" name="status" id="status_cancelled" value="cancelled" 
                                                   class="sr-only" x-model="status" @change="status = 'cancelled'">
                                            <label for="status_cancelled" 
                                                   :class="status === 'cancelled' ? 'bg-red-600 border-red-400 text-white shadow-[0_0_25px_rgba(239,68,68,0.5)] scale-[1.05]' : 'bg-white/5 border-white/10 text-slate-500 hover:bg-white/10'"
                                                   class="w-full h-16 flex items-center justify-center gap-3 rounded-2xl border-2 font-black text-[11px] uppercase tracking-widest cursor-pointer transition-all duration-300 relative overflow-hidden">
                                                <span class="material-symbols-outlined text-[24px]" x-show="status === 'cancelled'" x-transition:enter="animate-bounce">cancel</span>
                                                <span class="relative z-10">İPTAL</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 pt-6 border-t border-white/5">
                                <label class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest pl-1">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">description</span>
                                    Ek Notlar
                                </label>
                                <textarea name="notes" rows="4" 
                                          class="w-full bg-[#1e293b] text-white border border-white/10 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all outline-none resize-none font-medium" 
                                          style="background-color: #1e293b !important;"
                                          placeholder="Abonelik ile ilgili detaylı açıklamalar...">{{ old('notes') }}</textarea>
                            </div>

                            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
                                <p class="hidden lg:block text-[11px] text-slate-500 font-bold uppercase tracking-widest italic leading-relaxed max-w-[300px]">
                                    * Abonelik başlatıldığı tarihten itibaren seçilen periyotlarla faturalandırılacaktır.
                                </p>
                                <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                                    <a href="{{ route('sales.subscriptions.index') }}" 
                                       class="w-full md:w-40 h-14 flex items-center justify-center rounded-2xl border border-white/10 text-slate-400 font-bold text-[11px] uppercase tracking-widest hover:bg-white/5 hover:text-white transition-all text-center">
                                        İPTAL
                                    </a>
                                    <button type="submit" 
                                            class="w-full md:w-72 h-14 flex items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-primary to-blue-600 text-white font-bold text-[11px] uppercase tracking-widest hover:opacity-90 active:scale-95 shadow-2xl shadow-primary/20 transition-all whitespace-nowrap">
                                        <span>ABONELİĞİ BAŞLAT</span>
                                        <span class="material-symbols-outlined text-[20px]">send</span>
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
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-[0.3em] mb-4 block">
                                DURUM
                            </label>
                            <div class="py-10 px-4 rounded-3xl !bg-[#0f172a]/60 border border-white/5 backdrop-blur-md shadow-inner">
                                <div class="text-4xl font-black text-white mb-2 flex items-center justify-center gap-1">
                                    <span class="text-xl text-emerald-500 font-bold">₺</span>
                                    <span x-text="annual_value">0,00</span>
                                </div>
                                <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest">Tahmini Yıllık Değer</div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl !bg-[#0f172a]/60 border border-white/5">
                                    <div class="text-xl font-black text-white mb-1">₺<span x-text="mrr_formatted">0,00</span></div>
                                    <div class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Aylık Değer (MRR)</div>
                                </div>
                                <div class="p-4 rounded-2xl !bg-[#0f172a]/60 border border-white/5">
                                    <div class="text-xl font-black text-white mb-1" x-text="billing_interval === 'monthly' ? 'Aylık' : (billing_interval === 'quarterly' ? '3 Aylık' : 'Yıllık')">Aylık</div>
                                    <div class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Periyot</div>
                                </div>
                            </div>

                            <div class="pt-4 space-y-4 text-left">
                                <div class="flex items-center justify-between text-[11px] px-2 border-b border-white/5 pb-3">
                                    <span class="text-slate-500 font-bold uppercase tracking-wider">İlk Fatura Tarihi</span>
                                    <span class="text-white font-black" x-text="start_date ? start_date : '--'">--</span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] px-2">
                                    <span class="text-slate-500 font-bold uppercase tracking-wider">Hizmet Modeli</span>
                                    <span class="text-primary font-black uppercase tracking-widest">SaaS / Periyodik</span>
                                </div>
                            </div>

                            <div class="mt-8 p-5 rounded-2xl bg-emerald-500/5 border border-emerald-500/10">
                                <div class="flex gap-4 text-left">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex-shrink-0 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-emerald-500 text-sm">trending_up</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 leading-relaxed font-medium">
                                        Abonelik modeli, işletmenizin öngörülebilir gelirini (MRR) artırmak için en iyi yöntemdir.
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
