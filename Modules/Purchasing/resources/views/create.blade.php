<x-app-layout>
<div class="px-4 py-8 sm:px-6 lg:px-8" x-data="purchaseOrderForm()">
    <form action="{{ route('purchasing.orders.store') }}" method="POST">
        @csrf
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white">Yeni Satın Alma Siparişi</h1>
                    <p class="text-gray-500 dark:text-gray-400">Tedarikçi seçin ve sipariş kalemlerini ekleyin.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('purchasing.orders.index') }}" class="px-6 py-3 rounded-xl border border-gray-200 dark:border-white/10 text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-all">İptal</a>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-purple-600 text-sm font-bold text-white shadow-lg shadow-purple-500/30 hover:bg-purple-700 transition-all">Siparişi Kaydet</button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sol Panel: Sipariş Detayları -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Tedarikçi Seçimi -->
                    <div class="bg-white/50 dark:bg-white/5 rounded-3xl p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-500">store</span>
                            Tedarikçi Bilgileri
                        </h2>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Tedarikçi</label>
                                <select name="supplier_id" required class="w-full bg-gray-50 dark:bg-white/5 border-none rounded-2xl px-4 py-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition-all">
                                    <option value="">Seçiniz</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Sipariş No</label>
                                <input type="text" name="order_number" value="{{ $nextOrderNumber }}" readonly class="w-full bg-gray-50 dark:bg-white/5 border-none rounded-2xl px-4 py-4 text-gray-900 dark:text-white font-mono opacity-60">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Sipariş Tarihi</label>
                                <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 dark:bg-white/5 border-none rounded-2xl px-4 py-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Durum</label>
                                <select name="status" class="w-full bg-gray-50 dark:bg-white/5 border-none rounded-2xl px-4 py-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition-all">
                                    <option value="draft">Taslak</option>
                                    <option value="sent">Sipariş Gönderildi</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Sipariş Kalemleri -->
                    <div class="bg-white/50 dark:bg-white/5 rounded-3xl p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-purple-500">list_alt</span>
                                Sipariş İçeriği
                            </h2>
                            <button type="button" @click="addItem()" class="flex items-center gap-2 text-sm font-bold text-purple-600 hover:text-purple-700 transition-colors">
                                <span class="material-symbols-outlined">add_circle</span>
                                Kalem Ekle
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-center gap-4 p-4 bg-gray-50/50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5">
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Ürün</label>
                                        <select :name="'items['+index+'][product_id]'" required class="w-full bg-white dark:bg-white/10 border-none rounded-xl text-sm py-2">
                                            <option value="">Ürün Seçin</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-24">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Miktar</label>
                                        <input type="number" step="0.01" :name="'items['+index+'][quantity]'" x-model="item.quantity" @input="calculateItem(index)" required class="w-full bg-white dark:bg-white/10 border-none rounded-xl text-sm py-2">
                                    </div>
                                    <div class="w-32">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Birim Fiyat</label>
                                        <input type="number" step="0.01" :name="'items['+index+'][unit_price]'" x-model="item.unit_price" @input="calculateItem(index)" required class="w-full bg-white dark:bg-white/10 border-none rounded-xl text-sm py-2">
                                    </div>
                                    <div class="w-20">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">KDV %</label>
                                        <input type="number" :name="'items['+index+'][tax_rate]'" x-model="item.tax_rate" @input="calculateItem(index)" class="w-full bg-white dark:bg-white/10 border-none rounded-xl text-sm py-2">
                                    </div>
                                    <div class="w-32 text-right">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Toplam</label>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="formatCurrency(item.total)">0.00</span>
                                    </div>
                                    <button type="button" @click="removeItem(index)" class="p-2 text-gray-400 hover:text-rose-500 transition-colors">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Sağ Panel: Özet -->
                <div class="space-y-8">
                    <div class="bg-white/50 dark:bg-white/5 rounded-3xl p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl sticky top-8">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Sipariş Özeti</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between text-gray-500">
                                <span>Ara Toplam</span>
                                <span class="font-bold text-gray-900 dark:text-white" x-text="formatCurrency(subtotal)">0.00 ₺</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Toplam KDV</span>
                                <span class="font-bold text-gray-900 dark:text-white" x-text="formatCurrency(taxTotal)">0.00 ₺</span>
                            </div>
                            <div class="h-px bg-gray-200 dark:bg-white/10"></div>
                            <div class="flex justify-between text-xl font-black text-purple-600">
                                <span>GENEL TOPLAM</span>
                                <span x-text="formatCurrency(grandTotal)">0.00 ₺</span>
                            </div>
                        </div>

                        <div class="mt-8">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Notlar</label>
                            <textarea name="notes" rows="4" class="w-full bg-gray-50 dark:bg-white/5 border-none rounded-2xl px-4 py-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 transition-all" placeholder="Sipariş için ek notlarınız..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function purchaseOrderForm() {
    return {
        items: [{ product_id: '', quantity: 0, unit_price: 0, tax_rate: 20, total: 0 }],
        subtotal: 0,
        taxTotal: 0,
        grandTotal: 0,
        
        addItem() {
            this.items.push({ product_id: '', quantity: 0, unit_price: 0, tax_rate: 20, total: 0 });
        },
        
        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
                this.calculateTotals();
            }
        },
        
        calculateItem(index) {
            let item = this.items[index];
            let rawTotal = item.quantity * item.unit_price;
            item.total = rawTotal + (rawTotal * (item.tax_rate / 100));
            this.calculateTotals();
        },
        
        calculateTotals() {
            this.subtotal = this.items.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
            this.grandTotal = this.items.reduce((sum, item) => sum + (parseFloat(item.total) || 0), 0);
            this.taxTotal = this.grandTotal - this.subtotal;
        },
        
        formatCurrency(val) {
            return new Intl.NumberFormat('tr-TR', { minimumFractionDigits: 2 }).format(val) + ' ₺';
        }
    }
}
</script>
</x-app-layout>
