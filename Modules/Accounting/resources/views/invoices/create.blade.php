<x-app-layout>
    <x-slot name="header">
        Yeni Satış Faturası
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <form action="{{ route('accounting.invoices.store') }}" method="POST"
              x-data="invoiceForm()">
            @csrf

            <!-- Header Info -->
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-xl">
                    <div class="flex items-center gap-3 text-red-500 font-bold">
                        <span class="material-symbols-outlined">warning</span>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-xl">
                    <div class="flex items-center gap-3 text-red-500 font-bold mb-2">
                        <span class="material-symbols-outlined">error</span>
                        <span>Lütfen hataları düzeltin:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <x-card class="mb-6 p-6">
                <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">Fatura Bilgileri</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Müşteri Seçin</label>
                        <select name="contact_id" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="">-- Müşteri Seç --</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Fatura Tipi</label>
                        <select name="invoice_type" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="SATIS" selected>SATIŞ</option>
                            <option value="IADE">İADE</option>
                            <option value="TEVKIFAT">TEVKİFAT</option>
                            <option value="ISTISNA">İSTİSNA</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Senaryo</label>
                        <select name="invoice_scenario" x-model="invoiceScenario" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                            <option value="EARSIV" selected>e-Arşiv Fatura</option>
                            <option value="KAGIT">Kağıt Fatura</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Fatura Tarihi</label>
                        <input type="date" name="issue_date" x-model="issueDate" @change="updateDueDate()" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Vade Tarihi (Otomatik +7 Gün)</label>
                        <input type="date" name="due_date" x-model="dueDate" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>
                </div>

                <!-- e-Invoice / Receiver Details -->
                <div class="mt-6 pt-4 border-t border-white/5 grid grid-cols-1 md:grid-cols-2 gap-6" x-show="invoiceScenario == 'EARSIV'">
                   <div>
                       <label class="block text-sm font-medium text-slate-400 mb-1">Sevk Adresi (Boş ise Cari Adresi kullanılır)</label>
                       <textarea name="receiver_info[address]" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500" rows="2"></textarea>
                   </div>
                   <div class="grid grid-cols-2 gap-4">
                       <div>
                           <label class="block text-sm font-medium text-slate-400 mb-1">Şehir</label>
                           <input type="text" name="receiver_info[city]" placeholder="Örn: Ankara" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                       </div>
                       <div>
                           <label class="block text-sm font-medium text-slate-400 mb-1">İlçe</label>
                           <input type="text" name="receiver_info[district]" placeholder="Örn: Çankaya" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                       </div>
                   </div>
                </div>
            </x-card>

            <!-- Items -->
            <x-card class="mb-6 p-6">
                <div class="flex justify-between items-center mb-4 border-b border-white/10 pb-2">
                    <h3 class="text-lg font-bold text-white">Hizmet ve Ürünler</h3>
                    <button type="button" @click="addItem()" class="text-sm bg-slate-800 hover:bg-slate-700 text-white py-1 px-3 rounded border border-white/10">
                        + Satır Ekle
                    </button>
                </div>

                <div class="overflow-visible min-h-[300px]">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs text-slate-400 uppercase border-b border-white/5">
                                <th class="py-2 w-1/3">Ürün / Hizmet</th>
                                <th class="py-2 w-24 text-right">Miktar</th>
                                <th class="py-2 w-32 text-right">Birim Fiyat</th>
                                <th class="py-2 w-24 text-right">KDV %</th>
                                <th class="py-2 w-32 text-right">Tutar</th>
                                <th class="py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="relative" :style="'z-index: ' + (50 - index)">
                                    <td class="py-2 pr-2">
                                        <input type="text" :name="'items['+index+'][description]'" x-model="item.description" placeholder="Açıklama girin" class="w-full bg-transparent border-none text-white focus:ring-0 p-0 placeholder-slate-600">
                                        
                                        <!-- Searchable Product Select Helper -->
                                        <div x-data="{ 
                                            dropOpen: false, 
                                            pSearch: '', 
                                            chooseProduct(prodId, prodName, prodPrice) {
                                                item.product_id = prodId;
                                                item.description = prodName;
                                                item.unit_price = prodPrice;
                                                this.dropOpen = false;
                                                this.pSearch = '';
                                                calculateTotal();
                                            },
                                            get filteredProducts() {
                                                if (!this.pSearch) return allProducts;
                                                return allProducts.filter(p => p.name.toLowerCase().includes(this.pSearch.toLowerCase()));
                                            }
                                        }" class="relative mt-1">
                                            <button type="button" @click="dropOpen = !dropOpen" 
                                                    class="w-full bg-slate-900/50 border border-white/10 rounded-lg py-1.5 px-3 text-xs text-slate-400 hover:text-white flex justify-between items-center transition-all">
                                                <span x-text="item.product_id ? 'Ürün Seçildi' : '...veya Ürün Seçin'"></span>
                                                <span class="material-symbols-outlined text-sm transition-transform" :class="dropOpen ? 'rotate-180' : ''">expand_more</span>
                                            </button>

                                            <!-- Dropdown Menu -->
                                            <div x-show="dropOpen" @click.away="dropOpen = false" 
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 class="absolute z-50 w-full mt-1 bg-slate-900 border border-white/10 rounded-xl shadow-2xl backdrop-blur-xl overflow-hidden">
                                                <div class="p-2 border-b border-white/5">
                                                    <input type="text" x-model="pSearch" placeholder="Ürün ara..." 
                                                           class="w-full bg-white/5 border border-white/10 rounded-lg py-1.5 px-3 text-xs text-white focus:ring-0 focus:border-primary-500">
                                                </div>
                                                <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                                    <template x-for="p in filteredProducts" :key="p.id">
                                                        <button type="button" @click="chooseProduct(p.id, p.name, p.price)" 
                                                                class="w-full text-left px-4 py-2 text-xs text-slate-300 hover:bg-primary-500/20 hover:text-white transition-colors border-b border-white/5 last:border-0 flex justify-between">
                                                            <span x-text="p.name"></span>
                                                            <span class="text-primary-400 font-bold" x-text="p.price + ' ₺'"></span>
                                                        </button>
                                                    </template>
                                                    <div x-show="filteredProducts.length === 0" class="p-4 text-center text-slate-500 text-xs italic">
                                                        Ürün bulunamadı...
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" :name="'items['+index+'][product_id]'" x-model="item.product_id">
                                    </td>
                                    <td class="py-2">
                                        <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" @input="calculateTotal()" step="0.01" class="w-full bg-slate-900/50 border border-white/10 rounded text-right text-white focus:border-primary-500 focus:ring-primary-500 p-1">
                                    </td>
                                    <td class="py-2">
                                        <input type="number" :name="'items['+index+'][unit_price]'" x-model="item.unit_price" @input="calculateTotal()" step="0.01" class="w-full bg-slate-900/50 border border-white/10 rounded text-right text-white focus:border-primary-500 focus:ring-primary-500 p-1">
                                    </td>
                                    <td class="py-2">
                                        <select :name="'items['+index+'][tax_rate]'" x-model="item.tax_rate" @change="calculateTotal()" class="w-full bg-slate-900/50 border border-white/10 rounded text-right text-white focus:border-primary-500 focus:ring-primary-500 p-1">
                                            @foreach($vat_rates as $rate)
                                                <option value="{{ (int)$rate->rate }}">%{{ (int)$rate->rate }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-2 text-right text-white font-mono">
                                        <span x-text="formatMoney(item.line_total)"></span> ₺
                                    </td>
                                    <td class="py-2 text-center">
                                        <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end mt-6">
                    <div class="w-64 space-y-2">
                        <div class="flex justify-between text-slate-400">
                            <span>Ara Toplam:</span>
                            <span class="font-mono text-white"><span x-text="formatMoney(subTotal)"></span> ₺</span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>KDV Toplam:</span>
                            <span class="font-mono text-white"><span x-text="formatMoney(taxTotal)"></span> ₺</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-white border-t border-white/10 pt-2">
                            <span>Genel Toplam:</span>
                            <span class="font-mono text-primary-400"><span x-text="formatMoney(grandTotal)"></span> ₺</span>
                        </div>
                    </div>
                </div>
            </x-card>

            <div class="flex justify-end gap-4">
                <a href="{{ route('accounting.invoices.index') }}" class="px-6 py-3 rounded-xl border border-white/10 text-slate-300 hover:bg-white/5 transition-colors font-medium">
                    İptal
                </a>
                <button type="submit" class="bg-gradient-to-r from-primary-600 to-indigo-600 text-white px-8 py-3 rounded-xl hover:shadow-[0_0_20px_rgba(79,70,229,0.4)] transition-all font-bold tracking-wide">
                    Faturayı Onayla ve Kaydet
                </button>
            </div>
        </form>
    </div>

    <script>
        function invoiceForm() {
            return {
                issueDate: '{{ date('Y-m-d') }}',
                dueDate: '{{ date('Y-m-d', strtotime('+7 days')) }}',
                invoiceScenario: 'EARSIV',
                allProducts: [
                    @foreach($products as $product)
                        { id: '{{ $product->id }}', name: '{{ addslashes($product->name) }}', price: '{{ $product->sale_price }}' },
                    @endforeach
                ],
                items: [
                    { product_id: '', description: '', quantity: 1, unit_price: 0, tax_rate: 20, line_total: 0 }
                ],
                subTotal: 0,
                taxTotal: 0,
                grandTotal: 0,

                addItem() {
                    this.items.push({ product_id: '', description: '', quantity: 1, unit_price: 0, tax_rate: 20, line_total: 0 });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                        this.calculateTotal();
                    }
                },

                fillProduct(index) {
                    // Deprecated: now using chooseProduct inside the searchable component
                },

                updateDueDate() {
                    let date = new Date(this.issueDate);
                    date.setDate(date.getDate() + 7);
                    this.dueDate = date.toISOString().split('T')[0];
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
                    return (parseFloat(amount) || 0).toFixed(2);
                }
            }
        }
    </script>
</x-app-layout>
