<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-white tracking-tight">
                    Cari Hesaplar
                </h2>
                <p class="text-slate-400 text-sm font-medium mt-1">
                    Müşteri ve tedarikçi borç/alacak takibi
                </p>
            </div>
            <a href="{{ route('accounting.account-transactions.create') }}" 
               class="px-6 py-3 bg-primary hover:bg-primary/80 text-white rounded-xl font-bold transition-all duration-300 flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Yeni Hareket
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-xl text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <!-- İstatistik Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Toplam Alacak -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 rounded-xl bg-green-500/10 text-green-500">
                            <span class="material-symbols-outlined text-[24px]">trending_up</span>
                        </div>
                        <div class="text-green-400 text-xs font-bold bg-green-900/30 px-2 py-1 rounded-lg border border-green-500/30">
                            Alacak
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight mb-1">
                        ₺{{ number_format($contacts->filter(fn($c) => $c->calculated_balance < 0)->sum(fn($c) => abs($c->calculated_balance)), 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-slate-500 font-medium">Toplam Alacağımız</div>
                </x-card>

                <!-- Toplam Borç -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 rounded-xl bg-red-500/10 text-red-500">
                            <span class="material-symbols-outlined text-[24px]">trending_down</span>
                        </div>
                        <div class="text-red-400 text-xs font-bold bg-red-900/30 px-2 py-1 rounded-lg border border-red-500/30">
                            Borç
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight mb-1">
                        ₺{{ number_format($contacts->filter(fn($c) => $c->calculated_balance > 0)->sum('calculated_balance'), 2, ',', '.') }}
                    </div>
                    <div class="text-xs text-slate-500 font-medium">Toplam Borcumuz</div>
                </x-card>

                <!-- Toplam Cari Sayısı -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 rounded-xl bg-blue-500/10 text-blue-500">
                            <span class="material-symbols-outlined text-[24px]">groups</span>
                        </div>
                        <div class="text-blue-400 text-xs font-bold bg-blue-900/30 px-2 py-1 rounded-lg border border-blue-500/30">
                            Cari
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white tracking-tight mb-1">
                        {{ $contacts->count() }}
                    </div>
                    <div class="text-xs text-slate-500 font-medium">Aktif Cari Hesap</div>
                </x-card>
            </div>

            <!-- Cari Hesap Listesi -->
            <x-card class="p-0 border-white/10 bg-white/5 overflow-hidden">
                <div class="p-6 border-b border-white/10 bg-white/[0.02]">
                    <h3 class="text-lg font-black text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
                        Cari Hesap Listesi
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Cari Adı</th>
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Tip</th>
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Borç</th>
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Alacak</th>
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Bakiye</th>
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Son Hareket</th>
                                <th class="p-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($contacts as $contact)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="p-4">
                                    <div class="text-sm font-bold text-white">{{ $contact->name }}</div>
                                    @if($contact->tax_number)
                                        <div class="text-xs text-slate-500">VKN: {{ $contact->tax_number }}</div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                        {{ $contact->type === 'customer' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'bg-purple-500/10 text-purple-400 border border-purple-500/20' }}">
                                        {{ $contact->type === 'customer' ? 'Müşteri' : 'Tedarikçi' }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black text-red-400">₺{{ number_format($contact->debit_total, 2, ',', '.') }}</div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black text-green-400">₺{{ number_format($contact->credit_total, 2, ',', '.') }}</div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black {{ $contact->calculated_balance > 0 ? 'text-red-400' : ($contact->calculated_balance < 0 ? 'text-green-400' : 'text-slate-400') }}">
                                        ₺{{ number_format($contact->calculated_balance, 2, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-slate-500">
                                        {{ $contact->calculated_balance > 0 ? 'Borçlu' : ($contact->calculated_balance < 0 ? 'Alacaklı' : 'Dengede') }}
                                    </div>
                                </td>
                                <td class="p-4 text-xs text-slate-500 font-mono">
                                    {{ $contact->last_transaction_date ? \Carbon\Carbon::parse($contact->last_transaction_date)->format('d.m.Y') : '-' }}
                                </td>
                                <td class="p-4 text-center">
                                    <a href="{{ route('accounting.account-transactions.show', $contact->id) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg text-xs font-bold transition-all">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        Ekstre
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-slate-500 italic">
                                    Henüz cari hesap kaydı bulunmuyor.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
