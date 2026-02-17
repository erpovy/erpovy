<x-app-layout>
    <x-slot name="header">
        Ürün Düzenle
    </x-slot>

    <x-card class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">Ürün Bilgileri</h2>
            @if($product->stock_track)
                <div class="flex items-center gap-3 px-3 py-1.5 rounded-xl bg-primary/10 border border-primary/20">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-primary">inventory_2</span>
                        <span class="text-xs font-black text-primary uppercase tracking-wider">
                            Mevcut Stok: {{ (int)$product->stock }} {{ $product->unit?->symbol ?? 'Adet' }}
                        </span>
                    </div>
                    <button type="button" 
                            @click="openAdjustmentModal('{{ $product->id }}', '{{ $product->name }}', '{{ (int)$product->stock }}', '{{ $product->unit?->symbol ?? 'Adet' }}')"
                            class="px-2 py-1 rounded-lg bg-primary text-white text-[10px] font-black uppercase hover:bg-primary-500 transition-colors">
                        Düzelt
                    </button>
                </div>
            @endif
        </div>

        <form action="{{ route('inventory.products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Code -->
                <div x-data="{ 
                    generateSku() { 
                        let date = new Date().toISOString().slice(0,10).replace(/-/g,'');
                        let random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                        this.$refs.skuInput.value = 'PRD-' + date + '-' + random;
                    } 
                }">
                    <label class="block text-sm font-medium text-slate-400 mb-1">Ürün Kodu (SKU)</label>
                    <div class="flex gap-2">
                        <input type="text" name="code" x-ref="skuInput" value="{{ old('code', $product->code) }}" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" required>
                        <button type="button" @click="generateSku()" class="whitespace-nowrap px-3 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs rounded-lg border border-white/10 transition-colors">
                            SKU Oluştur
                        </button>
                    </div>
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Ürün Adı</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Kategori</label>
                    <select name="category_id" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Kategori Seçiniz</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }} class="bg-slate-900">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Brand -->
                <div>
                     <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-slate-400">Marka</label>
                        <a href="{{ route('inventory.brands.index') }}" target="_blank" class="text-xs text-primary-400 hover:text-primary-300">
                             + Markaları Yönet
                        </a>
                     </div>
                    <select name="brand_id" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Marka Seçiniz</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }} class="bg-slate-900">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                     <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-slate-400">Tür</label>
                        <a href="{{ route('inventory.settings.types.index') }}" target="_blank" class="text-xs text-primary-400 hover:text-primary-300">
                             + Türleri Yönet
                        </a>
                     </div>
                     <select name="product_type_id" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                         @foreach($productTypes as $type)
                            <option value="{{ $type->id }}" {{ old('product_type_id', $product->product_type_id) == $type->id ? 'selected' : '' }} class="bg-slate-900">
                                {{ $type->name }}
                            </option>
                         @endforeach
                     </select>
                     @error('product_type_id')
                         <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                     @enderror
                </div>

                <!-- Unit -->
                <div>
                     <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-slate-400">Ölçü Birimi</label>
                        <a href="{{ route('inventory.units.index') }}" target="_blank" class="text-xs text-primary-400 hover:text-primary-300">
                             + Birimleri Yönet
                        </a>
                     </div>
                    <select name="unit_id" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Birim Seçiniz</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }} class="bg-slate-900">{{ $unit->name }} ({{ $unit->symbol }})</option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prices & VAT -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Alış Fiyatı (Maliyet)</label>
                    <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" onfocus="this.select()" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    @error('purchase_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Satış Fiyatı</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" onfocus="this.select()" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    @error('sale_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">KDV Oranı (%)</label>
                    <input type="number" step="0.01" name="vat_rate" value="{{ old('vat_rate', $product->vat_rate ?? 20) }}" onfocus="this.select()" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    @error('vat_rate')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock Settings -->
                <div>
                     <label class="block text-sm font-medium text-slate-400 mb-1">Kritik Stok Seviyesi (MRP)</label>
                     <input type="number" step="0.01" name="min_stock_level" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" value="{{ old('min_stock_level', $product->min_stock_level) }}" onfocus="this.select()">
                    <p class="text-xs text-slate-500 mt-1">Stok bu seviyenin altına düştüğünde MRP uyarı verir.</p>
                     @error('min_stock_level')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                     <label class="flex items-center gap-2 cursor-pointer p-4 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                        <input type="checkbox" name="stock_track" value="1" {{ old('stock_track', $product->stock_track) ? 'checked' : '' }} class="text-primary-600 bg-slate-900 border-white/10 focus:ring-primary-600 rounded w-5 h-5">
                        <span class="text-slate-300 font-medium">Bu ürün için stok takibi yapılsın</span>
                    </label>
                    <p class="text-xs text-slate-500 mt-2 px-1">Seçili olduğunda sistem her satış ve alış işleminde stok adetlerini otomatik günceller.</p>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('inventory.products.index') }}" class="px-6 py-2 rounded-lg border border-white/10 text-slate-300 hover:bg-white/5 transition-colors">
                    İptal
                </a>
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-500 shadow-neon transition-all">
                    Güncelle
                </button>
            </div>
        </form>
    </x-card>

    <!-- Adjustment Modal -->
    <div x-data="{ show: false, productId: null, productName: '', currentStock: 0, unit: 'Adet', type: 'in', quantity: 1, description: '' }"
         @open-adjustment-modal.window="show = true; productId = $event.detail.id; productName = $event.detail.name; currentStock = $event.detail.stock; unit = $event.detail.unit; type = 'in'; quantity = 1; description = '';"
         x-show="show" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/50 dark:bg-black/80 backdrop-blur-sm" @click="show = false"></div>

            <div class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-white/10 rounded-3xl shadow-2xl relative">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">
                        Stok Hareketi
                    </h3>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="mb-6 p-4 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5">
                    <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-1">Seçili Ürün</p>
                    <div class="flex justify-between items-end">
                        <p class="text-lg font-bold text-gray-900 dark:text-white" x-text="productName"></p>
                        <p class="text-sm font-medium text-gray-500 dark:text-slate-400">
                            Mevcut: <span x-text="currentStock" class="text-gray-900 dark:text-white font-bold"></span> <span x-text="unit"></span>
                        </p>
                    </div>
                </div>

                <form action="{{ route('inventory.stock.adjust') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" :value="productId">
                    
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">İşlem Türü</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="type" value="in" x-model="type" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 peer-checked:border-emerald-500 dark:peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-500/10 transition-all text-center">
                                    <span class="material-symbols-outlined text-3xl mb-1 text-gray-400 peer-checked:text-emerald-500 block">add_circle</span>
                                    <span class="text-sm font-bold text-gray-500 dark:text-slate-400 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-400">Giriş (Ekleme)</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="type" value="out" x-model="type" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 peer-checked:border-red-500 dark:peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-500/10 transition-all text-center">
                                    <span class="material-symbols-outlined text-3xl mb-1 text-gray-400 peer-checked:text-red-500 block">remove_circle</span>
                                    <span class="text-sm font-bold text-gray-500 dark:text-slate-400 peer-checked:text-red-700 dark:peer-checked:text-red-400">Çıkış (Azaltma)</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Miktar (<span x-text="unit"></span>)</label>
                        <div class="relative">
                            <input type="number" name="quantity" x-model="quantity" step="0.01" min="0.01" 
                                   class="w-full h-12 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all font-bold text-lg">
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Açıklama</label>
                        <input type="text" name="description" x-model="description" 
                               class="w-full h-12 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" 
                               placeholder="Örn: Sayım farkı, hasarlı ürün vb.">
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-white/5">
                        <button type="button" @click="show = false" class="px-6 py-3 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-slate-300 font-bold hover:bg-gray-200 dark:hover:bg-white/10 transition-all">İptal</button>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-gray-900 dark:bg-primary-600 text-white font-bold hover:bg-gray-800 dark:hover:bg-primary-500 shadow-lg shadow-gray-200/50 dark:shadow-primary-500/20 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAdjustmentModal(id, name, stock, unit) {
            window.dispatchEvent(new CustomEvent('open-adjustment-modal', {
                detail: { id: id, name: name, stock: stock, unit: unit }
            }));
        }
    </script>
</x-app-layout>
