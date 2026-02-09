<x-app-layout>
    <x-slot name="header">
        Şirket Detayı: {{ $company->name }}
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6">
            <x-card class="p-6">
                <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span>
                    Genel Bilgiler
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-slate-500 block">Şirket Unvanı</label>
                        <div class="text-white font-medium">{{ $company->name }}</div>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 block">Domain / Alt Alan Adı</label>
                        <div class="text-white font-medium">{{ $company->domain ?? 'Tanımlanmamış' }}</div>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 block">Kayıt Tarihi</label>
                        <div class="text-white font-medium">{{ $company->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 block">Durum</label>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-green-900/30 text-green-400 border border-green-800/50">
                            {{ strtoupper($company->status ?? 'ACTIVE') }}
                        </span>
                    </div>
                </div>
            </x-card>

            <x-card class="p-6">
                <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-400">database</span>
                    Veritabanı Durumu
                </h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Bağlantı</span>
                        <span class="text-white font-mono">{{ $company->db_connection ?? 'Sistem Varsayılan' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Veri Boyutu</span>
                        <span class="text-white">~ 1.2 MB</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Module Management -->
            <x-card class="overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="text-white font-bold">Modül ve Yetki Yönetimi</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php 
                        $allModules = [
                            ['id' => 'Accounting', 'name' => 'Muhasebe', 'desc' => 'Fatura, Fiş ve Hesap Planı'],
                            ['id' => 'CRM', 'name' => 'CRM', 'desc' => 'Müşteri ve Tedarikçi Yönetimi'],
                            ['id' => 'Inventory', 'name' => 'Stok', 'desc' => 'Ürün ve Depo Takibi'],
                        ];
                        $activeModules = $company->settings['modules'] ?? ['Accounting', 'CRM', 'Inventory'];
                    @endphp

                    @foreach($allModules as $mod)
                        <div class="border border-white/5 rounded-xl p-4 bg-white/5 flex justify-between items-center group hover:border-primary/30 transition-colors">
                            <div>
                                <div class="text-white font-bold text-sm">{{ $mod['name'] }}</div>
                                <div class="text-xs text-slate-500">{{ $mod['desc'] }}</div>
                            </div>
                            <form action="{{ route('superadmin.companies.toggle-module', $company) }}" method="POST">
                                @csrf
                                <input type="hidden" name="module" value="{{ $mod['id'] }}">
                                <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ in_array($mod['id'], $activeModules) ? 'bg-primary' : 'bg-slate-700' }}">
                                    <span class="sr-only">Toggle</span>
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ in_array($mod['id'], $activeModules) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </x-card>

            <!-- Location Settings -->
            <x-card class="overflow-hidden">
                <div class="p-6 border-b border-white/5">
                    <h3 class="text-white font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">location_on</span>
                        Konum Ayarları
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Hava durumu widget'ı için şirket konumunu belirleyin</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('superadmin.companies.update-location', $company) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Ülke</label>
                            <input type="text" name="country" value="{{ $company->settings['country'] ?? 'Turkey' }}" 
                                   class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                                   placeholder="Örn: Turkey">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Şehir</label>
                            <input type="text" name="city" value="{{ $company->settings['city'] ?? 'Ankara' }}" 
                                   class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                                   placeholder="Örn: Ankara">
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-500 shadow-neon transition-all font-bold text-sm">
                                KAYDET
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>

            <!-- Read-only Inspection -->
            <x-card class="bg-blue-600/5 border-blue-500/20 p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-blue-500/20 rounded-lg text-blue-400">
                        <span class="material-symbols-outlined">visibility</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-bold mb-1">Gözetim Modu (Salt-Okunur)</h4>
                        <p class="text-slate-400 text-sm mb-4">Bu şirketin paneline "Read-Only" yetkisiyle giriş yaparak işlemleri denetleyebilirsiniz. Hiçbir veri değiştirilemez.</p>
                        <form action="{{ route('superadmin.companies.inspect', $company) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition-all">
                                Şirket Panelini İzle
                            </button>
                        </form>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
