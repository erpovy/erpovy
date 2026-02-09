<x-app-layout>
    <x-slot name="header">Yeni Birim Ekle</x-slot>
    <x-card>
        <div class="p-6">
            <form action="{{ route('inventory.units.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Birim Adı *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500" placeholder="Örn: Kilogram, Litre, Metre">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Sembol *</label>
                        <input type="text" name="symbol" value="{{ old('symbol') }}" required class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500" placeholder="Örn: Kg, Lt, m">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Tip *</label>
                        <select name="type" required class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="piece">Adet</option>
                            <option value="weight">Ağırlık</option>
                            <option value="volume">Hacim</option>
                            <option value="length">Uzunluk</option>
                            <option value="area">Alan</option>
                            <option value="other">Diğer</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_base_unit" value="1" class="rounded bg-slate-800 border-white/10 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-white">Ana Birim</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded bg-slate-800 border-white/10 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-white">Aktif</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-white/5">
                    <a href="{{ route('inventory.units.index') }}" class="px-4 py-2 text-sm text-slate-300 hover:text-white">İptal</a>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-500 shadow-neon">Kaydet</button>
                </div>
            </form>
        </div>
    </x-card>
</x-app-layout>
