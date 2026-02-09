<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Yeni Teklif Hazırla 📝
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">edit_note</span>
                        Müşteriniz için profesyonel bir teklif oluşturun
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('sales.quotes.store') }}" method="POST" x-data="quoteForm()">
                @csrf

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-2xl">
                        <div class="flex items-center gap-3 text-red-500 font-bold">
                            <span class="material-symbols-outlined">warning</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Side: Main Info -->
                    <div class="lg:col-span-2 space-y-8">
                        <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl relative z-20">
                            <h3 class="text-lg font-black text-white mb-6 uppercase tracking-widest flex items-center gap-2 border-b border-white/10 pb-4">
                                <span class="material-symbols-outlined text-primary">person</span>
                                Müşteri ve Tarih Bilgileri
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Müşteri Seçin</label>
                                    <div x-data="{ 
                                        open: false, 
                                        search: '', 
                                        selectedId: '', 
                                        selectedName: 'Müşteri Seçin...',
                                        contacts: [
                                            @foreach($contacts as $contact)
                                                { id: '{{ $contact->id }}', name: '{{ addslashes($contact->name) }}' },
                                            @endforeach
                                        ],
                                        get filteredContacts() {
                                            if (this.search === '') return this.contacts;
                                            return this.contacts.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
                                        },
                                        select(contact) {
                                            this.selectedId = contact.id;
                                            this.selectedName = contact.name;
                                            this.open = false;
                                            this.search = '';
                                        }
                                    }" class="relative">
                                        <input type="hidden" name="contact_id" :value="selectedId" required>
                                        <button type="button" @click="open = !open" 
                                                class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-left text-white focus:border-primary/50 flex justify-between items-center transition-all">
                                            <span x-text="selectedName" :class="selectedId ? 'text-white' : 'text-slate-500'"></span>
                                            <span class="material-symbols-outlined transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                                        </button>

                                        <!-- Dropdown List -->
                                        <div x-show="open" @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="absolute z-50 w-full mt-2 bg-[#0f172a] border border-white/10 rounded-2xl shadow-2xl backdrop-blur-xl overflow-hidden">
                                            <div class="p-2 border-b border-white/5">
                                                <input type="text" x-model="search" placeholder="Müşteri ara..." 
                                                       class="w-full bg-white/5 border border-white/10 rounded-xl py-2 px-3 text-sm text-white focus:border-primary/50 focus:ring-0">
                                            </div>
                                            <div class="max-h-64 overflow-y-auto custom-scrollbar">
                                                <template x-for="contact in filteredContacts" :key="contact.id">
                                                    <button type="button" @click="select(contact)" 
                                                            class="w-full text-left px-4 py-3 text-sm text-slate-300 hover:bg-primary/10 hover:text-white transition-colors flex items-center justify-between">
                                                        <span x-text="contact.name"></span>
                                                        <span x-show="selectedId == contact.id" class="material-symbols-outlined text-primary text-sm">check_circle</span>
                                                    </button>
                                                </template>
                                                <div x-show="filteredContacts.length === 0" class="px-4 py-8 text-center text-slate-500 italic text-xs">
                                                    Müşteri bulunamadı...
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Teklif Tarihi</label>
                                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-white focus:border-primary/50 focus:ring-0 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Geçerlilik</label>
                                        <input type="date" name="expiry_date" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-white focus:border-primary/50 focus:ring-0 transition-all">
                                    </div>
                                </div>
                            </div>
                        </x-card>

                        <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl relative z-10">
                            <div class="flex justify-between items-center mb-6 border-b border-white/10 pb-4">
                                <h3 class="text-lg font-black text-white uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-outlined text-purple-400">inventory_2</span>
                                    Teklif İçeriği
                                </h3>
                                <button type="button" @click="addItem()" class="px-4 py-2 bg-primary/10 hover:bg-primary/20 border border-primary/20 rounded-xl text-xs font-black text-primary transition-all uppercase tracking-widest">
                                    + Satır Ekle
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="p-6 rounded-3xl bg-white/[0.02] border border-white/5 hover:border-white/10 transition-all relative group" 
                                         :style="'z-index: ' + (50 - index)">
                                        <button type="button" @click="removeItem(index)" class="absolute top-4 right-4 text-slate-500 hover:text-red-500 transition-colors">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                                            <div class="md:col-span-12 lg:col-span-5">
                                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Ürün / Açıklama</label>
                                                <input type="text" :name="'items['+index+'][description]'" x-model="item.description" placeholder="Açıklama giriniz..." class="w-full bg-white/5 border border-white/10 rounded-xl py-2 px-3 text-sm text-white focus:border-primary/50 focus:ring-0">
                                                
                                                <div x-data="{ 
                                                    dropOpen: false, 
                                                    pSearch: '', 
                                                    products: [
                                                        @foreach($products as $product)
                                                            { id: '{{ $product->id }}', name: '{{ addslashes($product->name) }}', price: '{{ $product->sale_price }}' },
                                                        @endforeach
                                                    ],
                                                    get filteredProducts() {
                                                        if (this.pSearch === '') return this.products;
                                                        return this.products.filter(p => p.name.toLowerCase().includes(this.pSearch.toLowerCase()));
                                                    },
                                                    chooseProduct(prod) {
                                                        item.product_id = prod.id;
                                                        item.description = prod.name;
                                                        item.unit_price = prod.price;
                                                        this.dropOpen = false;
                                                        this.pSearch = '';
                                                        calculateTotal();
                                                    }
                                                }" class="relative mt-2">
                                                    <button type="button" @click="dropOpen = !dropOpen" 
                                                            class="w-full bg-white/5 border border-white/10 rounded-xl py-1.5 px-3 text-[11px] text-slate-400 hover:text-white flex justify-between items-center transition-all">
                                                        <span x-text="item.product_id ? 'Ürün Seçildi' : '-- Ürün Kataloğundan Seç --'"></span>
                                                        <span class="material-symbols-outlined text-sm transition-transform" :class="dropOpen ? 'rotate-180' : ''">expand_more</span>
                                                    </button>

                                                    <div x-show="dropOpen" @click.away="dropOpen = false" 
                                                         class="absolute z-50 w-full mt-1 bg-[#0f172a] border border-white/10 rounded-xl shadow-2xl backdrop-blur-xl overflow-hidden">
                                                        <div class="p-1.5 border-b border-white/5">
                                                            <input type="text" x-model="pSearch" placeholder="Ürün ara..." 
                                                                   class="w-full bg-white/5 border border-white/10 rounded-lg py-1 px-2 text-[11px] text-white focus:ring-0">
                                                        </div>
                                                        <div class="max-h-48 overflow-y-auto custom-scrollbar">
                                                            <template x-for="p in filteredProducts" :key="p.id">
                                                                <button type="button" @click="chooseProduct(p)" 
                                                                        class="w-full text-left px-3 py-2 text-[11px] text-slate-300 hover:bg-primary/10 hover:text-white transition-colors">
                                                                    <div class="flex justify-between items-center">
                                                                        <span x-text="p.name"></span>
                                                                        <span class="text-primary font-bold">₺<span x-text="p.price"></span></span>
                                                                    </div>
                                                                </button>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" :name="'items['+index+'][product_id]'" x-model="item.product_id">
                                            </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.02); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
    </style>
                                            
                                            <div class="md:col-span-4 lg:col-span-2">
                                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Miktar</label>
                                                <input type="number" step="0.01" :name="'items['+index+'][quantity]'" x-model="item.quantity" @input="calculateTotal()" class="w-full bg-white/5 border border-white/10 rounded-xl py-2 px-3 text-sm text-white text-center">
                                            </div>
                                            
                                            <div class="md:col-span-4 lg:col-span-2">
                                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Birim Fiyat</label>
                                                <input type="number" step="0.01" :name="'items['+index+'][unit_price]'" x-model="item.unit_price" @input="calculateTotal()" class="w-full bg-white/5 border border-white/10 rounded-xl py-2 px-3 text-sm text-white text-right">
                                            </div>

                                            <div class="md:col-span-2 lg:col-span-1">
                                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">KDV%</label>
                                                <input type="number" :name="'items['+index+'][tax_rate]'" x-model="item.tax_rate" @input="calculateTotal()" class="w-full bg-white/5 border border-white/10 rounded-xl py-2 px-3 text-sm text-white text-center">
                                            </div>

                                            <div class="md:col-span-2 lg:col-span-2 text-right">
                                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Satır Toplamı</label>
                                                <div class="text-sm font-black text-white h-10 flex items-center justify-end pr-2 bg-primary/5 rounded-xl border border-primary/10">
                                                    ₺<span x-text="formatMoney(item.line_total)"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </x-card>
                    </div>

                    <!-- Right Side: Summary & Notes -->
                    <div class=" space-y-8">
                        <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl">
                            <h3 class="text-lg font-black text-white mb-6 uppercase tracking-widest flex items-center gap-2 border-b border-white/10 pb-4">
                                <span class="material-symbols-outlined text-orange-400">calculate</span>
                                Teklif Özeti
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-500 uppercase">Ara Toplam</span>
                                    <span class="text-sm font-black text-white">₺<span x-text="formatMoney(subTotal)"></span></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-500 uppercase">Vergi Toplamı</span>
                                    <span class="text-sm font-black text-white">₺<span x-text="formatMoney(taxTotal)"></span></span>
                                </div>
                                <div class="pt-4 border-t border-white/10 flex justify-between items-center">
                                    <span class="text-sm font-black text-primary uppercase tracking-widest">Genel Toplam</span>
                                    <span class="text-2xl font-black text-white">₺<span x-text="formatMoney(grandTotal)"></span></span>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-8 group relative px-6 py-4 overflow-hidden rounded-2xl bg-primary text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-primary/20">
                                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                <div class="relative flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">send</span>
                                    Teklifi Oluştur
                                </div>
                            </button>
                            
                            <a href="{{ route('sales.quotes.index') }}" class="w-full mt-4 flex items-center justify-center py-4 text-xs font-black text-slate-500 hover:text-white transition-colors uppercase tracking-widest">
                                İşlemi İptal Et
                            </a>
                        </x-card>

                        <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl">
                            <h3 class="text-lg font-black text-white mb-6 uppercase tracking-widest flex items-center gap-2 border-b border-white/10 pb-4">
                                <span class="material-symbols-outlined text-blue-400">notes</span>
                                Teklif Notları
                            </h3>
                            <textarea name="notes" rows="4" placeholder="Müşteriye özel notlar..." class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-sm text-white focus:border-primary/50 focus:ring-0 transition-all"></textarea>
                            <p class="text-[10px] text-slate-500 mt-2 italic">* Bu notlar teklif belgesinde görüntülenecektir.</p>
                        </x-card>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function quoteForm() {
            return {
                items: [
                    { product_id: '', description: '', quantity: 1, unit_price: 0, tax_rate: 20, line_total: 0 }
                ],
                subTotal: 0,
                taxTotal: 0,
                grandTotal: 0,

                init() {
                    this.$watch('items', () => this.calculateTotal(), { deep: true });
                },

                addItem() {
                    this.items.push({ product_id: '', description: '', quantity: 1, unit_price: 0, tax_rate: 20, line_total: 0 });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                        this.calculateTotal();
                    }
                },

                calculateTotal() {
                    this.subTotal = 0;
                    this.taxTotal = 0;
                    this.grandTotal = 0;

                    this.items.forEach(item => {
                        let qty = parseFloat(item.quantity) || 0;
                        let price = parseFloat(item.unit_price) || 0;
                        let tax = parseFloat(item.tax_rate) || 0;

                        let total = qty * price;
                        let taxAmount = total * (tax / 100);

                        item.line_total = total + taxAmount;

                        this.subTotal += total;
                        this.taxTotal += taxAmount;
                    });

                    this.grandTotal = this.subTotal + this.taxTotal;
                },

                formatMoney(amount) {
                    return (parseFloat(amount) || 0).toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }
    </script>
    
    <style>
        /* Global select fix for dark theme */
        select option {
            background-color: #0f172a !important;
            color: white !important;
        }
    </style>
</x-app-layout>
