<x-app-layout>
    <x-slot name="header">
        Şirket Düzenle: {{ $company->name }}
    </x-slot>

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
            
            <div class="space-y-6 max-w-2xl">
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

                <div class="flex justify-between items-center pt-6 border-t border-white/5">
                    <!-- Delete (Far Left) -->
                    <form action="{{ route('superadmin.companies.destroy', $company) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors font-bold text-sm">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            SİL
                        </button>
                    </form>

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
</x-app-layout>
