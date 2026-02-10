<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-white tracking-tight">Kasa/Banka Hareketleri</h2>
                <p class="text-slate-400 text-sm font-medium mt-1">Nakit ve banka işlemlerini buradan takip edebilirsiniz.</p>
            </div>
            <div class="flex gap-3">
                 <a href="{{ route('accounting.cash-bank-transactions.create', ['type' => 'collection']) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold text-sm transition-colors flex items-center gap-2 shadow-lg shadow-green-600/20">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Tahsilat
                </a>
                 <a href="{{ route('accounting.cash-bank-transactions.create', ['type' => 'payment']) }}" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold text-sm transition-colors flex items-center gap-2 shadow-lg shadow-red-600/20">
                    <span class="material-symbols-outlined text-[18px]">remove_circle</span>
                    Ödeme
                </a>
                <a href="{{ route('accounting.cash-bank-transactions.create', ['type' => 'transfer']) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-colors flex items-center gap-2 shadow-lg shadow-blue-600/20">
                    <span class="material-symbols-outlined text-[18px]">sync_alt</span>
                    Virman
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8">
            <x-card class="p-0 border-white/10 bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02] border-b border-white/5">
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center w-32">Tarih</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Hesap</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">İşlem Tipi</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Açıklama / Cari</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Tutar</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="p-4 text-center">
                                    <div class="text-sm font-bold text-white">{{ $transaction->transaction_date->format('d.m.Y') }}</div>
                                    <div class="text-[10px] text-slate-600 font-bold uppercase">{{ $transaction->transaction_date->diffForHumans() }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-bold text-white">{{ $transaction->account->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $transaction->account->currency ?? 'TRY' }}</div>
                                </td>
                                <td class="p-4">
                                     @php
                                        $typeClasses = [
                                            'income' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                            'expense' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            'transfer' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                        ];
                                        $typeLabels = [
                                            'income' => 'Tahsilat (Giriş)',
                                            'expense' => 'Ödeme (Çıkış)',
                                            'transfer' => 'Virman',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $typeClasses[$transaction->type] ?? 'bg-slate-500/10 text-slate-400' }}">
                                        {{ $typeLabels[$transaction->type] ?? ucfirst($transaction->type) }}
                                    </span>
                                    <div class="mt-1.5 text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ $transaction->method_label }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-medium text-slate-300">{{ $transaction->description }}</div>
                                    @if($transaction->contact)
                                        <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">person</span>
                                            {{ $transaction->contact->name }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black {{ $transaction->type == 'income' ? 'text-green-400' : ($transaction->type == 'expense' ? 'text-red-400' : 'text-blue-400') }}">
                                        {{ $transaction->type == 'expense' ? '-' : ($transaction->type == 'income' ? '+' : '') }}
                                        {{ number_format($transaction->amount, 2, ',', '.') }} ₺
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('accounting.cash-bank-transactions.show', $transaction->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-500 italic">
                                    Henüz kayıt bulunamadı.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                <div class="p-4 border-t border-white/5 bg-white/[0.01]">
                    {{ $transactions->links() }}
                </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
