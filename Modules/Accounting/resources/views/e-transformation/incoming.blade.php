<x-app-layout>
    <x-slot name="header">
         <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    Gelen Kutusu
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">file_download</span>
                    Tedarikçilerden gelen e-Faturalar
                </p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('accounting.e-transformation.sync-incoming') }}" method="POST">
                    @csrf
                    <button type="submit" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-emerald-500 text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(16,185,129,0.3)]">
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">sync</span>
                            Yeni Faturaları Çek
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-6">
            <!-- Filters -->
            <x-card class="p-4 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('accounting.e-transformation.incoming') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[300px]">
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Fatura no veya firma adı..." 
                                style="padding-left: 40px !important;"
                                class="w-full pr-4 py-2 rounded-xl border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 text-sm focus:border-primary focus:ring-primary transition-all text-gray-900 dark:text-white">
                        </div>
                    </div>
                    <button type="submit" class="rounded-xl px-6 py-2 bg-primary text-white text-sm font-black uppercase tracking-widest shadow-lg shadow-primary/20 transition-all">ARA</button>
                </form>
            </x-card>

            <!-- Table -->
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Fatura No</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Gönderici</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">Tarih</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">Tutar</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">Durum</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right w-24">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse($invoices as $invoice)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="p-4">
                                        <span class="font-mono text-sm font-bold text-primary">{{ $invoice->invoice_number }}</span>
                                        <span class="block text-[10px] text-gray-400 font-mono">{{ $invoice->ettn }}</span>
                                    </td>
                                    <td class="p-4 font-bold text-gray-900 dark:text-white">
                                        {{ $invoice->contact->name }}
                                    </td>
                                    <td class="p-4 text-center text-sm font-medium text-gray-600 dark:text-gray-400">
                                        {{ $invoice->issue_date->format('d.m.Y') }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <span class="text-base font-black text-gray-900 dark:text-white">{{ number_format($invoice->grand_total, 2, ',', '.') }} TL</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-500/10 text-green-400 border border-green-500/20">
                                            {{ $invoice->gib_status_label }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('accounting.e-transformation.show-xml', $invoice->id) }}" class="p-2 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-all" title="XML Görüntüle">
                                                <span class="material-symbols-outlined text-xl">code</span>
                                            </a>
                                            
                                            @if($invoice->status !== 'paid')
                                            <form action="{{ route('accounting.e-transformation.convert-to-purchase', $invoice->id) }}" method="POST" onsubmit="return confirm('Bu fatura muhasebeleştirilecek ve alış kaydı oluşturulacaktır. Emin misiniz?')">
                                                @csrf
                                                <button type="submit" class="p-2 rounded-lg bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500/20 transition-all font-bold" title="Muhasebeleştir">
                                                    <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
                                                </button>
                                            </form>
                                            @else
                                            <span class="p-2 rounded-lg bg-gray-100 text-gray-400 opacity-50 font-bold" title="Muhasebeleştirildi">
                                                <span class="material-symbols-outlined text-xl">done_all</span>
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-12 text-center text-gray-500 dark:text-slate-500 italic">
                                        Gelen fatura kaydı bulunamadı.
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
