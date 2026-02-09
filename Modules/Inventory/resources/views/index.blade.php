<x-app-layout>
    <x-slot name="header">
        Stok ve Ürün Yönetimi
    </x-slot>

    <x-card>
        <div class="p-6 border-b border-white/5">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white">Ürün Listesi</h2>
                <div class="flex gap-2">
                    <a href="{{ route('inventory.products.import.sample') }}" class="bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white font-medium py-2 px-3 rounded-lg border border-white/10 transition-all flex items-center gap-2 text-sm" title="Excel Şablonu İndir">
                        <span class="material-symbols-outlined text-lg">download</span>
                        Şablon
                    </a>
                    <a href="{{ route('inventory.products.export') }}" class="bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white font-medium py-2 px-3 rounded-lg border border-white/10 transition-all flex items-center gap-2 text-sm" title="Excel Olarak İndir">
                        <span class="material-symbols-outlined text-lg">export_notes</span>
                        Excel İndir
                    </a>
                    <a href="{{ route('inventory.products.import.form') }}" class="bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 font-bold py-2 px-4 rounded-lg border border-blue-600/30 transition-all flex items-center gap-2 text-sm">
                        <span class="material-symbols-outlined text-lg">upload_file</span>
                        Excel Yükle
                    </a>
                    <a href="{{ route('inventory.products.create') }}" class="bg-primary-600 hover:bg-primary-500 text-white font-bold py-2 px-4 rounded-lg shadow-neon transition-all flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Yeni Ürün
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Kod (SKU)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Ürün Adı</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Tür</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">Alış Fiyatı</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">Satış Fiyatı</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="bg-transparent divide-y divide-white/5">
                        @forelse($products as $product)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400 font-mono">
                                    {{ $product->code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 bg-slate-800 rounded-lg flex items-center justify-center text-slate-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">{{ $product->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($product->type == 'good')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">Ürün</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/20">Hizmet</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-slate-400 font-mono">
                                    {{ number_format($product->purchase_price, 2) }} ₺
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-green-400 font-mono font-bold">
                                    {{ number_format($product->sale_price, 2) }} ₺
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold {{ $product->stock > 0 ? 'text-white' : 'text-red-400' }}">
                                    @if($product->type == 'good' && $product->stock_track)
                                        {{ $product->stock }} {{ $product->unit?->symbol ?? $product->unit?->name ?? 'Adet' }}
                                    @else
                                        <span class="text-slate-600">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($product->type == 'good' && $product->stock_track)
                                            <button @click="openAdjustmentModal('{{ $product->id }}', '{{ $product->name }}', '{{ $product->stock }}', '{{ $product->unit?->symbol ?? $product->unit?->name ?? 'Adet' }}')" 
                                                    class="p-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 border border-blue-500/20 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                                </svg>
                                            </button>
                                        @endif
                                        <a href="{{ route('inventory.products.edit', $product) }}" 
                                           class="p-2 rounded-lg bg-slate-500/10 text-slate-400 hover:bg-slate-500/20 hover:text-white border border-slate-500/20 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('inventory.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Bu ürünü silmek istediğinizden emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 border border-red-500/20 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                    Henüz ürün/hizmet tanımlanmamış.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </x-card>

    <!-- Adjustment Modal -->
    <div x-data="{ show: false, productId: null, productName: '', currentStock: 0, unit: 'Adet', type: 'in', quantity: 1, description: '' }"
         @open-adjustment-modal.window="show = true; productId = $event.detail.id; productName = $event.detail.name; currentStock = $event.detail.stock; unit = $event.detail.unit; type = 'in'; quantity = 1; description = '';"
         x-show="show" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75 backdrop-blur-sm" @click="show = false"></div>

            <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-slate-900 border border-white/10 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                
                <h3 class="text-lg font-medium leading-6 text-white mb-4">
                    Stok Hareketi: <span x-text="productName" class="text-primary-400"></span>
                </h3>

                <form action="{{ route('inventory.stock.adjust') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" :value="productId">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-400 mb-2">İşlem Türü</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="in" x-model="type" class="text-green-500 bg-slate-900 border-white/10 focus:ring-green-500">
                                <span class="text-white">Giriş (Ekleme)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="out" x-model="type" class="text-red-500 bg-slate-900 border-white/10 focus:ring-red-500">
                                <span class="text-white">Çıkış (Azaltma)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-400 mb-1">Miktar (<span x-text="unit"></span>)</label>
                        <input type="number" name="quantity" x-model="quantity" step="0.01" min="0.01" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                        <p class="text-xs text-slate-500 mt-1">Mevcut Stok: <span x-text="currentStock"></span> <span x-text="unit"></span></p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-400 mb-1">Açıklama</label>
                        <input type="text" name="description" x-model="description" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500" placeholder="Örn: Sayım farkı, hasarlı ürün vb.">
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="show = false" class="px-4 py-2 text-sm text-slate-300 hover:text-white">İptal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-500 shadow-neon">Kaydet</button>
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
