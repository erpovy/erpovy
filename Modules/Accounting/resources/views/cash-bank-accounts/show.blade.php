<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 via-teal-500/5 to-emerald-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        {{ $account->name }}
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">
                            {{ $account->type === 'cash' ? 'payments' : 'account_balance' }}
                        </span>
                        {{ $account->type === 'cash' ? 'Kasa' : 'Banka' }} Hesabı
                        @if($account->type === 'bank' && $account->bank_name)
                            • {{ $account->bank_name }}
                        @endif
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('accounting.cash-bank-transactions.create', ['account_id' => $account->id]) }}" 
                       class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white text-sm font-medium transition-all shadow-lg shadow-emerald-500/30">
                        <span class="material-symbols-outlined text-[18px] align-middle">add</span>
                        Yeni İşlem
                    </a>
                    <a href="{{ route('accounting.cash-bank-accounts.index') }}" 
                       class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white text-sm font-medium transition-all">
                        <span class="material-symbols-outlined text-[18px] align-middle">arrow_back</span>
                        Geri Dön
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Hesap Bilgileri ve Bakiye -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Güncel Bakiye -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl col-span-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br 
                            @if($account->balance > 0) from-green-500/20 to-emerald-500/20
                            @elseif($account->balance < 0) from-red-500/20 to-orange-500/20
                            @else from-slate-500/20 to-gray-500/20
                            @endif">
                            <span class="material-symbols-outlined text-[32px]
                                @if($account->balance > 0) text-green-400
                                @elseif($account->balance < 0) text-red-400
                                @else text-slate-400
                                @endif">
                                account_balance_wallet
                            </span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400 mb-1">Güncel Bakiye</h3>
                    <p class="text-4xl font-black
                        @if($account->balance > 0) text-green-400
                        @elseif($account->balance < 0) text-red-400
                        @else text-slate-400
                        @endif">
                        {{ number_format($account->balance, 2) }}₺
                    </p>
                    <p class="text-xs text-slate-500 mt-2">{{ $account->currency }}</p>
                </x-card>

                <!-- Hesap Detayları -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl col-span-2">
                    <h3 class="text-lg font-bold text-white mb-4">Hesap Bilgileri</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-slate-400 mb-1">Hesap Adı</p>
                            <p class="text-white font-semibold">{{ $account->name }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 mb-1">Hesap Tipi</p>
                            <p class="text-white font-semibold">{{ $account->type === 'cash' ? '💵 Kasa' : '🏦 Banka' }}</p>
                        </div>
                        @if($account->type === 'bank')
                            @if($account->bank_name)
                                <div>
                                    <p class="text-slate-400 mb-1">Banka</p>
                                    <p class="text-white font-semibold">{{ $account->bank_name }}</p>
                                </div>
                            @endif
                            @if($account->branch)
                                <div>
                                    <p class="text-slate-400 mb-1">Şube</p>
                                    <p class="text-white font-semibold">{{ $account->branch }}</p>
                                </div>
                            @endif
                            @if($account->account_number)
                                <div>
                                    <p class="text-slate-400 mb-1">Hesap No</p>
                                    <p class="text-white font-semibold font-mono">{{ $account->account_number }}</p>
                                </div>
                            @endif
                            @if($account->iban)
                                <div class="col-span-2">
                                    <p class="text-slate-400 mb-1">IBAN</p>
                                    <p class="text-white font-semibold font-mono">{{ $account->iban }}</p>
                                </div>
                            @endif
                        @endif
                        @if($account->description)
                            <div class="col-span-2">
                                <p class="text-slate-400 mb-1">Açıklama</p>
                                <p class="text-white">{{ $account->description }}</p>
                            </div>
                        @endif
                    </div>
                </x-card>
            </div>

            <!-- Hesap Hareketleri -->
            <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-400">receipt_long</span>
                        Hesap Hareketleri
                    </h3>
                </div>

                @if($account->transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-400">Tarih</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-400">İşlem Tipi</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-400">Açıklama</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-slate-400">Giriş</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-slate-400">Çıkış</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-slate-400">Bakiye</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $runningBalance = (float) $account->opening_balance; @endphp
                                @foreach($account->transactions()->orderBy('transaction_date')->get() as $transaction)
                                    @php
                                        if ($transaction->type === 'deposit') {
                                            $runningBalance += $transaction->amount;
                                        } else {
                                            $runningBalance -= $transaction->amount;
                                        }
                                    @endphp
                                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                        <td class="py-3 px-4 text-sm text-slate-300">
                                            {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d.m.Y') }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium
                                                @if($transaction->type === 'deposit') bg-green-500/20 text-green-400
                                                @elseif($transaction->type === 'withdrawal') bg-red-500/20 text-red-400
                                                @else bg-blue-500/20 text-blue-400
                                                @endif">
                                                <span class="material-symbols-outlined text-[14px]">
                                                    @if($transaction->type === 'deposit') arrow_downward
                                                    @elseif($transaction->type === 'withdrawal') arrow_upward
                                                    @else swap_horiz
                                                    @endif
                                                </span>
                                                @if($transaction->type === 'deposit') Tahsilat
                                                @elseif($transaction->type === 'withdrawal') Ödeme
                                                @else Virman
                                                @endif
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-white">{{ $transaction->description }}</td>
                                        <td class="py-3 px-4 text-sm font-semibold text-right
                                            @if($transaction->type === 'deposit') text-green-400
                                            @else text-slate-600
                                            @endif">
                                            {{ $transaction->type === 'deposit' ? number_format($transaction->amount, 2) . '₺' : '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm font-semibold text-right
                                            @if($transaction->type === 'withdrawal') text-red-400
                                            @else text-slate-600
                                            @endif">
                                            {{ $transaction->type === 'withdrawal' ? number_format($transaction->amount, 2) . '₺' : '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm font-bold text-right
                                            @if($runningBalance > 0) text-blue-400
                                            @elseif($runningBalance < 0) text-orange-400
                                            @else text-slate-400
                                            @endif">
                                            {{ number_format($runningBalance, 2) }}₺
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 text-slate-400">
                        <span class="material-symbols-outlined text-[64px] opacity-50">inbox</span>
                        <p class="mt-2 text-lg">Henüz işlem kaydı yok</p>
                        <p class="text-sm mt-1">İlk işleminizi oluşturmak için yukarıdaki "Yeni İşlem" butonuna tıklayın.</p>
                    </div>
                @endif
            </x-card>

        </div>
    </div>
</x-app-layout>
