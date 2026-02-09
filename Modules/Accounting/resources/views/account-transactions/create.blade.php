<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-white tracking-tight">
                    Yeni Cari Hareket
                </h2>
                <p class="text-slate-400 text-sm font-medium mt-1">
                    Manuel cari hesap hareketi girişi
                </p>
            </div>
            <a href="{{ route('accounting.account-transactions.index') }}" 
               class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold transition-all duration-300 flex items-center gap-2 border border-white/10">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Geri
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-3xl px-6 lg:px-8">
            
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl">
                <form method="POST" action="{{ route('accounting.account-transactions.store') }}" class="space-y-6">
                    @csrf

                    <!-- Cari Seçimi -->
                    <div>
                        <label class="block text-sm text-slate-300 font-bold mb-2">
                            Cari Hesap <span class="text-red-400">*</span>
                        </label>
                        <select name="contact_id" required
                                class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            <option value="">Cari seçiniz...</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}" {{ old('contact_id', request('contact_id')) == $contact->id ? 'selected' : '' }}>
                                    {{ $contact->name }} - {{ $contact->type === 'customer' ? 'Müşteri' : 'Tedarikçi' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-slate-500">Hareket yapılacak müşteri veya tedarikçiyi seçin</p>
                    </div>

                    <!-- Hareket Tipi -->
                    <div>
                        <label class="block text-sm text-slate-300 font-bold mb-2">
                            Hareket Tipi <span class="text-red-400">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="debit" {{ old('type') === 'debit' ? 'checked' : '' }} required
                                       class="peer sr-only">
                                <div class="p-4 border-2 border-white/10 rounded-xl bg-white/5 peer-checked:border-red-500 peer-checked:bg-red-500/10 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 rounded-lg bg-red-500/10 text-red-500">
                                            <span class="material-symbols-outlined text-[24px]">trending_up</span>
                                        </div>
                                        <div>
                                            <div class="font-black text-white">Borç</div>
                                            <div class="text-xs text-slate-500">Müşteri borçlanır</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="type" value="credit" {{ old('type') === 'credit' ? 'checked' : '' }} required
                                       class="peer sr-only">
                                <div class="p-4 border-2 border-white/10 rounded-xl bg-white/5 peer-checked:border-green-500 peer-checked:bg-green-500/10 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 rounded-lg bg-green-500/10 text-green-500">
                                            <span class="material-symbols-outlined text-[24px]">trending_down</span>
                                        </div>
                                        <div>
                                            <div class="font-black text-white">Alacak</div>
                                            <div class="text-xs text-slate-500">Müşteri alacaklanır</div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Tutar -->
                    <div>
                        <label class="block text-sm text-slate-300 font-bold mb-2">
                            Tutar (₺) <span class="text-red-400">*</span>
                        </label>
                        <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}" required
                               class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="0.00">
                    </div>

                    <!-- Tarih -->
                    <div>
                        <label class="block text-sm text-slate-300 font-bold mb-2">
                            İşlem Tarihi <span class="text-red-400">*</span>
                        </label>
                        <input type="date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" required
                               class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>

                    <!-- Açıklama -->
                    <div>
                        <label class="block text-sm text-slate-300 font-bold mb-2">
                            Açıklama
                        </label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none"
                                  placeholder="Hareket açıklaması (opsiyonel)">{{ old('description') }}</textarea>
                    </div>

                    <!-- Butonlar -->
                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-primary hover:bg-primary/80 text-white rounded-xl font-bold transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            Hareketi Kaydet
                        </button>
                        <a href="{{ route('accounting.account-transactions.index') }}"
                           class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold transition-all duration-300 border border-white/10">
                            İptal
                        </a>
                    </div>
                </form>
            </x-card>

            <!-- Bilgilendirme -->
            <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/30 rounded-xl text-blue-400 text-sm">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-[20px] mt-0.5">info</span>
                    <div>
                        <strong>Bilgi:</strong> Manuel cari hareket girişi, fatura dışı tahsilat, ödeme veya düzeltme işlemleri için kullanılır.
                        Fatura oluşturduğunuzda cari hareket otomatik olarak kaydedilir.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
