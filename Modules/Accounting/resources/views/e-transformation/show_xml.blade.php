<x-app-layout>
    <x-slot name="header">
         <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    UBL/XML İçeriği
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">code</span>
                    Fatura Veri Yapısı ({{ $invoice->invoice_number }})
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="history.back()" class="rounded-xl px-6 py-2 bg-gray-100 dark:bg-white/10 text-sm font-black uppercase tracking-widest text-gray-700 dark:text-white transition-all">GERİ DÖN</button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-6">
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-slate-900 text-emerald-500 font-mono text-xs overflow-auto max-h-[70vh] shadow-2xl">
                <pre><code>{{ htmlspecialchars($invoice->ubl_xml) }}</code></pre>
            </x-card>

            <div class="flex items-center justify-end gap-3">
                <button class="flex items-center gap-2 rounded-xl bg-gray-800 px-6 py-3 text-sm font-black text-white hover:bg-gray-700 transition-all shadow-xl">
                    <span class="material-symbols-outlined text-xl">download</span>
                    XML DOSYASINI İNDİR
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
