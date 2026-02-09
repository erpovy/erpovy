<x-app-layout>
    <x-slot name="header">
        Hesabı Düzenle: {{ $account->name }}
    </x-slot>

    <x-card class="max-w-2xl mx-auto p-6">
        <h2 class="text-xl font-bold text-white mb-6">Hesap Bilgilerini Güncelle</h2>

        <form action="{{ route('accounting.accounts.update', $account) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Code -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Hesap Kodu</label>
                    <input type="text" name="code" value="{{ old('code', $account->code) }}" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="Örn: 120" required>
                    <p class="mt-1 text-xs text-slate-500">Tek Düzen Hesap Planı kodu.</p>
                    @error('code')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Hesap Adı</label>
                    <input type="text" name="name" value="{{ old('name', $account->name) }}" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="Örn: Alıcılar" required>
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                     <label class="block text-sm font-medium text-slate-400 mb-2">Hesap Türü</label>
                     <select name="type" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                         @foreach(\Modules\Accounting\Models\Account::getTypes() as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $account->type) == $key ? 'selected' : '' }} class="bg-slate-900">{{ $label }}</option>
                         @endforeach
                     </select>
                     @error('type')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center pt-6 border-t border-white/5">
                    <!-- Delete (Far Left) -->
                    <form action="{{ route('accounting.accounts.destroy', $account) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors font-bold text-sm">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            SİL
                        </button>
                    </form>

                    <div class="flex gap-4">
                        <a href="{{ route('accounting.accounts.index') }}" class="px-6 py-2 rounded-lg border border-white/10 text-slate-300 hover:bg-white/5 transition-colors font-medium text-sm">
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
