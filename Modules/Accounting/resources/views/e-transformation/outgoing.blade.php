<x-app-layout>
    <x-slot name="header">
         <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    Giden Kutusu
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">file_upload</span>
                    Gönderilen e-Fatura ve e-Arşiv belgeleri
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                    <div class="relative flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">refresh</span>
                        Durum Sorgula
                    </div>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-6">
            <!-- Table -->
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Fatura No</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Müşteri / Alıcı</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">Tarih</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">Tutar</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">Tip</th>
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
                                         <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                            {{ $invoice->e_type_label }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                            {{ $invoice->gib_status_label }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('accounting.invoices.pdf', $invoice->id) }}" target="_blank" class="p-2 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-all font-bold" title="Görüntüle">
                                                <span class="material-symbols-outlined text-xl">visibility</span>
                                            </a>
                                            <a href="{{ route('accounting.e-transformation.check-status', $invoice->id) }}" class="p-2 rounded-lg bg-amber-500/10 text-amber-500 hover:bg-amber-500/20 transition-all font-bold" title="Durum Sorgula">
                                                <span class="material-symbols-outlined text-xl">sync</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-12 text-center text-gray-500 dark:text-slate-500 italic">
                                        Giden belge kaydı bulunamadı.
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
