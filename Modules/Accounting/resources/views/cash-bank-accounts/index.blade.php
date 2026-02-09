<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 via-teal-500/5 to-emerald-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Kasa/Banka Hesapları
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">account_balance</span>
                        Nakit ve Banka Hesaplarınızı Yönetin
                    </p>
                </div>
                <a href="{{ route('accounting.cash-bank-accounts.create') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white text-sm font-medium transition-all shadow-lg shadow-emerald-500/30">
                    <span class="material-symbols-outlined text-[18px] align-middle">add</span>
                    Yeni Hesap
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            @if($accounts->count() > 0)
                <!-- Hesap Kartları -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($accounts as $account)
                        <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:bg-white/10 transition-all group">
                            <div class="flex items-start justify-between mb-4">
                                <div class="p-3 rounded-2xl bg-gradient-to-br 
                                    @if($account->type === 'cash') from-green-500/20 to-emerald-500/20
                                    @else from-blue-500/20 to-cyan-500/20
                                    @endif">
                                    <span class="material-symbols-outlined text-[32px]
                                        @if($account->type === 'cash') text-green-400
                                        @else text-blue-400
                                        @endif">
                                        {{ $account->type === 'cash' ? 'payments' : 'account_balance' }}
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('accounting.cash-bank-accounts.show', $account) }}" 
                                       class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-all"
                                       title="Detaylar">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    <a href="{{ route('accounting.cash-bank-accounts.edit', $account) }}" 
                                       class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-all"
                                       title="Düzenle">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                </div>
                            </div>

                            <h3 class="text-xl font-bold text-white mb-1">{{ $account->name }}</h3>
                            <p class="text-sm text-slate-400 mb-4">
                                {{ $account->type === 'cash' ? '💵 Kasa' : '🏦 Banka' }}
                                @if($account->type === 'bank' && $account->bank_name)
                                    • {{ $account->bank_name }}
                                @endif
                            </p>

                            @if($account->type === 'bank')
                                <div class="space-y-2 mb-4 text-xs text-slate-400">
                                    @if($account->iban)
                                        <p class="font-mono">IBAN: {{ $account->iban }}</p>
                                    @endif
                                    @if($account->account_number)
                                        <p class="font-mono">Hesap No: {{ $account->account_number }}</p>
                                    @endif
                                </div>
                            @endif

                            <div class="pt-4 border-t border-white/10">
                                <p class="text-sm text-slate-400 mb-1">Bakiye</p>
                                <p class="text-3xl font-black
                                    @if($account->balance > 0) text-green-400
                                    @elseif($account->balance < 0) text-red-400
                                    @else text-slate-400
                                    @endif">
                                    {{ number_format($account->balance, 2) }}₺
                                </p>
                            </div>

                            <div class="mt-4 pt-4 border-t border-white/10">
                                <a href="{{ route('accounting.cash-bank-accounts.show', $account) }}" 
                                   class="block w-full text-center px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white text-sm font-medium transition-all">
                                    Hareketleri Gör
                                </a>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            @else
                <!-- Boş Durum -->
                <x-card class="p-12 border-white/10 bg-white/5 backdrop-blur-2xl text-center">
                    <div class="max-w-md mx-auto">
                        <div class="p-4 rounded-full bg-emerald-500/10 inline-block mb-4">
                            <span class="material-symbols-outlined text-[64px] text-emerald-400">account_balance_wallet</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Henüz Kasa/Banka Hesabı Yok</h3>
                        <p class="text-slate-400 mb-6">
                            Nakit ve banka hesaplarınızı takip etmek için ilk hesabınızı oluşturun.
                        </p>
                        <a href="{{ route('accounting.cash-bank-accounts.create') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-medium transition-all shadow-lg shadow-emerald-500/30">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                            İlk Hesabı Oluştur
                        </a>
                    </div>
                </x-card>
            @endif

        </div>
    </div>
</x-app-layout>
