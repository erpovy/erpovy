<x-app-layout>
    <x-slot name="header">Marka Düzenle</x-slot>
    <x-card>
        <div class="p-6">
            <form action="{{ route('inventory.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-400 mb-2">Marka Adı *</label>
                        <input type="text" name="name" value="{{ old('name', $brand->name) }}" required class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Logo</label>
                        @if($brand->logo_path)
                            <div class="mb-2"><img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="h-20 w-20 rounded-lg object-cover"></div>
                        @endif
                        <input type="file" name="logo" accept="image/*" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }} class="rounded bg-slate-800 border-white/10 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-white">Aktif</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-400 mb-2">Açıklama</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">{{ old('description', $brand->description) }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-white/5">
                    <a href="{{ route('inventory.brands.index') }}" class="px-4 py-2 text-sm text-slate-300 hover:text-white">İptal</a>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-500 shadow-neon">Güncelle</button>
                </div>
            </form>
        </div>
    </x-card>
</x-app-layout>
