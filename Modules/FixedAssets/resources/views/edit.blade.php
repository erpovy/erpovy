<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Demirbaş Düzenle
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">edit</span>
                        {{ $asset->name }} ({{ $asset->code }})
                    </p>
                </div>
                
                <a href="{{ route('fixedassets.index') }}" class="group flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-bold text-sm transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Listeye Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-4xl px-6 lg:px-8">
            <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('fixedassets.update', $asset->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Demirbaş Adı</label>
                            <input type="text" name="name" required value="{{ old('name', $asset->name) }}"
                                   class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all">
                        </div>

                        <!-- Code -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Demirbaş Kodu / SKU</label>
                            <input type="text" name="code" required value="{{ old('code', $asset->code) }}"
                                   class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all font-mono uppercase">
                        </div>

                        <!-- Category -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Kategori</label>
                            <select name="category_id" class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 appearance-none">
                                <option value="">Kategori Seçin</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Durum</label>
                            <select name="status" class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 appearance-none">
                                @foreach(['active' => 'Aktif', 'retired' => 'Emekli', 'maintenance' => 'Bakımda', 'lost' => 'Kayıp'] as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $asset->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Serial Number -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Seri Numarası</label>
                            <input type="text" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}"
                                   class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all">
                        </div>

                        <!-- Purchase Date -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Alınış Tarihi</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date', optional($asset->purchase_date)->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all">
                        </div>

                        <!-- Purchase Value -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Alış Bedeli (₺)</label>
                            <input type="number" step="0.01" name="purchase_value" value="{{ old('purchase_value', $asset->purchase_value) }}"
                                   class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all">
                        </div>

                         <!-- Useful Life -->
                         <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Faydalı Ömür (Yıl)</label>
                            <input type="number" name="useful_life_years" value="{{ old('useful_life_years', $asset->useful_life_years) }}"
                                   class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all">
                        </div>

                        <!-- Depreciation Method -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Amortisman Yöntemi</label>
                            <select name="depreciation_method" class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 appearance-none">
                                <option value="straight_line" {{ old('depreciation_method', $asset->depreciation_method) == 'straight_line' ? 'selected' : '' }}>Eşit Oranlı (Normal)</option>
                                <option value="declining_balance" {{ old('depreciation_method', $asset->depreciation_method) == 'declining_balance' ? 'selected' : '' }}>Azalan Bakiyeler</option>
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Açıklama / Notlar</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all">{{ old('description', $asset->description) }}</textarea>
                    </div>

                    <div class="pt-4 flex items-center justify-between">
                         <button type="button" onclick="document.getElementById('delete-form').submit()" 
                                class="px-6 py-4 bg-red-500/10 text-red-500 font-black text-sm uppercase tracking-[0.2em] rounded-xl hover:bg-red-500/20 transition-all">
                            Sil
                        </button>
                        
                        <x-primary-button type="submit" class="px-8 py-4 bg-primary text-gray-900 dark:text-white font-black text-sm uppercase tracking-[0.2em] shadow-[0_0_20px_rgba(var(--color-primary),0.3)] hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Değişiklikleri Kaydet
                        </x-primary-button>
                    </div>
                </form>
                
                <form id="delete-form" action="{{ route('fixedassets.destroy', $asset->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
