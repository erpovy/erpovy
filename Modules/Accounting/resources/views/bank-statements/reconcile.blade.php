<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    İşlem Eşleştirme
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">account_tree</span>
                    {{ $bankAccount->bank_name }} - {{ $bankAccount->name }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8">
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 overflow-hidden">
                <form action="{{ route('accounting.bank-statements.process') }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                    <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Tarih</th>
                                    <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Açıklama</th>
                                    <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">Tutar</th>
                                    <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Tip</th>
                                    <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Eşleşen Cari</th>
                                    <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                @foreach($transactions as $index => $tx)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-all group">
                                    <td class="p-4 font-mono text-sm">{{ $tx['date'] }}</td>
                                    <td class="p-4 text-sm text-gray-600 dark:text-slate-400 max-w-md truncate" title="{{ $tx['description'] }}">
                                        {{ $tx['description'] }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <span class="font-black {{ $tx['type'] == 'income' ? 'text-emerald-500' : 'text-rose-500' }}">
                                            {{ $tx['type'] == 'income' ? '+' : '-' }}{{ number_format($tx['amount'], 2, ',', '.') }} {{ $bankAccount->currency }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-tight {{ $tx['type'] == 'income' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                                            {{ $tx['type'] == 'income' ? 'Giriş' : 'Çıkış' }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <select name="transactions[{{ $index }}][contact_id]" class="w-full py-1 rounded-lg border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 text-xs text-gray-900 dark:text-white">
                                            <option value="">Cari Seçiniz (Otomatik Tahmin Yok)</option>
                                            @foreach($contacts as $contact)
                                                <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-4 text-center">
                                        <input type="checkbox" name="transactions[{{ $index }}][process]" value="1" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-gray-50 dark:bg-white/5 border-t border-gray-200 dark:border-white/10 flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Toplam <strong>{{ count($transactions) }}</strong> işlem analiz edildi.
                        </div>
                        <button type="submit" class="px-8 py-3 bg-emerald-500 text-white font-black text-sm uppercase tracking-widest rounded-xl shadow-lg shadow-emerald-500/20 hover:scale-105 active:scale-95 transition-all">
                            Seçili İşlemleri Muhasebeleştir
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
