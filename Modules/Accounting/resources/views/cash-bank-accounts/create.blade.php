<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 via-teal-500/5 to-emerald-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Yeni Kasa/Banka Hesabı
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
                        Nakit veya Banka Hesabı Oluşturun
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
            
            <form action="{{ route('accounting.cash-bank-accounts.store') }}" method="POST">
                @csrf
                
                <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl">
                    
                    <!-- Hesap Tipi -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-white mb-3">Hesap Tipi *</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="cash" class="peer sr-only" checked>
                                <div class="p-6 rounded-xl border-2 border-white/10 bg-white/5 peer-checked:border-green-500 peer-checked:bg-green-500/10 transition-all">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-[32px] text-green-400">payments</span>
                                        <div>
                                            <p class="font-bold text-white">Kasa</p>
                                            <p class="text-xs text-slate-400">Nakit para</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="bank" class="peer sr-only">
                                <div class="p-6 rounded-xl border-2 border-white/10 bg-white/5 peer-checked:border-blue-500 peer-checked:bg-blue-500/10 transition-all">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-[32px] text-blue-400">account_balance</span>
                                        <div>
                                            <p class="font-bold text-white">Banka</p>
                                            <p class="text-xs text-slate-400">Banka hesabı</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Hesap Adı -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-white mb-2">Hesap Adı *</label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                               placeholder="Örn: Ana Kasa, Ziraat Bankası TL">
                    </div>

                    <!-- Banka Bilgileri (Sadece Banka seçildiğinde göster) -->
                    <div id="bank-fields" class="hidden space-y-6 mb-6">
                        <div>
                            <label for="bank_name" class="block text-sm font-semibold text-white mb-2">Banka Adı</label>
                            <input type="text" id="bank_name" name="bank_name"
                                   class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   placeholder="Örn: Ziraat Bankası">
                        </div>

                        <div>
                            <label for="branch" class="block text-sm font-semibold text-white mb-2">Şube</label>
                            <input type="text" id="branch" name="branch"
                                   class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                   placeholder="Örn: Ankara Kızılay Şubesi">
                        </div>

                        <div>
                            <label for="account_number" class="block text-sm font-semibold text-white mb-2">Hesap Numarası</label>
                            <input type="text" id="account_number" name="account_number"
                                   class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-mono"
                                   placeholder="Örn: 12345678">
                        </div>

                        <div>
                            <label for="iban" class="block text-sm font-semibold text-white mb-2">IBAN</label>
                            <input type="text" id="iban" name="iban"
                                   class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-mono"
                                   placeholder="TR00 0000 0000 0000 0000 0000 00"
                                   maxlength="32">
                        </div>
                    </div>

                    <!-- Para Birimi -->
                    <div class="mb-6">
                        <label for="currency" class="block text-sm font-semibold text-white mb-2">Para Birimi *</label>
                        <select id="currency" name="currency" required
                                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                            <option value="TRY" selected>TRY - Türk Lirası</option>
                            <option value="USD">USD - Amerikan Doları</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="GBP">GBP - İngiliz Sterlini</option>
                        </select>
                    </div>

                    <!-- Açıklama -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-semibold text-white mb-2">Açıklama</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all resize-none"
                                  placeholder="Hesap hakkında notlar..."></textarea>
                    </div>

                    <!-- Butonlar -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/10">
                        <a href="{{ route('accounting.cash-bank-accounts.index') }}" 
                           class="px-6 py-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white font-medium transition-all">
                            İptal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-medium transition-all shadow-lg shadow-emerald-500/30">
                            <span class="material-symbols-outlined text-[18px] align-middle">save</span>
                            Hesabı Oluştur
                        </button>
                    </div>

                </x-card>
            </form>

        </div>
    </div>

    @push('scripts')
    <script>
        // Hesap tipi değiştiğinde banka alanlarını göster/gizle
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const bankFields = document.getElementById('bank-fields');
                if (this.value === 'bank') {
                    bankFields.classList.remove('hidden');
                } else {
                    bankFields.classList.add('hidden');
                }
            });
        });

        // IBAN formatla
        const ibanInput = document.getElementById('iban');
        if (ibanInput) {
            ibanInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s/g, '').toUpperCase();
                let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
                e.target.value = formatted;
            });
        }
    </script>
    @endpush
</x-app-layout>
