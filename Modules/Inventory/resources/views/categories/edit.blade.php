<x-app-layout>
    <x-slot name="header">
        Kategori Düzenle
    </x-slot>

    <x-card>
        <div class="p-6">
            <form action="{{ route('inventory.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kategori Adı -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-400 mb-2">Kategori Adı *</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                               class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Üst Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Üst Kategori</label>
                        <select name="parent_id" 
                                class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Ana Kategori</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->path ?? $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- İkon -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">İkon (Material Symbols)</label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                                   class="flex-1 rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                            @if($category->icon)
                                <span class="material-symbols-outlined text-primary-400 text-2xl">{{ $category->icon }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Sıra -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Sıra</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                               class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <!-- Durum -->
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                   class="rounded bg-slate-800 border-white/10 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-white">Aktif</span>
                        </label>
                    </div>

                    <!-- Açıklama -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-400 mb-2">Açıklama</label>
                        <textarea name="description" rows="3"
                                  class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>

                <!-- Butonlar -->
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-white/5">
                    <a href="{{ route('inventory.categories.index') }}" 
                       class="px-4 py-2 text-sm text-slate-300 hover:text-white transition-colors">
                        İptal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-500 shadow-neon transition-all">
                        Güncelle
                    </button>
                </div>
            </form>
        </div>
    </x-card>
</x-app-layout>
