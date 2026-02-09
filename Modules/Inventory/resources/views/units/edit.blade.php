<x-app-layout>
    <x-slot name="header">Birim Düzenle</x-slot>
    <x-card>
        <div class="p-6">
            <form action="{{ route('inventory.units.update', $unit) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Birim Adı *</label>
                        <input type="text" name="name" value="{{ old('name', $unit->name) }}" required class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Sembol *</label>
                        <input type="text" name="symbol" value="{{ old('symbol', $unit->symbol) }}" required class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Tip *</label>
                        <select name="type" required class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="piece" {{ $unit->type == 'piece' ? 'selected' : '' }}>Adet</option>
                            <option value="weight" {{ $unit->type == 'weight' ? 'selected' : '' }}>Ağırlık</option>
                            <option value="volume" {{ $unit->type == 'volume' ? 'selected' : '' }}>Hacim</option>
                            <option value="length" {{ $unit->type == 'length' ? 'selected' : '' }}>Uzunluk</option>
                            <option value="area" {{ $unit->type == 'area' ? 'selected' : '' }}>Alan</option>
                            <option value="other" {{ $unit->type == 'other' ? 'selected' : '' }}>Diğer</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_base_unit" value="1" {{ $unit->is_base_unit ? 'checked' : '' }} class="rounded bg-slate-800 border-white/10 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-white">Ana Birim</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $unit->is_active ? 'checked' : '' }} class="rounded bg-slate-800 border-white/10 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-white">Aktif</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-white/5">
                    <a href="{{ route('inventory.units.index') }}" class="px-4 py-2 text-sm text-slate-300 hover:text-white">İptal</a>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-500 shadow-neon">Güncelle</button>
                </div>
            </form>
        </div>
    </x-card>
</x-app-layout>
