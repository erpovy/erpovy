<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('inventory.products.index') }}" class="p-2 rounded-xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    Toplu Ürün Düzenleme
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium">
                    Seçilen {{ $products->count() }} ürünü toplu olarak güncelleyin
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8 max-w-[1200px]">
            <form action="{{ route('inventory.products.bulk-update') }}" method="POST">
                @csrf
                @foreach($products as $product)
                    <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                @endforeach

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Selection List -->
                    <div class="lg:col-span-1">
                        <x-card class="p-6 h-fit sticky top-8">
                            <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-4">Seçili Ürünler</h3>
                            <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($products as $product)
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5">
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                                            <p class="text-[10px] font-mono text-gray-500">{{ $product->code }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </x-card>
                    </div>

                    <!-- Edit Form -->
                    <div class="lg:col-span-2 space-y-6">
                        <x-card class="p-8">
                            <div class="flex items-center gap-3 mb-8 pb-4 border-b border-gray-100 dark:border-white/5">
                                <span class="material-symbols-outlined text-primary">edit_note</span>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Güncellenecek Alanlar</h3>
                            </div>

                            <p class="text-sm text-gray-500 dark:text-slate-400 mb-8 p-4 rounded-xl bg-blue-50 dark:bg-blue-500/5 border border-blue-100 dark:border-blue-500/10 flex gap-3">
                                <span class="material-symbols-outlined text-blue-500">info</span>
                                Yalnızca değiştirmek istediğiniz alanları doldurun. Boş bırakılan alanlar mevcut verileri koruyacaktır.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Product Type -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Ürün Türü</label>
                                    <select name="fields[product_type_id]" class="w-full h-12 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                        <option value="">Değiştirme</option>
                                        @foreach($productTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Category -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Kategori</label>
                                    <select name="fields[category_id]" class="w-full h-12 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                        <option value="">Değiştirme</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Brand -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Marka</label>
                                    <select name="fields[brand_id]" class="w-full h-12 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                        <option value="">Değiştirme</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Unit -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Birim</label>
                                    <select name="fields[unit_id]" class="w-full h-12 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                        <option value="">Değiştirme</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Purchase Price -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Alış Fiyatı</label>
                                    <input type="number" step="0.01" name="fields[purchase_price]" class="w-full h-12 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Örn: 100.00">
                                </div>

                                <!-- Sale Price -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">Satış Fiyatı</label>
                                    <input type="number" step="0.01" name="fields[sale_price]" class="w-full h-12 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Örn: 150.00">
                                </div>

                                <!-- VAT Rate -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">KDV Oranı (%)</label>
                                    <input type="number" step="1" name="fields[vat_rate]" class="w-full h-12 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Örn: 20">
                                </div>

                                <!-- Stock Track -->
                                <div class="flex items-end">
                                    <label class="w-full h-12 flex items-center gap-3 cursor-pointer px-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-primary/50 transition-all">
                                        <select name="fields[stock_track]" class="bg-transparent border-none text-sm font-bold p-0 focus:ring-0">
                                            <option value="">Stok Takibi Ayarı (Değiştirme)</option>
                                            <option value="1">Stok Takibi YAPILSIN</option>
                                            <option value="0">Stok Takibi YAPILMASIN</option>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-12 pt-8 border-t border-gray-100 dark:border-white/5 flex justify-end gap-3">
                                <a href="{{ route('inventory.products.index') }}" class="px-8 py-3 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-slate-300 font-bold hover:bg-gray-200 dark:hover:bg-white/10 transition-all">İptal</a>
                                <button type="submit" class="px-10 py-3 rounded-xl bg-primary text-gray-900 font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-primary/20">
                                    Değişiklikleri Uygula
                                </button>
                            </div>
                        </x-card>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
