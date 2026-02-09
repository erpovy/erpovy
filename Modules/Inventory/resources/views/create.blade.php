<x-app-layout>
    <x-slot name="header">
        Yeni Ürün / Hizmet Kartı
    </x-slot>

    <x-card class="p-6">
        <h2 class="text-xl font-bold text-white mb-6">Ürün Bilgileri</h2>

        <form action="{{ route('inventory.products.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Code -->
                <div x-data="{ 
                    generateSku() { 
                        // PRD-YYYYMMDD-RNNN format
                        let date = new Date().toISOString().slice(0,10).replace(/-/g,'');
                        let random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                        this.$refs.skuInput.value = 'PRD-' + date + '-' + random;
                    } 
                }">
                    <label class="block text-sm font-medium text-slate-400 mb-1">Ürün Kodu (SKU)</label>
                    <div class="flex gap-2">
                        <input type="text" name="code" x-ref="skuInput" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" required placeholder="Otomatik için butona basın">
                        <button type="button" @click="generateSku()" class="whitespace-nowrap px-3 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs rounded-lg border border-white/10 transition-colors">
                            SKU Oluştur
                        </button>
                    </div>
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Ürün Adı</label>
                    <input type="text" name="name" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" required>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">Kategori</label>
                    <select name="category_id" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        <option value="">Kategori Seçiniz</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" class="bg-slate-900">{{ $category->name }}</option>
                        @endforeach
                    </select>
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
                            <option value="{{ $brand->id }}" class="bg-slate-900">{{ $brand->name }}</option>
                        @endforeach
                    </select>
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
                            <option value="{{ $type->id }}" class="bg-slate-900">{{ $type->name }}</option>
                         @endforeach
                     </select>
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
                            <option value="{{ $unit->id }}" class="bg-slate-900">{{ $unit->name }} ({{ $unit->symbol }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Prices -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Alış Fiyatı (Maliyet)</label>
                    <input type="number" step="0.01" name="purchase_price" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Satış Fiyatı</label>
                    <input type="number" step="0.01" name="sale_price" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">KDV Oranı (%)</label>
                    <input type="number" step="0.01" name="vat_rate" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="20">
                </div>

                <!-- Stock Settings -->
                <div>
                     <label class="block text-sm font-medium text-slate-400 mb-1">Kritik Stok Seviyesi (MRP)</label>
                     <input type="number" step="0.01" name="min_stock_level" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" placeholder="Örn: 10">
                    <p class="text-xs text-slate-500 mt-1">Stok bu seviyenin altına düştüğünde MRP uyarı verir.</p>
                </div>

                <div class="md:col-span-2">
                     <label class="flex items-center gap-2 cursor-pointer p-4 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 transition-colors">
                        <input type="checkbox" name="stock_track" value="1" checked class="text-primary-600 bg-slate-900 border-white/10 focus:ring-primary-600 rounded w-5 h-5">
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
                    Kaydet
                </button>
            </div>
        </form>
    </x-card>
</x-app-layout>
