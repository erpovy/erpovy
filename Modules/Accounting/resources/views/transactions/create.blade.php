<x-app-layout>
    <x-slot name="header">
        Yeni Mahsup Fişi
    </x-slot>

    <x-card class="p-6" x-data="journalEntry()">
        <h2 class="text-xl font-bold text-white mb-6">Fiş Detayları</h2>

        <form action="{{ route('accounting.transactions.store') }}" method="POST">
            @csrf
            
            <!-- Header -->
            <!-- Header -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-1">Tarih</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-300 mb-1">Fiş Tipi</label>
                    <select name="type" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" required>
                        <option value="regular">Mahsup Fişi</option>
                        <option value="collection">Tahsil Fişi</option>
                        <option value="payment">Tediye Fişi</option>
                        <option value="opening">Açılış Fişi</option>
                        <option value="closing">Kapanış Fişi</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-300 mb-1">Fiş Açıklaması</label>
                    <input type="text" name="description" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="Örn: Ocak Ayı Kira Ödemesi" required>
                </div>
            </div>

            <!-- Lines -->
            <div class="mb-8 overflow-hidden rounded-lg border border-white/5">
                <table class="min-w-full divide-y divide-white/5">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-bold text-slate-300 uppercase">Hesap Kodu</th>
                            <th class="px-3 py-3 text-left text-xs font-bold text-slate-300 uppercase">Açıklama</th>
                            <th class="px-3 py-3 text-right text-xs font-bold text-slate-300 uppercase">Borç (Debit)</th>
                            <th class="px-3 py-3 text-right text-xs font-bold text-slate-300 uppercase">Alacak (Credit)</th>
                            <th class="px-3 py-3 text-center text-xs font-bold text-slate-300 uppercase">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 bg-transparent">
                        <template x-for="(row, index) in rows" :key="index">
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-3 py-2">
                                    <select :name="'entries['+index+'][account_id]'" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" required>
                                        <option value="" class="bg-slate-900">Hesap Seçin</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" class="bg-slate-900">{{ $account->code }} - {{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" :name="'entries['+index+'][description]'" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.01" x-model="row.debit" :name="'entries['+index+'][debit]'" class="w-full text-right rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" @input="calculateTotals()">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.01" x-model="row.credit" :name="'entries['+index+'][credit]'" class="w-full text-right rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" @input="calculateTotals()">
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <button type="button" @click="removeRow(index)" class="text-red-400 hover:text-red-300 transition-colors" x-show="rows.length > 2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-white/5 font-bold border-t border-white/10">
                        <tr>
                            <td colspan="2" class="px-3 py-3 text-right text-slate-300">TOPLAM:</td>
                            <td class="px-3 py-3 text-right text-white font-mono" x-text="totalDebit.toFixed(2)">0.00</td>
                            <td class="px-3 py-3 text-right text-white font-mono" x-text="totalCredit.toFixed(2)">0.00</td>
                            <td></td>
                        </tr>
                        <tr x-show="Math.abs(totalDebit - totalCredit) > 0.01" class="bg-red-500/10 text-red-400">
                            <td colspan="5" class="px-3 py-2 text-center text-sm border-t border-red-500/20">
                                Dikkat: Fiş bakiyesi eşit değil! (Fark: <span x-text="(totalDebit - totalCredit).toFixed(2)"></span>)
                            </td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="px-4 py-3 bg-white/5 border-t border-white/5">
                    <button type="button" @click="addRow()" class="text-primary-400 hover:text-primary-300 text-sm font-medium flex items-center gap-2">
                        <span>+ Yeni Satır Ekle</span>
                    </button>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-500 disabled:opacity-50 disabled:cursor-not-allowed shadow-neon transition-all" :disabled="Math.abs(totalDebit - totalCredit) > 0.01 || totalDebit == 0">
                    Fişi Kaydet
                </button>
            </div>
        </form>
    </x-card>

    <script>
        function journalEntry() {
            return {
                rows: [
                    { debit: 0, credit: 0 },
                    { debit: 0, credit: 0 }
                ],
                totalDebit: 0,
                totalCredit: 0,
                
                addRow() {
                    this.rows.push({ debit: 0, credit: 0 });
                },
                removeRow(index) {
                    this.rows.splice(index, 1);
                    this.calculateTotals();
                },
                calculateTotals() {
                    this.totalDebit = this.rows.reduce((sum, row) => sum + Number(row.debit || 0), 0);
                    this.totalCredit = this.rows.reduce((sum, row) => sum + Number(row.credit || 0), 0);
                }
            }
        }
    </script>
</x-app-layout>
