<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Şirket Detayı: {{ $company->name }}
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">info</span>
                        Şirket Bilgileri ve Ayarlar
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('superadmin.companies.edit', $company) }}" class="group flex items-center gap-2 px-4 py-2 rounded-xl bg-primary/10 text-primary font-bold text-sm transition-all hover:bg-primary/20">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                        Düzenle
                    </a>
                    <a href="{{ route('superadmin.companies.index') }}" class="group flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-bold text-sm transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                        Listeye Dön
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar Info -->
                <div class="lg:col-span-1 space-y-6">
                    <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                            Genel Bilgiler
                        </h3>
                        <div class="space-y-6">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Şirket Unvanı</label>
                                <div class="text-gray-900 dark:text-white font-bold">{{ $company->name }}</div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Domain / Alt Alan Adı</label>
                                <div class="text-gray-900 dark:text-white font-mono text-sm">{{ $company->domain ?? 'Tanımlanmamış' }}</div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Kayıt Tarihi</label>
                                <div class="text-gray-900 dark:text-white font-medium">{{ $company->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 block">Durum</label>
                                <div class="flex">
                                    @if($company->status === 'active')
                                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-rose-500/10 text-rose-500 border border-rose-500/20 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Pasif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </x-card>

                    <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-400 text-[20px]">database</span>
                            Veritabanı Durumu
                        </h3>
                        <div class="space-y-4 text-sm font-medium">
                            <div class="flex justify-between items-center bg-white/5 p-3 rounded-xl border border-white/5">
                                <span class="text-slate-500 text-xs uppercase tracking-tight">Bağlantı</span>
                                <span class="text-gray-900 dark:text-white font-mono">{{ $company->db_connection ?? 'Sistem Varsayılan' }}</span>
                            </div>
                            <div class="flex justify-between items-center bg-white/5 p-3 rounded-xl border border-white/5">
                                <span class="text-slate-500 text-xs uppercase tracking-tight">Veri Boyutu</span>
                                <span class="text-gray-900 dark:text-white">~ 1.2 MB</span>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Location Settings -->
                    <x-card class="overflow-hidden border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="p-8 border-b border-gray-200 dark:border-white/5">
                            <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em] flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px]">location_on</span>
                                Konum Ayarları
                            </h3>
                            <p class="text-[10px] text-slate-500 uppercase font-black mt-1">Hava durumu widget'ı için şirket konumunu belirleyin</p>
                        </div>
                        <div class="p-8">
                            <form action="{{ route('superadmin.companies.update-location', $company) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Ülke</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500 text-[18px]">public</span>
                                            <input type="text" name="country" value="{{ $company->settings['country'] ?? 'Turkey' }}" 
                                                class="w-full pl-11 pr-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all font-bold">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Şehir</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-500 text-[18px]">location_city</span>
                                            <input type="text" name="city" value="{{ $company->settings['city'] ?? 'Ankara' }}" 
                                                class="w-full pl-11 pr-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all font-bold">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="bg-primary px-8 py-3 rounded-xl text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em] shadow-[0_0_20px_rgba(var(--color-primary),0.3)] hover:scale-[1.05] active:scale-[0.95] transition-all">
                                        AYARLARI KAYDET
                                    </button>
                                </div>
                            </form>
                        </div>
                    </x-card>

                    <!-- Read-only Inspection & Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-card class="bg-primary/5 border-primary/20 p-8 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="p-2 bg-primary/20 rounded-lg text-primary">
                                        <span class="material-symbols-outlined text-[20px]">group</span>
                                    </div>
                                    <h4 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-widest text-primary">Kullanıcı Erişimi</h4>
                                </div>
                                <div class="text-3xl font-black text-gray-900 dark:text-white mb-1">
                                    {{ $company->users_count ?? 0 }}
                                </div>
                                <p class="text-[10px] text-slate-500 uppercase font-black tracking-tight">Tanımlı Toplam Kullanıcı Sayısı</p>
                            </div>
                        </x-card>

                        <x-card class="bg-purple-500/5 border-purple-500/20 p-8 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="p-2 bg-purple-500/20 rounded-lg text-purple-400">
                                        <span class="material-symbols-outlined text-[20px]">extension</span>
                                    </div>
                                    <h4 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-widest text-purple-400">Aktif Modüller</h4>
                                </div>
                                <div class="text-3xl font-black text-gray-900 dark:text-white mb-1">
                                    {{ count($company->settings['modules'] ?? []) }}
                                </div>
                                <p class="text-[10px] text-slate-500 uppercase font-black tracking-tight">Şirket Tarafından Kullanılan Modüller</p>
                            </div>
                        </x-card>
                    </div>

                    <x-card class="bg-rose-500/5 border-rose-500/20 border-dashed p-8 border-2">
                        <div class="flex items-start gap-6">
                            <div class="p-4 bg-rose-500/20 rounded-2xl text-rose-500 shadow-[0_0_20px_rgba(244,63,94,0.2)]">
                                <span class="material-symbols-outlined text-[32px]">visibility</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-gray-900 dark:text-white font-black text-base uppercase tracking-tight mb-2">Gözetim Modu (Salt-Okunur)</h4>
                                <p class="text-slate-500 text-sm font-medium mb-6">Bu şirketin paneline "Read-Only" yetkisiyle giriş yaparak işlemleri denetleyebilirsiniz. Hiçbir veri değiştirilemez.</p>
                                <form action="{{ route('superadmin.companies.inspect', $company) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-8 py-4 rounded-xl text-xs font-black uppercase tracking-[0.2em] transition-all shadow-[0_4px_15px_rgba(244,63,94,0.3)] group flex items-center gap-3">
                                        PANELİ DENETLEMEYE BAŞLA
                                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
