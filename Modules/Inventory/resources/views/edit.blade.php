<x-app-layout>
    <x-slot name="header">
        Ürün Düzenle
    </x-slot>

    <x-card class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">Ürün Bilgileri</h2>
            @if($product->stock_track)
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-primary/10 border border-primary/20">
                    <span class="material-symbols-outlined text-[18px] text-primary">inventory_2</span>
                    <span class="text-xs font-black text-primary uppercase tracking-wider">
                        Mevcut Stok: {{ (int)$product->stock }} {{ $product->unit?->symbol ?? 'Adet' }}
                    </span>
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
                    <label class="block text-sm font-medium text-slate-400 mb-1">Alış Fiyatı (Maliyet)</label>
                    <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" onfocus="this.select()" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    @error('purchase_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Satış Fiyatı</label>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Satış Fiyatı</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" onfocus="this.select()" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    @error('sale_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">KDV Oranı (%)</label>
                    <label class="block text-sm font-medium text-slate-400 mb-1">KDV Oranı (%)</label>
                    <input type="number" step="0.01" name="vat_rate" value="{{ old('vat_rate', $product->vat_rate ?? 20) }}" onfocus="this.select()" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    @error('vat_rate')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock Settings -->
                <div>
                     <label class="block text-sm font-medium text-slate-400 mb-1">Kritik Stok Seviyesi (MRP)</label>
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
</x-app-layout>
