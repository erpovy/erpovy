<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('accounting.cash-bank-transactions.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight">İşlem Detayı</h2>
                <p class="text-slate-400 text-sm font-medium">#{{ $transaction->id }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <x-card class="overflow-hidden border-white/10 bg-white/5">
                <div class="p-8 border-b border-white/10 bg-white/[0.02] flex items-center justify-between">
                     <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center
                            {{ $transaction->type === 'income' ? 'bg-green-500/20 text-green-400' : ($transaction->type === 'expense' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400') }}">
                            <span class="material-symbols-outlined text-[28px]">
                                {{ $transaction->type === 'income' ? 'arrow_downward' : ($transaction->type === 'expense' ? 'arrow_upward' : 'sync_alt') }}
                            </span>
                        </div>
                        <div>
                            <div class="text-lg font-black text-white">
                                {{ $transaction->type === 'income' ? 'Tahsilat' : ($transaction->type === 'expense' ? 'Ödeme' : 'Virman') }}
                            </div>
                            <div class="text-sm text-slate-400">{{ $transaction->transaction_date->format('d.m.Y') }}</div>
                        </div>
                     </div>
                     <div class="text-right">
                        <div class="text-3xl font-black {{ $transaction->type === 'income' ? 'text-green-400' : ($transaction->type === 'expense' ? 'text-red-400' : 'text-blue-400') }}">
                            {{ number_format($transaction->amount, 2, ',', '.') }} ₺
                        </div>
                        <div class="text-sm text-slate-500 font-medium uppercase tracking-wider">{{ $transaction->method_label }}</div>
                     </div>
                </div>

                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest block mb-2">Hesap</span>
                            <div class="text-white font-bold text-lg">{{ $transaction->account->name }}</div>
                            <div class="text-slate-400 text-sm">{{ $transaction->account->type === 'cash' ? 'Kasa Hesabı' : 'Banka Hesabı' }}</div>
                        </div>
                         <div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest block mb-2">
                                {{ $transaction->type === 'transfer' ? 'Karşı Hesap' : 'Cari Hesap' }}
                            </span>
                            @if($transaction->type === 'transfer')
                                <div class="text-white font-bold text-lg">{{ $transaction->targetAccount->name ?? '-' }}</div>
                            @elseif($transaction->contact)
                                <div class="text-white font-bold text-lg">{{ $transaction->contact->name }}</div>
                                <div class="text-slate-400 text-sm">{{ $transaction->contact->tax_number ? 'VKN: ' . $transaction->contact->tax_number : '' }}</div>
                            @else
                                <div class="text-slate-500 italic">Bağlantılı cari yok</div>
                            @endif
                        </div>
                    </div>

                    @if($transaction->description)
                    <div class="pt-6 border-t border-white/5">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest block mb-2">Açıklama</span>
                        <div class="text-slate-300">{{ $transaction->description }}</div>
                    </div>
                    @endif

                    @if($transaction->reference_number)
                     <div class="pt-6 border-t border-white/5">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest block mb-2">Referans / Dekont No</span>
                        <div class="font-mono text-slate-300">{{ $transaction->reference_number }}</div>
                    </div>
                    @endif

                    @if($transaction->accountingTransaction)
                    <div class="pt-6 border-t border-white/5 flex items-center justify-between">
                         <div>
                             <span class="text-xs font-bold text-slate-500 uppercase tracking-widest block mb-1">Muhasebe Fişi</span>
                             <div class="text-white font-mono text-sm">{{ $transaction->accountingTransaction->receipt_number }}</div>
                         </div>
                         @if($transaction->accountingTransaction->is_approved)
                            <span class="px-3 py-1 rounded-full bg-green-500/10 text-green-400 text-xs font-bold border border-green-500/20">Onaylı</span>
                         @else
                            <span class="px-3 py-1 rounded-full bg-yellow-500/10 text-yellow-400 text-xs font-bold border border-yellow-500/20">Taslak</span>
                         @endif
                    </div>
                    @endif
                </div>

                <div class="bg-black/20 p-6 border-t border-white/5 text-center">
                    <span class="text-xs text-slate-500">
                        Oluşturulma: {{ $transaction->created_at->format('d.m.Y H:i') }}
                    </span>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
