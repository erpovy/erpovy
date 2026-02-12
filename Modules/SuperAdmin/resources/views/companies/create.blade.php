<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Yeni Şirket Tanımla
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">add_business</span>
                        Sisteme Yeni Bir İşletme/Tenant Kaydedin
                    </p>
                </div>
                
                <a href="{{ route('superadmin.companies.index') }}" class="group flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-bold text-sm transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Listeye Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8 max-w-3xl">
            <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden shadow-2xl">
                <div class="p-8 border-b border-gray-200 dark:border-white/5 bg-primary/5">
                    <h2 class="text-gray-900 dark:text-white font-black text-base uppercase tracking-tight">Temel Şirket Bilgileri</h2>
                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-1">Lütfen yeni şirketin yasal unvanını ve domain bilgilerini girin</p>
                </div>

                <form action="{{ route('superadmin.companies.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="space-y-8">
                        <!-- Company Name -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Şirket Unvanı</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">corporate_fare</span>
                                </div>
                                <input type="text" name="name" value="{{ old('name') }}" 
                                       class="w-full pl-12 pr-4 py-4 bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all font-bold text-lg" 
                                       placeholder="Örn: ABC Teknoloji ve Yazılım A.Ş." required>
                            </div>
                            @error('name')
                                <p class="mt-1 text-[10px] font-black uppercase text-rose-500 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Domain -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Alt Alan Adı (Domain)</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">language</span>
                                    </div>
                                    <input type="text" name="domain" value="{{ old('domain') }}" 
                                           class="w-full pl-12 pr-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all font-bold" 
                                           placeholder="abc-tek">
                                </div>
                                <p class="mt-1 text-[9px] text-slate-500 font-bold uppercase tracking-tight ml-1">Sadece küçük harf ve tire kullanın</p>
                                @error('domain')
                                    <p class="mt-1 text-[10px] font-black uppercase text-rose-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Başlangıç Durumu</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">verified</span>
                                    </div>
                                    <select name="status" class="w-full pl-12 pr-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all font-bold appearance-none">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }} class="bg-white dark:bg-slate-900">Aktif</option>
                                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }} class="bg-white dark:bg-slate-900">Askıya Alınmış</option>
                                    </select>
                                </div>
                                @error('status')
                                    <p class="mt-1 text-[10px] font-black uppercase text-rose-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="pt-8 border-t border-gray-200 dark:border-white/5">
                            <div class="bg-primary/5 rounded-2xl p-6 mb-8 border border-primary/10">
                                <div class="flex gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center text-primary flex-shrink-0">
                                        <span class="material-symbols-outlined">auto_awesome</span>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black uppercase tracking-widest text-primary mb-1">Otomatik Kurulum</h4>
                                        <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                                            Şirket oluşturulduktan sonra varsayılan modüller (Panel & Aktivite) otomatik olarak aktif edilecektir. 
                                            Diğer modülleri (Muhasebe, CRM vb.) şirket detay sayfasından yönetebilirsiniz.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <a href="{{ route('superadmin.companies.index') }}" class="flex-1 text-center px-6 py-4 rounded-xl border border-gray-200 dark:border-white/10 text-slate-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-all font-black text-xs uppercase tracking-widest">
                                    İptal
                                </a>
                                <button type="submit" class="flex-[2] bg-primary text-gray-900 dark:text-white px-8 py-4 rounded-xl hover:scale-[1.02] active:scale-[0.98] shadow-[0_0_20px_rgba(var(--color-primary),0.3)] transition-all font-black text-xs uppercase tracking-[0.2em] group">
                                    ŞİRKETİ KAYDET VE BAŞLAT
                                    <span class="inline-block transform group-hover:translate-x-1 transition-transform ml-2">→</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
