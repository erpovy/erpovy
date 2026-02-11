<x-app-layout>
    <div class="min-h-screen bg-[#0f172a] text-slate-200 font-sans selection:bg-primary-500/30">
        <form action="{{ route('accounting.invoices.update', $invoice->id) }}" method="POST"
              x-data="invoiceForm({{ json_encode($invoice->items) }}, '{{ $invoice->issue_date->format('Y-m-d') }}', '{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '' }}', '{{ $invoice->invoice_scenario }}')"
              class="max-w-[1600px] mx-auto p-4 md:p-8">
            @csrf
            @method('PUT')

            <!-- Sticky Header -->
            <div class="sticky top-0 z-[100] mb-8 py-4 bg-[#0f172a]/80 backdrop-blur-xl border-b border-white/5 -mx-4 md:-mx-8 px-4 md:px-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-slate-400 text-sm mb-1">
                            <a href="{{ route('accounting.invoices.index') }}" class="hover:text-white transition-colors">Faturalar</a>
                            <span class="material-symbols-outlined text-xs">chevron_right</span>
                            <span>Düzenle</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                            <span class="bg-gradient-to-br from-primary-400 to-indigo-500 bg-clip-text text-transparent">#{{ $invoice->invoice_number }}</span>
                            <span class="text-slate-500">Faturaları Düzenle</span>
                        </h1>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('accounting.invoices.show', $invoice->id) }}" 
                           class="px-5 py-2.5 rounded-xl bg-white/5 backdrop-blur-md border border-white/10 text-slate-300 hover:bg-white/10 hover:border-white/20 transition-all font-semibold text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">close</span>
                            <span>Vazgeç</span>
                        </a>
                        <button type="submit" 
                                class="bg-gradient-to-r from-primary-600 to-indigo-600 text-white px-7 py-2.5 rounded-xl hover:shadow-[0_0_25px_rgba(79,70,229,0.4)] transition-all font-bold text-sm flex items-center gap-2 active:scale-95">
                            <span class="material-symbols-outlined text-lg">save_as</span>
                            <span>Değişiklikleri Kaydet</span>
                        </button>
                    </div>
                </div>
            </div>

            @if(session('error') || $errors->any())
                <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
                    <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-2xl backdrop-blur-md">
                        <div class="flex items-start gap-4">
                            <div class="bg-red-500/20 p-2 rounded-xl text-red-500">
                                <span class="material-symbols-outlined text-2xl">error</span>
                            </div>
                            <div>
                                <h3 class="text-red-400 font-bold mb-1">Bir Hata Oluştu</h3>
                                @if(session('error'))
                                    <p class="text-red-300/80 text-sm italic">{{ session('error') }}</p>
                                @endif
                                @if($errors->any())
                                    <ul class="list-disc list-inside text-xs text-red-300/70 mt-2 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Panel: Main Form & Items -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Section: General Info -->
                    <div class="group border border-white/5 rounded-[2.5rem] bg-slate-900/40 backdrop-blur-3xl p-8 transition-all hover:bg-slate-900/60 shadow-2xl">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="p-3 rounded-2xl bg-primary-500/10 text-primary-400 ring-1 ring-primary-500/20 group-hover:ring-primary-500/40 transition-all">
                                <span class="material-symbols-outlined text-2xl">info</span>
                            </div>
                            <h2 class="text-xl font-black text-white tracking-tight">Temel Bilgiler</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-500 uppercase tracking-widest px-1">Müşteri / Cari</label>
                                <div class="relative group/input">
                                    <select name="contact_id" class="w-full h-12 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-white px-4 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all appearance-none cursor-pointer">
                                        <option value="">-- Müşteri Seçin --</option>
                                        @foreach($contacts as $contact)
                                            <option value="{{ $contact->id }}" {{ $invoice->contact_id == $contact->id ? 'selected' : '' }}>
                                                {{ $contact->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                        <span class="material-symbols-outlined text-lg">expand_more</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-500 uppercase tracking-widest px-1">Fatura Tipi</label>
                                <div class="relative group/input">
                                    <select name="invoice_type" class="w-full h-12 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-white px-4 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all appearance-none cursor-pointer">
                                        <option value="SATIS" {{ $invoice->invoice_type == 'SATIS' ? 'selected' : '' }}>SATIŞ FATURASI</option>
                                        <option value="IADE" {{ $invoice->invoice_type == 'IADE' ? 'selected' : '' }}>İADE FATURASI</option>
                                        <option value="TEVKIFAT" {{ $invoice->invoice_type == 'TEVKIFAT' ? 'selected' : '' }}>TEVKİFAT FATURASI</option>
                                        <option value="ISTISNA" {{ $invoice->invoice_type == 'ISTISNA' ? 'selected' : '' }}>İSTİSNA FATURASI</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                        <span class="material-symbols-outlined text-lg">swap_horiz</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-500 uppercase tracking-widest px-1">Düzenleme Tarihi</label>
                                <div class="relative group/input">
                                    <input type="date" name="issue_date" x-model="issueDate" @change="updateDueDate()" 
                                           class="w-full h-12 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-white px-4 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all custom-calendar-icon">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-xs font-black text-slate-500 uppercase tracking-widest px-1">Vade Tarihi</label>
                                <div class="relative group/input">
                                    <input type="date" name="due_date" x-model="dueDate" 
                                           class="w-full h-12 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-white px-4 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all custom-calendar-icon">
                                </div>
                            </div>
                        </div>

                        <!-- Addon: e-Invoice / Address Detail -->
                        <div x-show="invoiceScenario == 'EARSIV'" x-collapse class="mt-8 pt-8 border-t border-white/5">
                            <div class="flex items-center gap-3 mb-6">
                                <span class="material-symbols-outlined text-primary-400">local_shipping</span>
                                <span class="text-sm font-bold text-white uppercase tracking-wider">Sevk ve Alıcı Bilgileri</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-black text-slate-500 uppercase tracking-widest px-1">Tam Adres</label>
                                    <textarea name="receiver_info[address]" rows="2" 
                                              class="w-full rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-white p-4 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all placeholder:text-slate-400"
                                              placeholder="Sevk adresini girin...">{{ $invoice->receiver_info['address'] ?? '' }}</textarea>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest px-1">Şehir</label>
                                        <input type="text" name="receiver_info[city]" value="{{ $invoice->receiver_info['city'] ?? '' }}" placeholder="Ankara"
                                               class="w-full h-12 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-white px-4 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all placeholder:text-slate-400">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-xs font-black text-slate-500 uppercase tracking-widest px-1">İlçe</label>
                                        <input type="text" name="receiver_info[district]" value="{{ $invoice->receiver_info['district'] ?? '' }}" placeholder="Çankaya"
                                               class="w-full h-12 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 text-white px-4 focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all placeholder:text-slate-400">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Items -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-6 bg-primary-500 rounded-full"></div>
                                <h2 class="text-xl font-black text-white tracking-tight italic">Hizmet ve Ürünler</h2>
                                <span class="px-2 py-0.5 rounded-md bg-slate-800 text-[10px] font-black text-slate-400 uppercase" x-text="items.length + ' KALEM'"></span>
                            </div>
                            <button type="button" @click="addItem()" 
                                    class="group/btn flex items-center gap-2 bg-primary-500/10 hover:bg-primary-500 text-primary-400 hover:text-white px-4 py-2 rounded-xl transition-all ring-1 ring-primary-500/30 hover:ring-primary-500">
                                <span class="material-symbols-outlined text-lg transition-transform group-hover/btn:rotate-90">add</span>
                                <span class="text-sm font-bold">Yeni Satır</span>
                            </button>
                        </div>

                        <!-- Table Header (Visible on MD+) -->
                        <div class="hidden md:grid px-10 mb-2" style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 8px; min-width: 900px;">
                            <div style="grid-column: span 4 / span 4;" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Ürün / Hizmet Tanımı</div>
                            <div style="grid-column: span 2 / span 2;" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Seçim</div>
                            <div style="grid-column: span 1 / span 1;" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">Miktar</div>
                            <div style="grid-column: span 2 / span 2;" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">Birim Fiyat</div>
                            <div style="grid-column: span 1 / span 1;" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">İskonto</div>
                            <div style="grid-column: span 1 / span 1;" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">KDV</div>
                            <div style="grid-column: span 1 / span 1;" class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right pr-2">Toplam</div>
                        </div>

                        <div class="space-y-2 relative overflow-x-auto custom-scrollbar pb-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="group/item relative border border-white/10 rounded-2xl bg-slate-900/40 backdrop-blur-2xl p-4 transition-all hover:bg-slate-900/60 hover:border-white/20 animate-in fade-in slide-in-from-bottom-2 duration-300"
                                     :style="'z-index: ' + (100 - index)">
                                    
                                    <!-- Delete Button (Top-Right Floating) -->
                                    <button type="button" @click="removeItem(index)" 
                                            class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500/10 text-red-500 border border-red-500/20 backdrop-blur-md items-center justify-center transition-all opacity-0 group-hover/item:opacity-100 group-hover/item:visible invisible hover:bg-red-500 hover:text-white flex z-50">
                                        <span class="material-symbols-outlined text-sm uppercase">close</span>
                                    </button>

                                    <div class="px-2 py-3" style="display: grid; grid-template-columns: repeat(12, 1fr); gap: 8px; min-width: 900px; align-items: center;">
                                        
                                        <!-- Product Selection -->
                                        <div style="grid-column: span 4 / span 4;">
                                            <input type="text" 
                                                   :name="'items['+index+'][description]'" 
                                                   x-model="item.description" 
                                                   placeholder="Açıklama girin..." 
                                                   class="w-full h-10 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl text-white px-4 text-xs focus:ring-1 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all placeholder:text-slate-400">
                                        </div>

                                        <!-- Specialized Product Select -->
                                        <div style="grid-column: span 2 / span 2;" class="relative">
                                            <button type="button" 
                                                    @click="activeDropdown = (activeDropdown === index ? null : index); productSearch = ''" 
                                                    class="w-full h-10 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-3 text-[10px] font-bold flex justify-between items-center transition-all hover:border-primary-500/30 hover:bg-white/10 group/sel"
                                                    :class="item.product_id ? 'text-primary-400' : 'text-slate-400'">
                                                <span class="truncate pr-1" x-text="item.product_id ? 'Seçildi' : 'Envanterden Seç'"></span>
                                                <span class="material-symbols-outlined text-sm group-hover/sel:translate-y-0.5 transition-transform" :class="activeDropdown === index ? 'rotate-180' : ''">expand_more</span>
                                            </button>

                                            <!-- Absolute Portal -->
                                            <div x-show="activeDropdown === index" 
                                                 x-cloak
                                                 @click.away="activeDropdown = null" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                 class="absolute left-0 right-0 top-full mt-2 z-[200] bg-[#0f172a] border border-white/10 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)] backdrop-blur-3xl overflow-hidden ring-1 ring-white/10">
                                                    <div class="p-3 bg-slate-900/50 border-b border-white/5">
                                                        <div class="relative">
                                                            <input type="text" 
                                                                   x-model="productSearch" 
                                                                   placeholder="Hızlı ara..." 
                                                                   @click.stop
                                                                   x-init="$el.focus()"
                                                                   class="w-full h-9 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl px-10 text-[10px] text-white placeholder:text-slate-400 focus:ring-primary-500 focus:border-primary-500/30 focus:bg-white/10 transition-all">
                                                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-600 text-lg">search</span>
                                                        </div>
                                                    </div>
                                                    <div class="max-h-[250px] overflow-y-auto overflow-x-hidden custom-scrollbar py-1">
                                                        <template x-for="p in filteredProducts" :key="p.id">
                                                            <button type="button" 
                                                                    @click="selectProduct(index, p.id, p.name, p.price)" 
                                                                    class="w-full text-left px-4 py-2 hover:bg-primary-500/10 flex justify-between items-center group/opt transition-all border-b border-white/5 last:border-0 relative">
                                                                <div class="flex flex-col">
                                                                    <span class="text-xs font-bold text-slate-300 group-hover/opt:text-white" x-text="p.name"></span>
                                                                    <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest mt-0.5">Stokta</span>
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-xs font-black text-primary-400 group-hover/opt:text-primary-300" x-text="parseFloat(p.price).toLocaleString('tr-TR', {minimumFractionDigits: 2}) + ' ₺'"></span>
                                                                </div>
                                                            </button>
                                                        </template>
                                                    </div>
                                            </div>
                                            <input type="hidden" :name="'items['+index+'][product_id]'" x-model="item.product_id">
                                            <input type="hidden" :name="'items['+index+'][id]'" x-model="item.id">
                                        </div>

                                        <!-- Quantity -->
                                        <div style="grid-column: span 1 / span 1;">
                                            <input type="number" step="0.01" x-model="item.quantity" @input="calculateTotal()" :name="'items['+index+'][quantity]'"
                                                   class="w-full h-10 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl text-white text-center font-bold text-xs focus:ring-1 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all no-spinner appearance-none">
                                        </div>

                                        <!-- Unit Price -->
                                        <div style="grid-column: span 2 / span 2;" class="relative group/price">
                                            <input type="number" step="0.01" x-model="item.unit_price" @input="calculateTotal()" :name="'items['+index+'][unit_price]'"
                                                   class="w-full h-10 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl text-white text-right pr-8 font-mono font-bold text-xs focus:ring-1 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all placeholder:text-slate-400">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-600 font-bold text-[10px] pointer-events-none group-hover/price:text-slate-400 transition-colors italic">₺</span>
                                        </div>

                                        <!-- Discount -->
                                        <div style="grid-column: span 1 / span 1;">
                                            <input type="number" step="0.01" x-model="item.discount" @input="calculateTotal()" :name="'items['+index+'][discount]'"
                                                   class="w-full h-10 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl text-white text-center font-bold text-xs focus:ring-1 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all placeholder:text-slate-400" placeholder="0.00">
                                        </div>

                                        <!-- Tax Rate -->
                                        <div style="grid-column: span 1 / span 1;">
                                            <select x-model="item.tax_rate" @change="calculateTotal()" :name="'items['+index+'][tax_rate]'"
                                                    class="w-full h-10 bg-white/5 backdrop-blur-md border border-white/10 rounded-xl text-white text-center font-bold text-xs appearance-none cursor-pointer focus:ring-1 focus:ring-primary-500/50 focus:border-primary-500/30 focus:bg-white/10 transition-all px-0">
                                                @foreach($vat_rates as $rate)
                                                    <option value="{{ (int)$rate->rate }}">%{{ (int)$rate->rate }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Line Total -->
                                        <div style="grid-column: span 1 / span 1;">
                                            <div class="h-10 flex items-center justify-end pr-3 bg-primary-500/5 rounded-xl border border-primary-500/20">
                                                <span class="text-xs font-black text-white font-mono" x-text="formatMoney(item.line_total)"></span>
                                                <span class="text-[9px] text-primary-500 font-black ml-1 uppercase italic">₺</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <!-- Empty State -->
                            <div x-show="items.length === 0" class="py-20 flex flex-col items-center justify-center border-2 border-dashed border-white/5 rounded-[3rem] bg-slate-950/20">
                                <div class="w-20 h-20 rounded-full bg-slate-900 flex items-center justify-center mb-4 ring-8 ring-slate-950/50 shadow-inner">
                                    <span class="material-symbols-outlined text-4xl text-slate-700">receipt_long</span>
                                </div>
                                <h3 class="text-slate-300 font-black tracking-tight">Fatura İçeriği Boş</h3>
                                <p class="text-slate-600 text-sm mt-1 mb-6">İşlem yapabilmek için en az bir kalem ürün veya hizmet eklemelisiniz.</p>
                                <button type="button" @click="addItem()" class="bg-white text-black px-6 py-2.5 rounded-full font-black text-sm hover:scale-105 active:scale-95 transition-all shadow-[0_10px_30px_rgba(255,255,255,0.1)]">
                                    HEMEN SATIR EKLE
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Sidebar Summary & Actions -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-[120px]">
                    
                    <!-- Summary Card -->
                    <div class="border border-white/10 rounded-[2.5rem] bg-slate-900/60 backdrop-blur-3xl overflow-hidden shadow-2xl ring-1 ring-white/5">
                        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-8 border-b border-white/5">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">Fatura Özeti</span>
                                <div class="px-2 py-1 rounded bg-orange-500/10 text-orange-500 text-[10px] font-black border border-orange-500/20">TASLAK</div>
                            </div>
                            <h3 class="text-3xl font-black text-white italic tracking-tighter">Genel Toplam</h3>
                            <div class="mt-4 flex items-baseline gap-2">
                                <span class="text-5xl font-black text-white font-mono tracking-tighter leading-none" x-text="formatMoney(grandTotal)"></span>
                                <span class="text-2xl font-black text-primary-500 italic">₺</span>
                            </div>
                        </div>
                        
                        <div class="p-8 pb-4 space-y-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between group/row">
                                    <span class="text-sm font-bold text-slate-500 group-hover/row:text-slate-400 transition-colors">Ara Toplam</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-black text-slate-300 font-mono" x-text="formatMoney(subTotal)"></span>
                                        <span class="text-xs text-slate-600 font-bold italic uppercase">TRY</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between group/row">
                                    <span class="text-sm font-bold text-slate-500 group-hover/row:text-slate-400 transition-colors">Toplam İskonto</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-black text-slate-300 font-mono" x-text="formatMoney(items.reduce((sum, i) => sum + (parseFloat(i.discount) || 0), 0))"></span>
                                        <span class="text-xs text-slate-600 font-bold italic uppercase">TRY</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between group/row">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-slate-500 group-hover/row:text-slate-400 transition-colors">Vergi Yükü (KDV)</span>
                                        <span class="px-1.5 py-0.5 rounded bg-primary-500/10 text-primary-500 text-[9px] font-black">DETAY</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-primary-400">
                                        <span class="text-sm font-black font-mono tracking-tight">+ <span x-text="formatMoney(taxTotal)"></span></span>
                                        <span class="text-xs font-bold italic uppercase">TRY</span>
                                    </div>
                                </div>
                            </div>

                            <div class="h-px bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>

                            <!-- Footer Section inside Summary -->
                            <div class="pt-2 italic">
                                <p class="text-[10px] text-slate-600 leading-relaxed font-medium">Bu fatura e-arşiv senaryosu ile düzenlenmektedir. Kayıt sonrası GİB portalına gönderim için kuyruğa alınacaktır.</p>
                            </div>
                        </div>

                        <!-- Secondary Actions -->
                        <div class="p-4 bg-slate-950/50 border-t border-white/5 grid grid-cols-2 gap-2">
                            <button type="button" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-slate-900 border border-white/5 hover:bg-slate-800 transition-all group/sub">
                                <span class="material-symbols-outlined text-xl text-slate-500 group-hover/sub:text-primary-400 mb-1">print</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Önizleme</span>
                            </button>
                            <button type="button" class="flex flex-col items-center justify-center p-3 rounded-2xl bg-slate-900 border border-white/5 hover:bg-slate-800 transition-all group/sub">
                                <span class="material-symbols-outlined text-xl text-slate-500 group-hover/sub:text-primary-400 mb-1">download</span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Taslak İndir</span>
                            </button>
                        </div>
                    </div>

                    <!-- Additional Settings Card -->
                    <div class="border border-white/5 rounded-[2rem] bg-slate-900/40 backdrop-blur-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Fatura Ayarları</h4>
                            <span class="material-symbols-outlined text-slate-700 text-lg">settings</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-950/40 border border-white/5">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-white tracking-tight">Fatura Senaryosu</span>
                                    <span class="text-[10px] text-slate-500 uppercase font-black italic" x-text="invoiceScenario == 'EARSIV' ? 'Elektronik Arşiv' : 'Kağıt / Matbu'"></span>
                                </div>
                                <select name="invoice_scenario" x-model="invoiceScenario" class="opacity-0 absolute w-full h-12 cursor-pointer z-10">
                                    <option value="EARSIV">e-Arşiv</option>
                                    <option value="KAGIT">Kağıt</option>
                                </select>
                                <div class="w-8 h-8 rounded-full bg-slate-900 flex items-center justify-center text-slate-500 border border-white/10 ring-1 ring-white/5">
                                    <span class="material-symbols-outlined text-lg">edit_note</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Extra Styles for Perfection -->
    <style>
        .custom-calendar-icon::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.2;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }
        .custom-calendar-icon:hover::-webkit-calendar-picker-indicator {
            opacity: 0.6;
        }
        .no-spinner::-webkit-inner-spin-button, 
        .no-spinner::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.3);
        }
    </style>

    <script>
        function invoiceForm(existingItems = [], initialIssueDate = '', initialDueDate = '', initialScenario = 'EARSIV') {
            let initialItems = [];
            
            if (existingItems && existingItems.length > 0) {
                initialItems = existingItems.map(item => ({
                    id: item.id,
                    product_id: item.product_id,
                    description: item.description,
                    quantity: parseFloat(item.quantity) || 1,
                    unit_price: parseFloat(item.unit_price) || 0,
                    discount: parseFloat(item.discount || 0),
                    tax_rate: parseFloat(item.tax_rate) || 20,
                    line_total: parseFloat(item.total) || 0 // Use 'total' from DB if available
                }));
            } else {
                initialItems = [
                    { id: null, product_id: '', description: '', quantity: 1, unit_price: 0, discount: 0, tax_rate: 20, line_total: 0 }
                ];
            }

            return {
                items: initialItems,
                subTotal: 0,
                taxTotal: 0,
                grandTotal: 0,
                
                activeDropdown: null,
                productSearch: '',
                
                issueDate: initialIssueDate,
                dueDate: initialDueDate || '{{ date('Y-m-d', strtotime('+7 days')) }}',
                invoiceScenario: initialScenario,
                allProducts: [
                    @foreach($products as $product)
                        { id: '{{ $product->id }}', name: '{{ addslashes($product->name) }}', price: '{{ $product->sale_price }}' },
                    @endforeach
                ],

                get filteredProducts() {
                    if (!this.productSearch) return this.allProducts;
                    const search = this.productSearch.toLowerCase();
                    return this.allProducts.filter(p => p.name.toLowerCase().includes(search));
                },

                init() {
                    this.calculateTotal();
                },

                updateDueDate() {
                    if(this.issueDate) {
                        let date = new Date(this.issueDate);
                        date.setDate(date.getDate() + 7);
                        this.dueDate = date.toISOString().slice(0, 10);
                    }
                },

                addItem() {
                    this.items.push({ id: null, product_id: '', description: '', quantity: 1, unit_price: 0, discount: 0, tax_rate: 20, line_total: 0 });
                },

                removeItem(index) {
                    if (this.items.length > 0) { // Changed from > 1 to > 0 to allow removing the last item
                        this.items.splice(index, 1);
                        this.calculateTotal();
                    }
                },

                selectProduct(index, prodId, prodName, prodPrice) {
                    this.items[index].product_id = prodId;
                    this.items[index].description = prodName;
                    this.items[index].unit_price = parseFloat(prodPrice);
                    this.activeDropdown = null;
                    this.productSearch = '';
                    this.calculateTotal();
                },

                calculateTotal() {
                    this.subTotal = 0;
                    this.taxTotal = 0;
                    this.grandTotal = 0;

                    this.items.forEach(item => {
                        let qty = parseFloat(item.quantity) || 0;
                        let price = parseFloat(item.unit_price) || 0;
                        let disc = parseFloat(item.discount) || 0;
                        let tax = parseFloat(item.tax_rate) || 0;

                        // Calculation: (Q x P) - D, then + Tax
                        let lineBase = (qty * price) - disc;
                        let taxAmount = lineBase * (tax / 100);

                        item.line_total = lineBase + taxAmount;

                        this.subTotal += lineBase;
                        this.taxTotal += taxAmount;
                    });

                    this.grandTotal = this.subTotal + this.taxTotal;
                },

                formatMoney(amount) {
                    return (parseFloat(amount) || 0).toLocaleString('tr-TR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        }
    </script>
</x-app-layout>
