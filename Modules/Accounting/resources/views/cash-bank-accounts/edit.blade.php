<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 via-teal-500/5 to-emerald-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Hesabı Düzenle
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">edit</span>
                        {{ $account->name }}
                    </p>
                </div>
                <a href="{{ route('accounting.cash-bank-accounts.index') }}" class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white text-sm font-medium transition-all">
                    <span class="material-symbols-outlined text-[18px] align-middle">arrow_back</span>
                    Geri Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-3xl px-6 lg:px-8">
            
            <form action="{{ route('accounting.cash-bank-accounts.update', $account) }}" method="POST">
                @csrf
                @method('PUT')
                
                <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl">
                    
                    <!-- Hesap Adı -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-white mb-2">Hesap Adı *</label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                               value="{{ old('name', $account->name) }}">
                    </div>

                    @if($account->type === 'bank')
                    <!-- Banka Bilgileri -->
                    <div class="space-y-6 mb-6">
                        <div>
                            <label for="bank_name" class="block text-sm font-semibold text-white mb-2">Banka Adı</label>
                            <input type="text" id="bank_name" name="bank_name"
                                   class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   value="{{ old('bank_name', $account->bank_name) }}">
                        </div>

                        <div>
                            <label for="branch" class="block text-sm font-semibold text-white mb-2">Şube</label>
                            <input type="text" id="branch" name="branch"
                                   class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   value="{{ old('branch', $account->branch) }}">
                        </div>

                        <div>
                            <label for="account_number" class="block text-sm font-semibold text-white mb-2">Hesap Numarası</label>
                            <input type="text" id="account_number" name="account_number"
                                   class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-mono"
                                   value="{{ old('account_number', $account->account_number) }}">
                        </div>

                        <div>
                            <label for="iban" class="block text-sm font-semibold text-white mb-2">IBAN</label>
                            <input type="text" id="iban" name="iban"
                                   class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-mono"
                                   value="{{ old('iban', $account->iban) }}"
                                   maxlength="32">
                        </div>
                    </div>
                    @endif

                    <!-- Durum ve Diğer Ayarlar -->
                    <div class="mb-6">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ $account->is_active ? 'checked' : '' }}
                                   class="w-5 h-5 rounded border-white/10 bg-white/5 text-emerald-600 focus:ring-emerald-500 transition-all">
                            <span class="text-white font-medium group-hover:text-emerald-400 transition-colors text-sm">Hesap Aktif Şekilde Kullanılsın mı?</span>
                        </label>
                    </div>

                    <!-- Butonlar -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/10">
                        <a href="{{ route('accounting.cash-bank-accounts.index') }}" 
                           class="px-6 py-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white font-medium transition-all">
                            İptal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-medium transition-all shadow-lg shadow-emerald-500/30">
                            <span class="material-symbols-outlined text-[18px] align-middle mr-1">save</span>
                            Değişiklikleri Kaydet
                        </button>
                    </div>

                </x-card>
            </form>

        </div>
    </div>
</x-app-layout>
