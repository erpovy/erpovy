<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-white tracking-tight">
                    Cari Ekstre
                </h2>
                <p class="text-slate-400 text-sm font-medium mt-1">
                    {{ $contact->name }} - {{ $contact->type === 'customer' ? 'Müşteri' : 'Tedarikçi' }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('accounting.account-transactions.index') }}" 
                   class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold transition-all duration-300 flex items-center gap-2 border border-white/10">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Geri
                </a>
                <a href="{{ route('accounting.account-transactions.create') }}?contact_id={{ $contact->id }}" 
                   class="px-4 py-2 bg-primary hover:bg-primary/80 text-white rounded-xl font-bold transition-all duration-300 flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Yeni Hareket
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-green-500/10 border border-green-500/30 rounded-xl text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Cari Bilgileri -->
            <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Cari Adı</div>
                        <div class="text-lg font-black text-white">{{ $contact->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Vergi No / Dairesi</div>
                        <div class="text-sm font-medium text-slate-300">
                            {{ $contact->tax_number ?? '-' }} / {{ $contact->tax_office ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">İletişim</div>
                        <div class="text-sm font-medium text-slate-300">
                            {{ $contact->phone ?? '-' }}<br>
                            {{ $contact->email ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Adres</div>
                        <div class="text-sm font-medium text-slate-300">
                            {{ $contact->address ?? '-' }}
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Özet Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Toplam Borç -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 rounded-xl bg-red-500/10 text-red-500">
                            <span class="material-symbols-outlined text-[20px]">trending_up</span>
                        </div>
                        <div class="text-red-400 text-xs font-bold bg-red-900/30 px-2 py-1 rounded-lg border border-red-500/30">
                            Borç
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight mb-1">
                        ₺{{ number_format($totalDebit, 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-slate-500 font-medium">Toplam Borç</div>
                </x-card>

                <!-- Toplam Alacak -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 rounded-xl bg-green-500/10 text-green-500">
                            <span class="material-symbols-outlined text-[20px]">trending_down</span>
                        </div>
                        <div class="text-green-400 text-xs font-bold bg-green-900/30 px-2 py-1 rounded-lg border border-green-500/30">
                            Alacak
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight mb-1">
                        ₺{{ number_format($totalCredit, 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-slate-500 font-medium">Toplam Alacak</div>
                </x-card>

                <!-- Bakiye -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 rounded-xl bg-blue-500/10 text-blue-500">
                            <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                        </div>
                        <div class="text-blue-400 text-xs font-bold bg-blue-900/30 px-2 py-1 rounded-lg border border-blue-500/30">
                            Bakiye
                        </div>
                    </div>
                    <div class="text-3xl font-black tracking-tight mb-1 {{ $balance > 0 ? 'text-red-400' : ($balance < 0 ? 'text-green-400' : 'text-white') }}">
                        ₺{{ number_format($balance, 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-slate-500 font-medium">
                        {{ $balance > 0 ? 'Borçlu' : ($balance < 0 ? 'Alacaklı' : 'Dengede') }}
                    </div>
                </x-card>
            </div>

            <!-- Tarih Filtresi -->
            <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                <form method="GET" action="{{ route('accounting.account-transactions.show', $contact->id) }}" class="flex items-end gap-4">
                    <div class="flex-1">
                        <label class="block text-xs text-slate-500 font-bold uppercase tracking-wider mb-2">Başlangıç Tarihi</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                               class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs text-slate-500 font-bold uppercase tracking-wider mb-2">Bitiş Tarihi</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                               class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    <button type="submit" 
                            class="px-6 py-2 bg-primary hover:bg-primary/80 text-white rounded-xl font-bold transition-all duration-300 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                        Filtrele
                    </button>
                    @if($startDate || $endDate)
                        <a href="{{ route('accounting.account-transactions.show', $contact->id) }}" 
                           class="px-6 py-2 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold transition-all duration-300 border border-white/10">
                            Temizle
                        </a>
                    @endif
                </form>
            </x-card>

            <!-- Hareket Listesi -->
            <x-card class="p-0 border-white/10 bg-white/5 overflow-hidden">
                <div class="p-6 border-b border-white/10 bg-white/[0.02]">
                    <h3 class="text-lg font-black text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">receipt_long</span>
                        Hesap Hareketleri
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Tarih</th>
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Açıklama</th>
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Borç</th>
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Alacak</th>
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Bakiye</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="p-4 text-xs text-slate-400 font-mono">
                                    {{ $transaction->transaction_date->format('d.m.Y') }}
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-medium text-white">{{ $transaction->description ?? '-' }}</div>
                                    @if($transaction->invoice_id)
                                        <div class="text-xs text-slate-500">Fatura #{{ $transaction->invoice->invoice_number ?? $transaction->invoice_id }}</div>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    @if($transaction->type === 'debit')
                                        <div class="text-sm font-black text-red-400">₺{{ number_format($transaction->amount, 2, ',', '.') }}</div>
                                    @else
                                        <div class="text-sm font-medium text-slate-600">-</div>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    @if($transaction->type === 'credit')
                                        <div class="text-sm font-black text-green-400">₺{{ number_format($transaction->amount, 2, ',', '.') }}</div>
                                    @else
                                        <div class="text-sm font-medium text-slate-600">-</div>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black {{ $transaction->balance_after > 0 ? 'text-red-400' : ($transaction->balance_after < 0 ? 'text-green-400' : 'text-slate-400') }}">
                                        ₺{{ number_format($transaction->balance_after, 2, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-slate-500 italic">
                                    Seçilen tarih aralığında hareket bulunmuyor.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($transactions->count() > 0)
                        <tfoot class="bg-white/[0.02] border-t-2 border-primary/30">
                            <tr>
                                <td colspan="2" class="p-4 text-sm font-black text-white uppercase">Toplam</td>
                                <td class="p-4 text-right text-sm font-black text-red-400">₺{{ number_format($totalDebit, 2, ',', '.') }}</td>
                                <td class="p-4 text-right text-sm font-black text-green-400">₺{{ number_format($totalCredit, 2, ',', '.') }}</td>
                                <td class="p-4 text-right text-sm font-black {{ $balance > 0 ? 'text-red-400' : ($balance < 0 ? 'text-green-400' : 'text-white') }}">
                                    ₺{{ number_format($balance, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
