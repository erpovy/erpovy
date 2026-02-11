<x-app-layout>
    <x-slot name="header">
        Yeni Mahsup Fişi
    </x-slot>

    <div class="max-w-[95%] mx-auto" x-data="journalEntry()">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Yeni Muhasebe Fişi Oluştur</h1>
                <p class="text-sm text-gray-600 dark:text-slate-400">Yevmiye defterine kayıt işlemek için detaylı fiş girişi yapın.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 rounded-xl bg-primary/10 border border-primary/20 text-primary text-sm font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                    <span x-text="'Denge: ' + (totalDebit - totalCredit).toFixed(2)"></span>
                </div>
            </div>
        </div>

        <form action="{{ route('accounting.transactions.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Fiş Üst Bilgileri -->
                <x-card class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                                Tarih
                            </label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" 
                                   class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                                Fiş Tipi
                            </label>
                            <div class="relative">
                                <select name="type" class="w-full pl-4 pr-10 py-2.5 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none cursor-pointer font-medium" required>
                                    <option value="regular">Mahsup Fişi</option>
                                    <option value="collection">Tahsil Fişi</option>
                                    <option value="payment">Tediye Fişi</option>
                                    <option value="opening">Açılış Fişi</option>
                                    <option value="closing">Kapanış Fişi</option>
                                </select>
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 dark:text-slate-500 text-[20px] pointer-events-none">expand_more</span>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">notes</span>
                                Genel Açıklama
                            </label>
                            <input type="text" name="description" 
                                   class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium" 
                                   placeholder="Örn: Aylık Kira Ödemesi veya Personel Maaşları" required>
                        </div>
                    </div>
                </x-card>

                <!-- Fiş Satırları -->
                <x-card class="overflow-hidden border-2 border-gray-100 dark:border-white/5 shadow-glass">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
                            <thead class="bg-gray-50 dark:bg-white/5">
                                <tr>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-1/4">Hesap Kodu / Adı</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider">Satır Açıklaması</th>
                                    <th class="px-4 py-4 text-right text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-40">Borç (Debit)</th>
                                    <th class="px-4 py-4 text-right text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-40">Alacak (Credit)</th>
                                    <th class="px-4 py-4 text-center text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider w-20"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/5 bg-transparent">
                                <template x-for="(row, index) in rows" :key="index">
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                                        <td class="px-3 py-3">
                                            <div class="relative">
                                                <select :name="'entries['+index+'][account_id]'" 
                                                        class="w-full pl-3 pr-8 py-2 rounded-lg bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 appearance-none transition-all" required>
                                                    <option value="">Hesap Seçin...</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" class="bg-white dark:bg-slate-900">{{ $account->code }} - {{ $account->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="absolute right-2 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-[18px] pointer-events-none">search</span>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3">
                                            <input type="text" :name="'entries['+index+'][description]'" 
                                                   class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium"
                                                   placeholder="Satır açıklaması...">
                                        </td>
                                        <td class="px-3 py-3">
                                            <div class="relative">
                                                <input type="number" step="0.01" x-model.number="row.debit" :name="'entries['+index+'][debit]'" 
                                                       class="w-full text-right px-3 py-2 rounded-lg bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white text-sm font-mono focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
                                                       @input="calculateTotals()"
                                                       placeholder="0.00">
                                            </div>
                                        </td>
                                        <td class="px-3 py-3">
                                            <div class="relative">
                                                <input type="number" step="0.01" x-model.number="row.credit" :name="'entries['+index+'][credit]'" 
                                                       class="w-full text-right px-3 py-2 rounded-lg bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white text-sm font-mono focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
                                                       @input="calculateTotals()"
                                                       placeholder="0.00">
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <button type="button" @click="removeRow(index)" 
                                                    class="p-2 rounded-lg text-red-500 hover:bg-red-500/10 transition-all group-hover:scale-110" 
                                                    x-show="rows.length > 2">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-white/5 font-bold border-t border-gray-200 dark:border-white/10">
                                <tr class="divide-x divide-gray-200 dark:divide-white/5">
                                    <td colspan="2" class="px-6 py-4 text-right text-gray-600 dark:text-slate-400 text-sm">GENEL TOPLAM</td>
                                    <td class="px-4 py-4 text-right text-gray-900 dark:text-white font-mono text-lg" x-text="formatCurrency(totalDebit)">0.00</td>
                                    <td class="px-4 py-4 text-right text-gray-900 dark:text-white font-mono text-lg" x-text="formatCurrency(totalCredit)">0.00</td>
                                    <td></td>
                                </tr>
                                <tr x-show="isUnbalanced()" class="bg-red-500/10 text-red-600 dark:text-red-400 animate-pulse">
                                    <td colspan="5" class="px-6 py-3 text-center text-xs font-bold uppercase tracking-widest border-t border-red-500/20">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">warning</span>
                                            <span>Dikkat: Fiş Dengesi Eşit Değil! Fark: <span x-text="formatCurrency(Math.abs(totalDebit - totalCredit))"></span></span>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        
                        <div class="px-6 py-4 bg-gray-50/50 dark:bg-white/[0.02] border-t border-gray-200 dark:border-white/5">
                            <button type="button" @click="addRow()" 
                                    class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 text-sm font-semibold text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/10 transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px] text-primary">add_circle</span>
                                Yeni Satır Ekle (F2)
                            </button>
                        </div>
                    </div>
                </x-card>

                <!-- Actions -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-white dark:bg-slate-900/40 p-6 rounded-2xl border-2 border-gray-100 dark:border-white/5 backdrop-blur-xl">
                    <div class="flex items-center gap-6">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-widest">Durum</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-2.5 h-2.5 rounded-full" :class="isUnbalanced() ? 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]' : 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]'"></span>
                                <span class="text-xs font-bold" :class="isUnbalanced() ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'" 
                                      x-text="isUnbalanced() ? 'Dengesiz Fiş' : 'Dengeli Fiş'"></span>
                            </div>
                        </div>
                        <div class="h-8 w-px bg-gray-200 dark:bg-white/10"></div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-widest text-right">Eşitlik Farkı</span>
                            <span class="text-sm font-mono mt-1 font-bold text-right" :class="isUnbalanced() ? 'text-red-600 dark:text-white' : 'text-gray-900 dark:text-white'"
                                  x-text="formatCurrency(totalDebit - totalCredit)"></span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <a href="{{ route('accounting.transactions.index') }}" 
                           class="px-6 py-3 rounded-xl border border-gray-300 dark:border-white/10 text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 transition-all font-bold text-sm">
                            Vazgeç
                        </a>
                        <button type="submit" 
                                class="flex items-center gap-3 bg-gradient-to-r from-primary to-blue-600 text-white px-10 py-3 rounded-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all font-bold shadow-lg disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed" 
                                :disabled="isUnbalanced() || totalDebit <= 0">
                            <span class="material-symbols-outlined text-[22px]">spellcheck</span>
                            Fişi Kaydet & Onayla
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function journalEntry() {
            return {
                rows: [
                    { debit: null, credit: null },
                    { debit: null, credit: null }
                ],
                totalDebit: 0,
                totalCredit: 0,
                
                init() {
                    window.addEventListener('keydown', (e) => {
                        if (e.key === 'F2') {
                            e.preventDefault();
                            this.addRow();
                        }
                    });
                },

                addRow() {
                    this.rows.push({ debit: null, credit: null });
                },

                removeRow(index) {
                    if(this.rows.length > 2) {
                        this.rows.splice(index, 1);
                        this.calculateTotals();
                    }
                },

                calculateTotals() {
                    this.totalDebit = this.rows.reduce((sum, row) => sum + (parseFloat(row.debit) || 0), 0);
                    this.totalCredit = this.rows.reduce((sum, row) => sum + (parseFloat(row.credit) || 0), 0);
                },

                isUnbalanced() {
                    return Math.abs(this.totalDebit - this.totalCredit) > 0.009;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('tr-TR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(value);
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</x-app-layout>
