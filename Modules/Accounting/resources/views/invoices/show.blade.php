<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Fatura Detayı: #{{ $invoice->invoice_number }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('accounting.invoices.edit', $invoice->id) }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    Düzenle
                </a>
                </a>
                
                <!-- GIB Send Button -->
                @if($invoice->invoice_scenario == 'EARSIV')
                <div x-data="{ loading: false }" class="inline-block">
                    <form id="gib-form-{{ $invoice->id }}" action="{{ route('accounting.invoices.send-to-gib', $invoice->id) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <button 
                        @click="
                            if(confirm('Fatura GİB Portalı\'na taslak olarak gönderilecek.\n\nŞirket ayarlarındaki GİB bilgilerinizin doğru olduğundan emin olun.\n\nOnaylıyor musunuz?')) { 
                                loading = true; 
                                document.getElementById('gib-form-{{ $invoice->id }}').submit(); 
                            }
                        " 
                        :disabled="loading"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center gap-2 text-sm shadow-lg shadow-indigo-500/20 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined text-lg" x-show="!loading">cloud_upload</span>
                        <span class="material-symbols-outlined text-lg animate-spin" x-show="loading" style="display: none;">sync</span>
                        <span x-text="loading ? 'Gönderiliyor...' : 'GİB\'e Gönder'"></span>
                    </button>
                </div>
                @endif

                <a href="{{ route('accounting.invoices.pdf', $invoice->id) }}" target="_blank" class="bg-slate-700 hover:bg-slate-600 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                    PDF İndir
                </a>
                <a href="{{ route('accounting.invoices.index') }}" class="bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white font-bold py-2 px-4 rounded-lg transition-colors text-sm">
                    Geri Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        <!-- Status & Meta Info -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <x-card class="p-4 bg-gradient-to-br from-green-500/20 to-blue-500/5 border-green-500/20">
                <div class="text-xs text-green-400 font-bold uppercase tracking-widest mb-1">Fatura Durumu</div>
                <div class="text-2xl font-black text-white">
                    {{ $invoice->status_label }}
                </div>
                <div class="mt-2 text-[10px] text-slate-400 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">{{ $invoice->status == 'paid' ? 'check_circle' : 'pending' }}</span>
                    <span>Durum</span>
                </div>
            </x-card>
            <x-card class="p-4 bg-gradient-to-br from-blue-500/20 to-cyan-500/5 border-blue-500/20">
                 <div class="text-xs text-blue-300 font-bold uppercase tracking-widest mb-1">Fatura Tarihi</div>
                <div class="text-2xl font-black text-white">
                    {{ $invoice->issue_date->format('d.m.Y') }}
                </div>
                <div class="mt-2 text-[10px] text-slate-400 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">calendar_today</span>
                    <span>Düzenlenme</span>
                </div>
            </x-card>
            <x-card class="p-4 bg-gradient-to-br from-purple-500/20 to-blue-500/5 border-purple-500/20">
                <div class="text-xs text-purple-300 font-bold uppercase tracking-widest mb-1">GİB Durumu</div>
                <div class="text-2xl font-black text-white">{{ $invoice->gib_status_label }}</div>
                <div class="mt-2 text-[10px] text-slate-400">ETTN: {{ $invoice->ettn ?? '-' }}</div>
            </x-card>
            <x-card class="p-4 bg-gradient-to-br from-emerald-500/20 to-green-500/5 border-emerald-500/20">
                 <div class="text-xs text-emerald-300 font-bold uppercase tracking-widest mb-1">Toplam Tutar</div>
                <div class="text-2xl font-black text-white">
                    {{ number_format($invoice->grand_total, 2) . ' ₺' }}
                </div>
                <div class="mt-2 text-[10px] text-slate-400 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">payments</span>
                    <span>Vergiler Dahil</span>
                </div>
            </x-card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Invoice Items -->
            <div class="lg:col-span-2 space-y-6">
                <x-card class="p-6">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Hizmet ve Ürünler</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-xs text-slate-400 uppercase border-b border-white/5">
                                    <th class="py-3 pl-2">Açıklama / Ürün</th>
                                    <th class="py-3 text-right">Miktar</th>
                                    <th class="py-3 text-right">Birim Fiyat</th>
                                    <th class="py-3 text-right">KDV %</th>
                                    <th class="py-3 text-right pr-2">Toplam</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($invoice->items as $item)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="py-4 pl-2">
                                            <div class="font-medium text-white">{{ $item->description }}</div>
                                            @if($item->product)
                                                <div class="text-xs text-slate-500">{{ $item->product->code }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right text-slate-300">
                                            {{ number_format($item->quantity, 2) }}
                                        </td>
                                        <td class="py-4 text-right text-slate-300 font-mono">
                                            {{ number_format($item->unit_price, 2) }} ₺
                                        </td>
                                        <td class="py-4 text-right text-slate-300">
                                            %{{ number_format($item->tax_rate, 0) }}
                                        </td>
                                        <td class="py-4 text-right text-white font-mono font-bold pr-2">
                                            {{ number_format($item->line_total, 2) }} ₺
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-t border-white/10 bg-white/5">
                                <tr>
                                    <td colspan="4" class="py-3 text-right text-slate-400 font-bold">Ara Toplam:</td>
                                    <td class="py-3 text-right text-slate-200 font-mono pr-2">{{ number_format($invoice->subtotal, 2) }} ₺</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="py-3 text-right text-slate-400 font-bold">KDV Toplam:</td>
                                    <td class="py-3 text-right text-slate-200 font-mono pr-2">{{ number_format($invoice->vat_total, 2) }} ₺</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="py-4 text-right text-white font-black text-lg">GENEL TOPLAM:</td>
                                    <td class="py-4 text-right text-green-400 font-black font-mono text-lg pr-2">{{ number_format($invoice->grand_total, 2) }} ₺</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </x-card>

                @if($invoice->notes)
                <x-card class="p-6">
                    <h3 class="text-sm font-bold text-slate-400 mb-2">Notlar</h3>
                    <p class="text-slate-200 text-sm whitespace-pre-wrap">{{ $invoice->notes }}</p>
                </x-card>
                @endif
            </div>

            <!-- Right Column: Customer Info -->
            <div class="space-y-6">
                <x-card class="p-6">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Müşteri Bilgileri</h3>
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-purple-500/20 flex items-center justify-center text-purple-400 font-bold text-xl">
                            {{ substr($invoice->contact->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-lg leading-tight">{{ $invoice->contact->name }}</h4>
                            <span class="text-xs text-slate-400 block mt-1">Vergi No: {{ $invoice->contact->tax_number ?? '-' }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mt-6">
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <span class="material-symbols-outlined text-[18px] text-slate-500">mail</span>
                            <span>{{ $invoice->contact->email ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <span class="material-symbols-outlined text-[18px] text-slate-500">phone</span>
                            <span>{{ $invoice->contact->phone ?? '-' }}</span>
                        </div>
                        <div class="flex items-start gap-3 text-sm text-slate-300">
                            <span class="material-symbols-outlined text-[18px] text-slate-500 mt-0.5">location_on</span>
                            <span>{{ $invoice->contact->address ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-white/10">
                        <a href="{{ route('crm.contacts.show', $invoice->contact->id) }}" class="text-blue-400 hover:text-blue-300 text-sm font-bold flex items-center gap-1 transition-colors">
                            Müşteri Profiline Git
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </x-card>

                @if($invoice->transaction)
                <x-card class="p-6">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Muhasebe Kaydı</h3>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Fiş No:</span>
                            <span class="text-white font-mono font-bold">{{ $invoice->transaction->receipt_number }}</span>
                        </div>
                         <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Fiş Tipi:</span>
                            <span class="text-white">{{ $invoice->transaction->type }}</span>
                        </div>
                        
                        <a href="{{ route('accounting.transactions.show', $invoice->transaction->id) }}" class="mt-4 w-full text-center bg-white/5 hover:bg-white/10 py-2 rounded-lg text-sm text-slate-300 hover:text-white transition-colors border border-white/10">
                            Fişi Görüntüle
                        </a>
                    </div>
                </x-card>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
