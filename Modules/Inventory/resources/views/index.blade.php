<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-100 via-gray-50 to-gray-100 dark:from-primary/5 dark:via-purple-500/5 dark:to-blue-500/5 animate-pulse"></div>
            <div class="relative flex justify-between items-center py-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-center text-gray-500 dark:text-slate-400 shadow-sm dark:shadow-none">
                        <span class="material-symbols-outlined text-[24px]">inventory_2</span>
                    </div>
                    <div>
                        <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1 font-display">
                            Ürün Yönetimi
                        </h2>
                        <p class="text-gray-500 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary">list_alt</span>
                            Stok kartları ve hizmet listesi
                        </p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('inventory.products.import.sample') }}" 
                       class="flex items-center gap-2 px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-white/10 text-gray-600 dark:text-slate-400 font-bold text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition-all shadow-sm dark:shadow-none" title="Excel Şablonu İndir">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                        <span class="hidden xl:inline">Şablon</span>
                    </a>
                    <a href="{{ route('inventory.products.export') }}" 
                       class="flex items-center gap-2 px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-white/10 text-gray-600 dark:text-slate-400 font-bold text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition-all shadow-sm dark:shadow-none" title="Excel Olarak İndir">
                        <span class="material-symbols-outlined text-[20px]">export_notes</span>
                        <span class="hidden xl:inline">Excel İndir</span>
                    </a>
                    <a href="{{ route('inventory.products.import.form') }}" 
                       class="flex items-center gap-2 px-4 py-3 rounded-xl bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-white/10 text-blue-600 dark:text-blue-400 font-bold text-sm hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:border-blue-200 dark:hover:border-blue-500/30 transition-all shadow-sm dark:shadow-none">
                        <span class="material-symbols-outlined text-[20px]">upload_file</span>
                        <span class="hidden sm:inline">Excel Yükle</span>
                    </a>
                    <a href="{{ route('inventory.products.create') }}" 
                       class="flex items-center gap-2 px-5 py-3 rounded-xl bg-gray-900 dark:bg-primary-600 text-white font-bold text-sm hover:bg-gray-800 dark:hover:bg-primary-500 transition-all shadow-lg shadow-gray-200/50 dark:shadow-primary-500/20">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        Yeni Ürün
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen transition-colors duration-300" 
         x-data="{ 
            selected: [], 
            toggleAll() {
                if (this.selected.length === {{ $products->count() }}) {
                    this.selected = [];
                } else {
                    this.selected = [
                        @foreach($products as $product)
                            '{{ $product->id }}',
                        @endforeach
                    ];
                }
            }
         }">
        
        <!-- Bulk Action Bar -->
        <div x-show="selected.length > 0" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-20 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-20 opacity-0"
             class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 w-full max-w-2xl px-4"
             style="display: none;">
            <div class="bg-gray-900 dark:bg-slate-800 text-white p-4 rounded-2xl shadow-2xl border border-white/10 flex items-center justify-between backdrop-blur-xl">
                <div class="flex items-center gap-4 px-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-gray-900 font-black text-sm" x-text="selected.length"></span>
                    <span class="font-bold text-sm tracking-wide">Ürün Seçildi</span>
                </div>
                
                <div class="flex items-center gap-2">
                    <form action="{{ route('inventory.products.bulk-edit') }}" method="POST">
                        @csrf
                        <template x-for="id in selected">
                            <input type="hidden" name="product_ids[]" :value="id">
                        </template>
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-widest transition-all">
                            <span class="material-symbols-outlined text-[18px]">edit_note</span>
                            Toplu Düzenle
                        </button>
                    </form>

                    <form action="{{ route('inventory.products.bulk-destroy') }}" method="POST" data-confirm="Seçilen tüm ürünler sistemden KALICI olarak silinecektir. Bu işlem geri alınamaz. Devam etmek istiyor musunuz?">
                        @csrf
                        <template x-for="id in selected">
                            <input type="hidden" name="product_ids[]" :value="id">
                        </template>
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-red-500/20 hover:bg-red-500 text-red-500 hover:text-white font-bold text-xs uppercase tracking-widest transition-all border border-red-500/30">
                            <span class="material-symbols-outlined text-[18px]">delete_sweep</span>
                            Seçilenleri Sil
                        </button>
                    </form>

                    <button @click="selected = []" class="p-2 text-gray-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-6 lg:px-8 max-w-[1600px]">
            @include('inventory::partials.stats')
            
            <div class="bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 rounded-3xl shadow-sm dark:shadow-2xl overflow-hidden backdrop-blur-xl">
                <div class="p-6 border-b border-gray-100 dark:border-white/5 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-8 bg-blue-500 rounded-full"></span>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">Ürün Listesi</h3>
                        <span class="px-3 py-1 bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-slate-400 rounded-full text-xs font-bold border border-gray-200 dark:border-white/5">
                            Top. {{ $products->total() }} Kayıt
                        </span>
                    </div>

                    <div class="flex items-center gap-4">
                        <form action="{{ route('inventory.products.index') }}" method="GET" class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Kategori:</label>
                                <select name="category_id" onchange="this.form.submit()" 
                                        class="rounded-xl bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 text-gray-900 dark:text-white text-xs font-bold focus:ring-primary focus:border-primary px-3 py-1.5 transition-all">
                                    <option value="">Tüm Kategoriler</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-center gap-2">
                                <label class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Sayfa Başı:</label>
                                <select name="per_page" onchange="this.form.submit()" 
                                        class="rounded-xl bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 text-gray-900 dark:text-white text-xs font-bold focus:ring-primary focus:border-primary px-3 py-1.5 transition-all">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="50" {{ request('per_page') == 50 || !request('per_page') ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5">
                                <th class="px-6 py-4 text-left">
                                    <input type="checkbox" 
                                           @change="toggleAll()" 
                                           :checked="selected.length === {{ $products->count() }} && {{ $products->count() }} > 0"
                                           class="rounded bg-gray-100 dark:bg-white/5 border-gray-300 dark:border-white/10 text-primary focus:ring-primary w-4 h-4">
                                </th>
                                <th class="px-2 py-4 text-left text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">Kod (SKU)</th>
                                <th class="px-6 py-4 text-left text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">Ürün Adı</th>
                                <th class="px-6 py-4 text-left text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">Kategori / Marka</th>
                                <th class="px-6 py-4 text-left text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">Tür</th>
                                <th class="px-6 py-4 text-right text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">Fiyat / KDV</th>
                                <th class="px-6 py-4 text-center text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">Stok</th>
                                <th class="px-6 py-4 text-right text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-wider">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse($products as $product)
                                <tr class="group hover:bg-blue-50/50 dark:hover:bg-blue-500/5 transition-colors"
                                    :class="selected.includes('{{ $product->id }}') ? 'bg-blue-50/50 dark:bg-blue-500/5' : ''">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" 
                                               value="{{ $product->id }}" 
                                               x-model="selected"
                                               class="rounded bg-gray-100 dark:bg-white/5 border-gray-300 dark:border-white/10 text-primary focus:ring-primary w-4 h-4">
                                    </td>
                                    <td class="px-2 py-4">
                                        <span class="text-sm font-mono font-bold text-gray-500 dark:text-slate-400 bg-gray-100 dark:bg-white/5 px-2 py-1 rounded-md border border-gray-200 dark:border-white/5">
                                            {{ $product->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-gray-400 dark:text-slate-500 border border-gray-200 dark:border-white/5 shadow-sm">
                                                <span class="material-symbols-outlined">inventory_2</span>
                                            </div>
                                            <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                {{ $product->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-gray-700 dark:text-white">{{ $product->category?->name ?? 'Kategorisiz' }}</span>
                                            <span class="text-[10px] text-gray-500 dark:text-slate-400 uppercase tracking-tight">{{ $product->brand?->name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($product->productType?->code == 'good' || $product->type == 'good')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-bold border border-blue-200 dark:border-blue-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                {{ $product->productType?->name ?? 'Ürün' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 text-xs font-bold border border-purple-200 dark:border-purple-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                                {{ $product->productType?->name ?? 'Hizmet' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-col items-end">
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($product->sale_price, 2) }} ₺</span>
                                            <span class="text-[11px] text-gray-500 dark:text-slate-400">KDV: %{{ number_format($product->vat_rate, 0) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php $stock = $product->current_stock ?? 0; @endphp
                                        @if(($product->productType?->code == 'good' || $product->type == 'good') && $product->stock_track)
                                            @if($stock <= 0)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-xs font-bold border border-red-200 dark:border-red-500/20">
                                                    Stok Yok
                                                </span>
                                            @elseif($stock <= ($product->min_stock_level ?? 0))
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 text-xs font-bold border border-orange-200 dark:border-orange-500/20" title="{{ (int)$stock }} {{ $product->unit?->symbol ?? 'Adet' }}">
                                                    {{ (int)$stock > 20 ? '20+' : (int)$stock }} {{ $product->unit?->symbol ?? $product->unit?->name ?? 'Adet' }}
                                                    <span class="text-[10px] opacity-75">(Kritik)</span>
                                                </span>
                                            @else
                                                <span class="text-sm font-bold text-gray-700 dark:text-white" title="{{ (int)$stock }} {{ $product->unit?->symbol ?? 'Adet' }}">
                                                    {{ (int)$stock > 20 ? '20+' : (int)$stock }} <span class="text-xs text-gray-400 font-normal">{{ $product->unit?->symbol ?? $product->unit?->name ?? 'Adet' }}</span>
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-gray-400 dark:text-slate-600 font-mono text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(($product->productType?->code == 'good' || $product->type == 'good') && $product->stock_track)
                                                <button @click="openAdjustmentModal('{{ $product->id }}', '{{ $product->name }}', '{{ $stock }}', '{{ $product->unit?->symbol ?? $product->unit?->name ?? 'Adet' }}')" 
                                                        class="p-2 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 border border-blue-200 dark:border-blue-500/20 transition-all" title="Stok Düzeltme">
                                                    <span class="material-symbols-outlined text-[18px]">swap_vert</span>
                                                </button>
                                            @endif
                                            <a href="{{ route('inventory.products.edit', $product) }}" 
                                               class="p-2 rounded-xl bg-gray-50 dark:bg-white/5 text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-all" title="Düzenle">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                            </a>
                                            <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" class="inline" data-confirm="'{{ $product->name }}' ürünü sistemden KALICI olarak silinecektir. Bu işlem geri alınamaz. Devam etmek istiyor musunuz?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 border border-red-200 dark:border-red-500/20 transition-all" title="Sil">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 dark:bg-white/5 rounded-2xl flex items-center justify-center mb-4 border border-gray-100 dark:border-white/5">
                                                <span class="material-symbols-outlined text-gray-300 dark:text-slate-600 text-[32px]">inventory_2</span>
                                            </div>
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Henüz ürün eklenmemiş</h3>
                                            <p class="text-gray-500 dark:text-slate-400 text-sm mb-4">Stok takibi yapmak için ilk ürününüzü ekleyin.</p>
                                            <a href="{{ route('inventory.products.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-primary-500/20 hover:bg-primary-500 transition-all">
                                                Ürün Ekle
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

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
