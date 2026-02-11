<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight">
                {{ __('PLM (Ürün Reçeteleri)') }}
            </h2>
            <div class="text-gray-600 dark:text-slate-400 text-sm font-medium">
                {{ now()->translatedFormat('d F Y, l') }}
            </div>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ openModal: false, items: [{product_id: '', quantity: 1, unit: 'adet', wastage: 0}] }">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-12 space-y-8">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
                <!-- Total BOMs -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-indigo-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-indigo-500/10 text-indigo-500 ring-1 ring-indigo-500/20">
                                <span class="material-symbols-outlined text-[24px]">schema</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Toplam Reçete</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $stats['total'] }}</div>
                        <div class="text-indigo-400 text-sm mt-4 flex items-center font-bold bg-indigo-500/10 w-fit px-3 py-1.5 rounded-lg border border-indigo-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">folder_open</span>
                            Tüm Tanımlar
                        </div>
                    </div>
                </x-card>

                <!-- Active BOMs -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-green-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-green-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-green-500/10 text-green-500 ring-1 ring-green-500/20">
                                <span class="material-symbols-outlined text-[24px]">check_circle</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Aktif Reçete</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $stats['active'] }}</div>
                        <div class="text-green-400 text-sm mt-4 flex items-center font-bold bg-green-500/10 w-fit px-3 py-1.5 rounded-lg border border-green-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">verified_user</span>
                            Kullanımda
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- List Section -->
             <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden rounded-[2.5rem] shadow-2xl">
                <div class="p-8 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-gray-50 dark:bg-white/[0.02]">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-[24px]">hub</span>
                        </div>
                        Ürün Reçeteleri (BOM)
                    </h3>
                    <button @click="openModal = true" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Yeni Reçete
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-100 dark:bg-white/5 text-xs uppercase text-gray-700 dark:text-slate-400 font-bold tracking-wider">
                            <tr>
                                <th class="px-8 py-6">Reçete Kodu</th>
                                <th class="px-8 py-6">Reçete Adı</th>
                                <th class="px-8 py-6">Ana Ürün</th>
                                <th class="px-8 py-6">Versiyon</th>
                                <th class="px-8 py-6">Bileşen Sayısı</th>
                                <th class="px-8 py-6">Durum</th>
                                <th class="px-8 py-6 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($boms as $bom)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-8 py-6 font-bold text-gray-900 dark:text-white">{{ $bom->code }}</td>
                                    <td class="px-8 py-6 text-gray-700 dark:text-slate-300">{{ $bom->name ?? '-' }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-gray-900 dark:text-white font-bold">{{ $bom->product->name ?? 'Silinmiş Ürün' }}</span>
                                            <span class="text-xs text-gray-500 dark:text-slate-500 font-bold">{{ $bom->product->code ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="px-2 py-1 rounded bg-gray-100 dark:bg-white/10 text-xs font-mono text-gray-700 dark:text-slate-300 border border-gray-300 dark:border-white/5">
                                            {{ $bom->version }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-gray-700 dark:text-slate-300">
                                        {{ $bom->items->count() }} Bileşen
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($bom->is_active)
                                            <div class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] uppercase font-black tracking-widest bg-green-500/10 text-green-500 border border-green-500/20">
                                                Aktif
                                            </div>
                                        @else
                                            <div class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] uppercase font-black tracking-widest bg-slate-500/10 text-slate-500 border border-slate-500/20">
                                                Pasif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button class="p-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-8 py-16 text-center">
                                        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="material-symbols-outlined text-4xl text-slate-600">schema</span>
                                        </div>
                                        <p class="text-gray-500 dark:text-slate-500 font-medium text-lg">Henüz ürün reçetesi oluşturulmamış.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 border-t border-gray-200 dark:border-white/5">
                    {{ $boms->links() }}
                </div>
            </x-card>
        </div>

        <!-- Create Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openModal = false"></div>
            
            <div class="relative bg-white dark:bg-[#1e1e2d] rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto border border-gray-200 dark:border-white/10 transform transition-all"
                 x-show="openModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="px-8 py-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center bg-gray-50 dark:bg-white/[0.02] sticky top-0 z-10 backdrop-blur-md">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Yeni Ürün Reçetesi (BOM)</h3>
                    <button @click="openModal = false" class="text-gray-500 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form action="{{ route('manufacturing.plm.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    
                    <!-- Header Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                         <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Ana Ürün</label>
                            <select name="product_id" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                                <option value="" class="bg-[#1e1e2d]">Ürün Seçiniz...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" class="bg-[#1e1e2d]">{{ $product->code }} - {{ $product->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-slate-500 mt-2">Hangi ürün için reçete oluşturuyorsunuz?</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Reçete Adı</label>
                            <input type="text" name="name" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" placeholder="Örn: Standart Üretim Reçetesi">
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-white/10 pt-8">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="font-bold text-gray-900 dark:text-white flex items-center gap-2 text-lg">
                                <span class="material-symbols-outlined text-primary">format_list_bulleted</span>
                                Reçete Bileşenleri
                            </h4>
                            <button type="button" @click="items.push({product_id: '', quantity: 1, unit: 'adet', wastage: 0})" class="text-sm text-primary font-bold hover:text-primary/80 flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">add_circle</span> Bileşen Ekle
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex flex-col md:flex-row gap-4 p-5 bg-white/[0.03] rounded-2xl border border-white/5 relative group hover:bg-white/[0.05] transition-colors">
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-wider mb-1.5">Bileşen</label>
                                        <select :name="'items['+index+'][product_id]'" x-model="item.product_id" class="w-full rounded-lg border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-sm text-gray-900 dark:text-white focus:border-primary focus:ring-primary h-10 px-3" required>
                                            <option value="" class="bg-[#1e1e2d]">Seçiniz...</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" class="bg-[#1e1e2d]">{{ $product->code }} - {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-32">
                                        <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-wider mb-1.5">Miktar</label>
                                        <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" step="0.0001" class="w-full rounded-lg border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-sm text-gray-900 dark:text-white focus:border-primary focus:ring-primary h-10 px-3" placeholder="0">
                                    </div>
                                    <div class="w-28">
                                        <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-wider mb-1.5">Birim</label>
                                        <select :name="'items['+index+'][unit]'" x-model="item.unit" class="w-full rounded-lg border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-sm text-gray-900 dark:text-white focus:border-primary focus:ring-primary h-10 px-3">
                                            <option value="adet" class="bg-[#1e1e2d]">Adet</option>
                                            <option value="kg" class="bg-[#1e1e2d]">Kg</option>
                                            <option value="m" class="bg-[#1e1e2d]">Metre</option>
                                            <option value="lt" class="bg-[#1e1e2d]">Litre</option>
                                        </select>
                                    </div>
                                     <div class="w-28">
                                        <label class="block text-[10px] font-bold text-gray-500 dark:text-slate-500 uppercase tracking-wider mb-1.5">Fire (%)</label>
                                        <input type="number" :name="'items['+index+'][wastage_percent]'" x-model="item.wastage" step="0.01" class="w-full rounded-lg border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-sm text-gray-900 dark:text-white focus:border-primary focus:ring-primary h-10 px-3" placeholder="0">
                                    </div>
                                    
                                    <div class="flex items-end pb-1.5">
                                        <button type="button" @click="items = items.filter((_, i) => i !== index)" class="text-red-400 hover:text-red-300 p-2 rounded-lg hover:bg-red-500/10 transition-colors" title="Sil">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <div x-show="items.length === 0" class="text-center py-12 text-gray-500 dark:text-slate-500 border-2 border-dashed border-gray-300 dark:border-white/10 rounded-2xl bg-gray-50 dark:bg-white/[0.02]">
                            <span class="material-symbols-outlined text-3xl mb-3 opacity-50">playlist_remove</span>
                            <p class="text-sm font-medium">Henüz bileşen eklenmedi.</p>
                            <button type="button" @click="items.push({product_id: '', quantity: 1, unit: 'adet', wastage: 0})" class="text-primary hover:text-primary/80 text-sm font-bold mt-3 transition-colors">İlk bileşeni ekle</button>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end gap-4 border-t border-gray-200 dark:border-white/10">
                        <button type="button" @click="openModal = false" class="px-6 py-3 rounded-xl text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 font-bold transition-colors">İptal</button>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-1">
                            Reçeteyi Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
