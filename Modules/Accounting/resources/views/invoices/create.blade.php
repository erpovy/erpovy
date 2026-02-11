<x-app-layout>
    <x-slot name="header">
        Yeni Satış Faturası
    </x-slot>

    <div class="max-w-[95%] mx-auto" x-data="invoiceForm()">
        <form action="{{ route('accounting.invoices.store') }}" method="POST">
            @csrf

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Yeni Satış Faturası Oluştur</h1>
                    <p class="text-sm text-gray-600 dark:text-slate-400">Müşterilerinize kesmek üzere yeni bir fatura hazırlayın.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 rounded-xl bg-primary/10 border border-primary/20 text-primary text-sm font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">payments</span>
                        <span x-text="'Toplam: ' + formatCurrency(grandTotal)"></span> ₺
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/50 rounded-xl flex items-center gap-3 text-red-600 dark:text-red-500 font-bold">
                    <span class="material-symbols-outlined">warning</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/50 rounded-xl">
                    <div class="flex items-center gap-3 text-red-600 dark:text-red-500 font-bold mb-2">
                        <span class="material-symbols-outlined">error</span>
                        <span>Lütfen hataları düzeltin:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-500 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Invoice Info -->
                <div class="lg:col-span-2 space-y-6">
                    <x-card class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-white/10 pb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">description</span>
                            Fatura Bilgileri
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">person</span>
                                    Müşteri Seçin
                                </label>
                                <div class="relative">
                                    <select name="contact_id" class="w-full pl-4 pr-10 py-2.5 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer font-medium transition-all" required>
                                        <option value="">-- Müşteri Seç --</option>
                                        @foreach($contacts as $contact)
                                            <option value="{{ $contact->id }}" class="bg-white dark:bg-slate-900">{{ $contact->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 dark:text-slate-500 text-[20px] pointer-events-none">expand_more</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                                        Fatura Tipi
                                    </label>
                                    <div class="relative">
                                        <select name="invoice_type" class="w-full pl-4 pr-10 py-2.5 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer font-medium transition-all">
                                            <option value="SATIS" selected>SATIŞ</option>
                                            <option value="IADE">İADE</option>
                                            <option value="TEVKIFAT">TEVKİFAT</option>
                                            <option value="ISTISNA">İSTİSNA</option>
                                        </select>
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 dark:text-slate-500 text-[20px] pointer-events-none">expand_more</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">auto_stories</span>
                                        Senaryo
                                    </label>
                                    <div class="relative">
                                        <select name="invoice_scenario" x-model="invoiceScenario" class="w-full pl-4 pr-10 py-2.5 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer font-medium transition-all">
                                            <option value="EARSIV" selected>e-Arşiv</option>
                                            <option value="KAGIT">Kağıt</option>
                                        </select>
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 dark:text-slate-500 text-[20px] pointer-events-none">expand_more</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                                    Fatura Tarihi
                                </label>
                                <input type="date" name="issue_date" x-model="issueDate" @change="updateDueDate()" 
                                       class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">event_busy</span>
                                    Vade Tarihi
                                </label>
                                <input type="date" name="due_date" x-model="dueDate" 
                                       class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium">
                            </div>
                        </div>

                        <!-- e-Invoice / Receiver Details -->
                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-white/10" x-show="invoiceScenario == 'EARSIV'" x-collapse>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px]">local_shipping</span>
                                Sevk ve Teslimat Bilgileri
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Sevk Adresi</label>
                                    <textarea name="receiver_info[address]" rows="3" 
                                              placeholder="Boş bırakılırsa cari adresi otomatik olarak kullanılır."
                                              class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm"></textarea>
                                </div>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Şehir</label>
                                            <input type="text" name="receiver_info[city]" placeholder="Örn: Ankara" 
                                                   class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">İlçe</label>
                                            <input type="text" name="receiver_info[district]" placeholder="Örn: Çankaya" 
                                                   class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-slate-500 italic">e-Fatura senaryolarında adres bilgileri zorunludur.</p>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Summary Side Card -->
                <div class="lg:col-span-1">
                    <x-card class="p-6 h-fit sticky top-6 bg-gradient-to-br from-gray-900 to-slate-900 border-none">
                        <h3 class="text-sm font-bold text-white mb-6 flex items-center gap-2 uppercase tracking-wider opacity-80">
                            <span class="material-symbols-outlined text-primary text-[20px]">analytics</span>
                            Fatura Özeti
                        </h3>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400">Ara Toplam:</span>
                                <span class="text-white font-mono font-bold" x-text="formatCurrency(subTotal) + ' ₺'">0.00 ₺</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400">KDV Toplam:</span>
                                <span class="text-white font-mono font-bold" x-text="formatCurrency(taxTotal) + ' ₺'">0.00 ₺</span>
                            </div>
                            <div class="h-px bg-white/10 my-4"></div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] text-primary uppercase font-bold tracking-widest">Ödenecek Tutar</span>
                                <div class="text-3xl font-bold text-white font-mono tracking-tighter" x-text="formatCurrency(grandTotal) + ' ₺'">
                                    0.00 ₺
                                </div>
                            </div>
                            
                            <div class="mt-8 pt-6">
                                <button type="submit" 
                                        class="w-full flex items-center justify-center gap-3 bg-gradient-to-r from-primary to-blue-600 text-white py-4 rounded-2xl hover:shadow-[0_20px_40px_rgba(37,99,235,0.3)] hover:scale-[1.02] active:scale-[0.98] transition-all font-bold text-lg shadow-xl shadow-primary/20"
                                        :disabled="grandTotal <= 0">
                                    <span class="material-symbols-outlined text-[24px]">verified</span>
                                    Faturayı Kaydet
                                </button>
                                <a href="{{ route('accounting.invoices.index') }}" 
                                   class="w-full mt-4 flex items-center justify-center gap-2 text-white/50 hover:text-white transition-colors text-sm font-bold">
                                    <span class="material-symbols-outlined text-[18px]">undo</span>
                                    İşlemi İptal Et
                                </a>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Items Table Section -->
            <div class="mt-8">
                <x-card class="overflow-hidden border-2 border-gray-100 dark:border-white/5 shadow-glass">
                    <div class="p-4 bg-gray-50 dark:bg-white/5 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">inventory_2</span>
                            Hizmet ve Ürünler
                        </h3>
                        <button type="button" @click="addItem()" 
                                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-xs font-bold hover:bg-primary-600 transition-all shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-[18px]">add_circle</span>
                            Yeni Satır Ekle
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs text-gray-500 dark:text-slate-400 uppercase tracking-wider font-bold border-b border-gray-100 dark:border-white/5">
                                    <th class="px-4 py-4 w-1/3">Ürün / Hizmet Açıklaması</th>
                                    <th class="px-4 py-4 w-28 text-right">Miktar</th>
                                    <th class="px-4 py-4 w-36 text-right">Birim Fiyat</th>
                                    <th class="px-4 py-4 w-24 text-right">KDV %</th>
                                    <th class="px-4 py-4 w-40 text-right">Satır Toplamı</th>
                                    <th class="px-4 py-4 w-12 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-4 py-4">
                                            <div class="space-y-2">
                                                <input type="text" :name="'items['+index+'][description]'" x-model="item.description" 
                                                       placeholder="Açıklama girin..." 
                                                       class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white text-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium">
                                                
                                                <!-- Searchable Product Select -->
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
                                                }" class="relative">
                                                    <button type="button" @click="dropOpen = !dropOpen" 
                                                            class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg border border-dashed border-gray-300 dark:border-white/10 text-[10px] font-bold text-gray-500 dark:text-slate-500 hover:border-primary hover:text-primary transition-all">
                                                        <span x-text="item.product_id ? 'Ürün Seçildi ✓' : 'Kayıtlı Ürünlerden Seç...'"></span>
                                                        <span class="material-symbols-outlined text-[14px]">search</span>
                                                    </button>

                                                    <div x-show="dropOpen" @click.away="dropOpen = false" x-cloak
                                                         class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-white/10 rounded-xl shadow-2xl overflow-hidden ring-1 ring-black/5 dark:ring-white/5">
                                                        <div class="p-2 border-b border-gray-100 dark:border-white/5">
                                                            <input type="text" x-model="pSearch" placeholder="Hizmet/Ürün ara..." 
                                                                   class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg py-1.5 px-3 text-xs text-gray-900 dark:text-white focus:ring-1 focus:ring-primary focus:border-primary">
                                                        </div>
                                                        <div class="max-h-48 overflow-y-auto">
                                                            <template x-for="p in filteredProducts" :key="p.id">
                                                                <button type="button" @click="chooseProduct(p.id, p.name, p.price)" 
                                                                        class="w-full text-left px-4 py-2.5 text-xs text-gray-700 dark:text-slate-300 hover:bg-primary hover:text-white transition-all border-b border-gray-50 dark:border-white/5 last:border-0 flex justify-between items-center group/item">
                                                                    <span x-text="p.name" class="font-medium"></span>
                                                                    <span class="text-primary group-hover/item:text-white font-bold" x-text="formatCurrency(p.price) + ' ₺'"></span>
                                                                </button>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" :name="'items['+index+'][product_id]'" x-model="item.product_id">
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" 
                                                   @input="calculateTotal()" step="0.01" 
                                                   class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-right text-gray-900 dark:text-white text-sm font-mono focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="number" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" 
                                                   @input="calculateTotal()" step="0.01" 
                                                   class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-right text-gray-900 dark:text-white text-sm font-mono focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="relative">
                                                <select :name="'items['+index+'][tax_rate]'" x-model.number="item.tax_rate" 
                                                        @change="calculateTotal()" 
                                                        class="w-full pl-3 pr-8 py-2 rounded-lg bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-right text-gray-900 dark:text-white text-sm font-mono focus:border-primary focus:ring-2 focus:ring-primary/20 appearance-none transition-all">
                                                    @foreach($vat_rates as $rate)
                                                        <option value="{{ (int)$rate->rate }}">%{{ (int)$rate->rate }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="absolute right-2 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 text-[14px] pointer-events-none">expand_more</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-right text-gray-900 dark:text-white font-mono font-bold text-base">
                                            <span x-text="formatCurrency(item.line_total)"></span> ₺
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <button type="button" @click="removeItem(index)" 
                                                    class="p-2 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all group-hover:scale-110" 
                                                    x-show="items.length > 1">
                                                <span class="material-symbols-outlined text-[20px]">delete_sweep</span>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </x-card>
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

                init() {
                    this.calculateTotal();
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

                formatCurrency(value) {
                    return new Intl.NumberFormat('tr-TR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(value);
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            @apply bg-transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            @apply bg-slate-700/50 rounded-full;
        }

        .shadow-glass {
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
</x-app-layout>
