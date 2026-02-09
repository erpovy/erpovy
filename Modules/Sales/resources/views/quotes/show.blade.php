<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('sales.quotes.index') }}" class="p-2 rounded-lg bg-white/5 text-slate-400 hover:bg-white/10 transition-all">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <div>
                    <h2 class="font-black text-2xl text-white tracking-tight">Teklif: {{ $quote->quote_number }}</h2>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">{{ $quote->contact->name ?? 'Müşteri Bilgisi Yok' }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="px-6 py-2 rounded-xl bg-white/5 text-white border border-white/10 font-bold text-sm hover:bg-white/10 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">print</span>
                    Yazdır
                </button>
                @php
                    $statusConfig = [
                        'accepted' => ['label' => 'KABUL EDİLDİ', 'class' => 'bg-green-500/10 text-green-400 border-green-500/20', 'icon' => 'check_circle'],
                        'sent' => ['label' => 'GÖNDERİLDİ', 'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/20', 'icon' => 'send'],
                        'rejected' => ['label' => 'REDDEDİLDİ', 'class' => 'bg-red-500/10 text-red-400 border-red-500/20', 'icon' => 'cancel'],
                        'expired' => ['label' => 'SÜRESİ DOLDU', 'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/20', 'icon' => 'history'],
                        'draft' => ['label' => 'TASLAK', 'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/20', 'icon' => 'edit_note'],
                    ];
                    $currStatus = $statusConfig[$quote->status] ?? ['label' => strtoupper($quote->status), 'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/20', 'icon' => 'info'];
                @endphp
                <span class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border {{ $currStatus['class'] }} flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">{{ $currStatus['icon'] }}</span>
                    {{ $currStatus['label'] }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto max-w-6xl px-6 space-y-8">
            
            <!-- Compact Customer Row -->
            <x-card class="p-4 border-white/10 bg-white/5 backdrop-blur-2xl flex flex-wrap md:flex-nowrap items-center justify-between gap-6 overflow-hidden relative group">
                <div class="flex items-center gap-4">
                    <div class="p-2.5 rounded-xl bg-primary/10 text-primary border border-primary/20">
                        <span class="material-symbols-outlined text-[24px]">business</span>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest mb-0.5" style="color: #94a3b8 !important;">Müşteri Profili</h4>
                        <div class="flex items-center gap-3">
                            <p class="text-white font-black text-lg leading-tight">{{ $quote->contact->name ?? 'Bilinmeyen Müşteri' }}</p>
                            <span class="px-2 py-0.5 rounded bg-white/5 text-[9px] font-black text-slate-400 border border-white/10 italic">ID: #{{ str_pad($quote->contact_id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center gap-12 flex-1 justify-center px-8 border-x border-white/5">
                    <div class="text-center">
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] mb-1" style="color: #cbd5e1 !important;">Vergi / Kimlik No</p>
                        <p class="text-sm font-black text-white" style="color: #ffffff !important;">{{ $quote->contact->tax_number ?: '---' }}</p>
                    </div>
                    @if($quote->contact->email)
                    <div class="text-center border-l border-white/10 pl-12">
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] mb-1" style="color: #cbd5e1 !important;">E-Posta</p>
                        <p class="text-sm font-black text-white" style="color: #ffffff !important;">{{ $quote->contact->email }}</p>
                    </div>
                    @endif
                    @if($quote->contact->phone)
                    <div class="text-center border-l border-white/10 pl-12">
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] mb-1" style="color: #cbd5e1 !important;">Telefon</p>
                        <p class="text-sm font-black text-white" style="color: #ffffff !important;">{{ $quote->contact->phone }}</p>
                    </div>
                    @endif
                </div>

                <div class="flex-shrink-0">
                    <a href="{{ route('crm.contacts.show', $quote->contact) }}" class="px-6 py-2.5 rounded-xl bg-white/5 text-[10px] font-black text-white hover:bg-white/10 transition-all uppercase tracking-widest border border-white/10 text-center block">Müşteri Detayına Git</a>
                </div>
            </x-card>

            <!-- Kalemler Tablosu (Moved to 2nd Row) -->
            <x-card class="p-0 border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden shadow-2xl">
                <div class="p-6 bg-white/[0.02] border-b border-white/5 flex items-center justify-between">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm text-primary">format_list_bulleted</span>
                        TEKLİF EDİLEN KALEMLER
                    </h4>
                    <span class="px-3 py-1 rounded-full bg-white/5 text-[10px] font-black text-slate-500">{{ $quote->items->count() }} KALEM</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-white/[0.03] text-[10px] font-black uppercase tracking-widest border-b border-white/10" style="color: #94a3b8 !important;">
                            <th class="p-5">Açıklama / Ürün</th>
                            <th class="p-5 text-center w-24">Adet</th>
                            <th class="p-5 text-right w-36">Birim Fiyat</th>
                            <th class="p-5 text-center w-20">KDV</th>
                            <th class="p-5 text-right w-40">Toplam</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($quote->items as $item)
                            <tr class="group hover:bg-white/[0.03] transition-all duration-300">
                                <td class="p-5">
                                    <div class="font-bold text-white group-hover:text-primary transition-colors">{{ $item->description }}</div>
                                    @if($item->product)
                                        <div class="inline-flex items-center gap-1.5 mt-1.5 px-2 py-0.5 rounded-md bg-white/5 border border-white/5">
                                            <span class="material-symbols-outlined text-[12px] text-slate-500">inventory_2</span>
                                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">{{ $item->product->name }}</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-5 text-center">
                                    <span class="text-sm font-bold text-slate-300">{{ number_format($item->quantity, 2, ',', '.') }}</span>
                                </td>
                                <td class="p-5 text-right">
                                    <span class="text-sm font-bold text-slate-300">₺{{ number_format($item->unit_price, 2, ',', '.') }}</span>
                                </td>
                                <td class="p-5 text-center">
                                    <span class="px-2 py-1 rounded-lg bg-primary/5 text-[10px] font-black text-primary border border-primary/10">
                                        %{{ number_format($item->tax_rate, 0) }}
                                    </span>
                                </td>
                                <td class="p-5 text-right">
                                    <span class="text-base font-black text-white">₺{{ number_format($item->total, 2, ',', '.') }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-white/[0.01]">
                                <td colspan="4" class="p-4 text-right text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Ara Toplam</td>
                                <td class="p-4 text-right font-bold text-white">₺{{ number_format($quote->total_amount - $quote->tax_amount, 2, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-white/[0.01]">
                                <td colspan="4" class="p-4 text-right text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Katma Değer Vergisi</td>
                                <td class="p-4 text-right font-medium text-slate-400">₺{{ number_format($quote->tax_amount, 2, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-primary/10 border-t border-primary/20">
                                <td colspan="4" class="p-6 text-right text-xs font-black uppercase tracking-[0.3em]" style="color: #60a5fa !important;">Genel Toplam</td>
                                <td class="p-6 text-right">
                                    <span class="text-4xl font-black" style="color: #ffffff !important; text-shadow: 0 0 20px rgba(96, 165, 250, 0.4);">
                                        ₺{{ number_format($quote->total_amount, 2, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-card>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Sol Kolon: Ana Detaylar -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Teklif Özeti Kartı -->
                    <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden relative group">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-all duration-700"></div>
                        
                        <div class="relative">
                            <div class="flex items-center justify-between mb-10">
                                <h3 class="text-xl font-black text-white flex items-center gap-3">
                                    <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                        <span class="material-symbols-outlined">description</span>
                                    </div>
                                    Teklif Bilgileri
                                </h3>
                                <div class="text-right">
                                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-[0.2em] mb-1">Teklif No</p>
                                    <p class="text-primary font-mono font-black text-xl italic">{{ $quote->quote_number }}</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                                <div>
                                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-[0.2em] mb-2">Tarih</p>
                                    <p class="text-white font-bold text-lg">{{ $quote->date->format('d.m.Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-[0.2em] mb-2">Geçerlilik</p>
                                    <p class="text-white font-bold text-lg">{{ $quote->expiry_date ? $quote->expiry_date->format('d.m.Y') : '---' }}</p>
                                </div>
                                <div class="col-span-2 text-right">
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-2" style="color: #94a3b8 !important;">Toplam Tutar</p>
                                    <p class="font-black text-5xl tracking-tighter" style="color: #ffffff !important;">
                                        ₺{{ number_format($quote->total_amount, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-card>



                    @if($quote->notes)
                    <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-primary/30"></div>
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Notlar & Özel Koşullar</h4>
                        <div class="text-slate-300 text-sm leading-relaxed whitespace-pre-line italic font-medium">
                            {{ $quote->notes }}
                        </div>
                    </x-card>
                    @endif
                </div>

                <!-- Sağ Kolon: Bilgiler ve Aksiyonlar -->
                <div class="space-y-6 lg:sticky lg:top-8">
                    


                    <!-- İşlemler Paneli -->
                    <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Yönetim Paneli</h4>
                        <div class="grid grid-cols-1 gap-3">
                            
                            @if($quote->status == 'draft' || $quote->status == 'sent')
                            <form action="{{ route('sales.quotes.approve', $quote) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-between p-4 rounded-2xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/20 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-xl" style="color: #10b981 !important;">task_alt</span>
                                        <span class="text-[11px] font-black uppercase tracking-widest" style="color: #10b981 !important;">Teklifi Onayla</span>
                                    </div>
                                    <span class="material-symbols-outlined text-sm opacity-0 group-hover:translate-x-1 group-hover:opacity-100 transition-all" style="color: #10b981 !important;">arrow_forward</span>
                                </button>
                            </form>

                            <form action="{{ route('sales.quotes.send', $quote) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-between p-4 rounded-2xl bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/20 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <span class="material-symbols-outlined text-xl" style="color: #3b82f6 !important;">send</span>
                                        <span class="text-[11px] font-black uppercase tracking-widest" style="color: #3b82f6 !important;">E-Posta Gönder</span>
                                    </div>
                                    <span class="material-symbols-outlined text-sm opacity-0 group-hover:translate-x-1 group-hover:opacity-100 transition-all" style="color: #3b82f6 !important;">arrow_forward</span>
                                </button>
                            </form>
                            @endif

                            <a href="{{ route('sales.quotes.edit', $quote) }}" class="w-full flex items-center justify-between p-4 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 transition-all group">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-xl" style="color: #ffffff !important;">edit</span>
                                    <span class="text-[11px] font-black uppercase tracking-widest" style="color: #ffffff !important;">Düzenle</span>
                                </div>
                                <span class="material-symbols-outlined text-sm opacity-0 group-hover:translate-x-1 group-hover:opacity-100 transition-all text-white">arrow_forward</span>
                            </a>

                            <div class="pt-3">
                                <form action="{{ route('sales.quotes.destroy', $quote) }}" method="POST" onsubmit="return confirm('Bu teklifi silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full flex items-center gap-3 p-4 rounded-2xl bg-red-500/5 hover:bg-red-500/10 border border-red-500/10 transition-all">
                                        <span class="material-symbols-outlined text-xl" style="color: #f87171 !important;">delete</span>
                                        <span class="text-[11px] font-black uppercase tracking-widest" style="color: #f87171 !important;">Teklifi Sil</span>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </x-card>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
