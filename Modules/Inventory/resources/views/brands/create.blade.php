<x-app-layout>
    <x-slot name="header">Yeni Marka Ekle</x-slot>
    <x-card>
        <div class="p-6">
            <form action="{{ route('inventory.brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-400 mb-2">Marka Adı *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                        @error('name')<p class="mt-1 text-sm text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Logo</label>
                        <input type="file" name="logo" accept="image/*" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                        <p class="mt-1 text-xs text-slate-500">Maksimum 2MB, JPG, PNG</p>
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded bg-slate-800 border-white/10 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-white">Aktif</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-400 mb-2">Açıklama</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-white/5">
                    <a href="{{ route('inventory.brands.index') }}" class="px-4 py-2 text-sm text-slate-300 hover:text-white">İptal</a>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-500 shadow-neon">Kaydet</button>
                </div>
            </form>
        </div>
    </x-card>
</x-app-layout>
