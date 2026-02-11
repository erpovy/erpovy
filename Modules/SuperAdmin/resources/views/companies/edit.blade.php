<x-app-layout>
    <x-slot name="header">
        Şirket Düzenle: {{ $company->name }}
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card>
            <div class="p-6 border-b border-white/5">
                <h2 class="text-xl font-bold text-white">Şirket Bilgilerini Düzenle</h2>
                <p class="text-sm text-slate-400 mt-1">Şirket temel bilgilerini güncelleyin</p>
            </div>

            @if(session('success'))
                <div class="m-6 bg-green-500/10 border border-green-500/50 text-green-400 p-4 rounded-xl flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="m-6 bg-red-500/10 border border-red-500/50 text-red-500 p-4 rounded-xl flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]">error</span>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('superadmin.companies.update', $company) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Company Name -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Şirket Unvanı</label>
                        <input type="text" name="name" value="{{ old('name', $company->name) }}" 
                               class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                               placeholder="Örn: ABC Teknoloji A.Ş." required>
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Domain -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Alt Alan Adı (Domain)</label>
                        <input type="text" name="domain" value="{{ old('domain', $company->domain) }}" 
                               class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                               placeholder="Örn: abc-tech">
                        <p class="mt-1 text-xs text-slate-500">Boş bırakılabilir. Çoklu tenant yapısı için kullanılır.</p>
                        @error('domain')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Durum</label>
                        <select name="status" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            <option value="active" {{ old('status', $company->status) == 'active' ? 'selected' : '' }} class="bg-slate-900">Aktif</option>
                            <option value="suspended" {{ old('status', $company->status) == 'suspended' ? 'selected' : '' }} class="bg-slate-900">Askıya Alınmış</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($registrant)
                    <!-- Registrant Info -->
                    <div class="p-4 rounded-xl bg-primary/5 border border-primary/20 space-y-3">
                        <div class="flex items-center gap-2 text-primary">
                            <span class="material-symbols-outlined text-[20px]">person_pin_circle</span>
                            <span class="text-sm font-bold uppercase tracking-wider">Kayıt Eden Kullanıcı</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase font-bold">Ad Soyad</p>
                                <p class="text-sm text-white font-medium">{{ $registrant->name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase font-bold">E-posta</p>
                                <p class="text-sm text-white font-medium">{{ $registrant->email }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="flex justify-between items-center pt-6 border-t border-white/5">
                        <div class="flex gap-4">
                            <a href="{{ route('superadmin.companies.index') }}" class="px-6 py-2 rounded-lg border border-white/10 text-slate-300 hover:bg-white/5 transition-colors font-medium text-sm">
                                İptal
                            </a>
                            <button type="submit" class="bg-primary-600 text-white px-8 py-2 rounded-lg hover:bg-primary-500 shadow-neon transition-all font-bold text-sm">
                                GÜNCELLE
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </x-card>

        <!-- Module Management Section -->
        <x-card>
            <div class="p-6 border-b border-white/5">
                <h2 class="text-xl font-bold text-white">Modül Yönetimi</h2>
                <p class="text-sm text-slate-400 mt-1">Şirket için aktif modülleri belirleyin</p>
            </div>

            <div class="p-6 grid grid-cols-1 gap-4">
                @php $activeModules = $company->settings['modules'] ?? []; @endphp
                @foreach($allModules as $key => $module)
                    <div class="flex items-center justify-between p-4 rounded-xl bg-slate-900/50 border border-white/5 hover:border-primary/30 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[28px]">{{ $module['icon'] }}</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-white">{{ $module['name'] }}</h3>
                                <p class="text-xs text-slate-500">{{ $key }} Modülü</p>
                            </div>
                        </div>

                        <form action="{{ route('superadmin.companies.toggle-module', $company) }}" method="POST">
                            @csrf
                            <input type="hidden" name="module" value="{{ $key }}">
                            <button type="submit" 
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none {{ in_array($key, $activeModules) ? 'bg-primary' : 'bg-slate-700' }}">
                                <span class="sr-only">Toggle Module</span>
                                <span aria-hidden="true" 
                                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ in_array($key, $activeModules) ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Danger Zone -->
            <div class="p-6 border-t border-white/5 bg-red-500/5">
                <h3 class="text-sm font-bold text-red-500 uppercase tracking-wider mb-4">Tehlikeli Bölge</h3>
                <form action="{{ route('superadmin.companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Bu şirketi silmek istediğinize emin misiniz? Bu işlem geri alınamaz!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors font-bold text-sm w-full justify-center border border-red-500/20">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                        ŞİRKETİ TAMAMEN SİL
                    </button>
                </form>
            </div>
        </x-card>
    </div>
</x-app-layout>
