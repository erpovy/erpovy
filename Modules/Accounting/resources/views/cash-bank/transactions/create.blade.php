<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-white tracking-tight">
                    {{ $type === 'collection' ? 'Yeni Tahsilat (Ödeme Al)' : ($type === 'payment' ? 'Yeni Ödeme (Para Çıkışı)' : ($type === 'transfer' ? 'Yeni Virman' : 'Yeni İşlem')) }}
                </h2>
                <p class="text-slate-400 text-sm font-medium mt-1">
                    Kasa veya banka üzerinden finansal işlem kaydı
                </p>
            </div>
            <a href="{{ route('accounting.cash-bank-transactions.index') }}" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold transition-all duration-300 flex items-center gap-2 border border-white/10">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Geri
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-3xl px-6 lg:px-8">
            <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('accounting.cash-bank-transactions.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="transaction_type" value="{{ $type }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- İşlem Tarihi -->
                        <div>
                            <label class="block text-sm text-slate-300 font-bold mb-2">İşlem Tarihi <span class="text-red-400">*</span></label>
                            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        </div>

                        <!-- Ödeme Yöntemi -->
                        <div>
                            <label class="block text-sm text-slate-300 font-bold mb-2">Ödeme Yöntemi <span class="text-red-400">*</span></label>
                            <select name="method" required
                                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                <option value="cash">Nakit</option>
                                <option value="transfer">Havale/EFT</option>
                                <option value="credit_card">Kredi Kartı</option>
                                <option value="check">Çek</option>
                                <option value="other">Diğer</option>
                            </select>
                        </div>
                    </div>

                    @if($type === 'transfer')
                        <!-- Virman Seçenekleri -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm text-slate-300 font-bold mb-2">Kaynak Hesap <span class="text-red-400">*</span></label>
                                <select name="source_account_id" required
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="">Hesap seçiniz...</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} ({{ number_format($account->balance, 2) }}₺)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-300 font-bold mb-2">Hedef Hesap <span class="text-red-400">*</span></label>
                                <select name="target_account_id" required
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="">Hesap seçiniz...</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">
                                            {{ $account->name }} ({{ number_format($account->balance, 2) }}₺)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        <!-- Tahsilat/Ödeme Seçenekleri -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm text-slate-300 font-bold mb-2">Cari Hesap (Müşteri/Tedarikçi) <span class="text-red-400">*</span></label>
                                <select name="contact_id" required
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="">Cari seçiniz...</option>
                                    @foreach($contacts as $contact)
                                        <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-slate-300 font-bold mb-2">Kasa/Banka Hesabı <span class="text-red-400">*</span></label>
                                <select name="cash_bank_account_id" required
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="">Hesap seçiniz...</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} ({{ number_format($account->balance, 2) }}₺)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tutar -->
                        <div>
                            <label class="block text-sm text-slate-300 font-bold mb-2">Tutar (₺) <span class="text-red-400">*</span></label>
                            <input type="number" name="amount" step="0.01" min="0.01" required
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-mono text-lg"
                                   placeholder="0.00">
                        </div>

                        <!-- Referans No -->
                        <div>
                            <label class="block text-sm text-slate-300 font-bold mb-2">Referans/Dekont No</label>
                            <input type="text" name="reference_number"
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="Örn: BNK12345">
                        </div>
                    </div>

                    <!-- Açıklama -->
                    <div>
                        <label class="block text-sm text-slate-300 font-bold mb-2">İşlem Açıklaması</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none"
                                  placeholder="İşlem ile ilgili notlarınız...">{{ $type === 'collection' ? 'Tahsilat kaydı' : ($type === 'payment' ? 'Ödeme kaydı' : '') }}</textarea>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit"
                                class="flex-1 px-6 py-4 bg-primary hover:bg-primary/80 text-white rounded-xl font-bold transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-[24px]">check_circle</span>
                            {{ $type === 'collection' ? 'Tahsilatı Kaydet' : ($type === 'payment' ? 'Ödemeyi Kaydet' : 'İşlemi Kaydet') }}
                        </button>
                    </div>
                </form>
            </x-card>

            <!-- Bilgilendirme -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-blue-400 text-[20px]">info</span>
                        <div>
                            <p class="text-white font-bold text-sm mb-1 uppercase tracking-wider">Otomatik Kayıt</p>
                            <p class="text-slate-400 text-xs leading-relaxed">Bu işlem kaydedildiğinde ilgili cari hesap ve kasa/banka bakiyesi otomatik olarak güncellenecek, muhasebe fişi (journal entry) oluşturulacaktır.</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl">
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-emerald-400 text-[20px]">verified_user</span>
                        <div>
                            <p class="text-white font-bold text-sm mb-1 uppercase tracking-wider">Güvenli İşlem</p>
                            <p class="text-slate-400 text-xs leading-relaxed">İşlem veritabanı loglarına kaydedilir ve mali dönem açık olduğu sürece finansal raporlarınıza anında yansır.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
