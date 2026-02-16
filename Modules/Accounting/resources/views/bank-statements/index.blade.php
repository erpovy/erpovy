<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    Banka Entegrasyonu
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">account_balance</span>
                    MT940 Banka Ekstre Aktarımı
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-4xl px-6 lg:px-8">
            <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('accounting.bank-statements.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-black text-gray-700 dark:text-slate-300 uppercase tracking-widest mb-2">Banka Hesabı</label>
                            <select name="bank_account_id" class="w-full rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 text-sm focus:border-primary focus:ring-primary transition-all text-gray-900 dark:text-white" required>
                                <option value="">Hesap Seçiniz</option>
                                @foreach($bankAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->bank_name }} - {{ $account->name }} ({{ $account->currency }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-black text-gray-700 dark:text-slate-300 uppercase tracking-widest mb-2">MT940 Dosyası (.sta, .txt)</label>
                            <input type="file" name="statement_file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all" required>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-8 py-3 bg-primary text-white font-black text-sm uppercase tracking-widest rounded-xl shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined">upload</span>
                            Dosyayı Yükle ve Analiz Et
                        </button>
                    </div>
                </form>
            </x-card>

            <!-- Info Box -->
            <div class="mt-8 p-6 rounded-2xl bg-blue-500/10 border border-blue-500/20">
                <div class="flex items-start gap-4">
                    <span class="material-symbols-outlined text-blue-500">info</span>
                    <div>
                        <h4 class="font-bold text-blue-500 mb-1">MT940 Hakkında</h4>
                        <p class="text-sm text-blue-400 leading-relaxed">
                            MT940, uluslararası banka ekstre standardıdır. Bankanızın internet şubesinden bu formatta alacağınız dosyaları saniyeler içinde Erpovy'e aktarabilir, cari hesaplarınızla otomatik eşleştirebilirsiniz.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
