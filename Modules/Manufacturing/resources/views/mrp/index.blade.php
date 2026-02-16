<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight">
                {{ __('Malzeme İhtiyaç Planlaması (MRP)') }}
            </h2>
            <div class="text-gray-600 dark:text-slate-400 text-sm font-medium">
                {{ now()->translatedFormat('d F Y, l') }}
            </div>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ openModal: false, selectedProduct: null, quantity: 0 }">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-12 space-y-8">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Open Orders -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-blue-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-blue-500/10 text-blue-500 ring-1 ring-blue-500/20">
                                <span class="material-symbols-outlined text-[24px]">pending_actions</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Açık İş Emirleri</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $stats['open_orders'] }}</div>
                        <div class="text-blue-400 text-sm mt-4 flex items-center font-bold bg-blue-500/10 w-fit px-3 py-1.5 rounded-lg border border-blue-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">timelapse</span>
                            Üretimdeki Siparişler
                        </div>
                    </div>
                </x-card>

                <!-- Critical Stock -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-red-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-red-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-red-500/10 text-red-500 ring-1 ring-red-500/20">
                                <span class="material-symbols-outlined text-[24px]">inventory_2</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Kritik Stok</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $stats['critical_stock'] }}</div>
                        <div class="text-red-400 text-sm mt-4 flex items-center font-bold bg-red-500/10 w-fit px-3 py-1.5 rounded-lg border border-red-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">warning</span>
                            Acil İkmal Gerekli
                        </div>
                    </div>
                </x-card>

                <!-- Planned Production -->
                 <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-purple-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-purple-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-purple-500/10 text-purple-500 ring-1 ring-purple-500/20">
                                <span class="material-symbols-outlined text-[24px]">factory</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Planlanan Üretim</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ number_format($stats['planned_production'], 0) }}</div>
                        <div class="text-purple-400 text-sm mt-4 flex items-center font-bold bg-purple-500/10 w-fit px-3 py-1.5 rounded-lg border border-purple-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">precision_manufacturing</span>
                            Adet Üretilecek
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Material Requirements List -->
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden rounded-[2.5rem] shadow-2xl mb-8">
                <div class="p-8 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-gray-50 dark:bg-white/[0.02]">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-[24px]">list_alt</span>
                        </div>
                        Üretim & İkmal Planı
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-100 dark:bg-white/5 text-xs uppercase text-gray-700 dark:text-slate-400 font-bold tracking-wider">
                            <tr>
                                <th class="px-8 py-6">Ürün</th>
                                <th class="px-8 py-6 text-center">Mevcut Stok</th>
                                <th class="px-8 py-6 text-center">Gelen (PO/WO)</th>
                                <th class="px-8 py-6 text-center">Min. Seviye</th>
                                <th class="px-8 py-6">Öneri</th>
                                <th class="px-8 py-6 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($requirements as $req)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-gray-900 dark:text-white font-bold">{{ $req['product']->name }}</span>
                                            <span class="text-xs text-slate-500 font-mono">{{ $req['product']->code }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $req['current_stock'] <= 0 ? 'bg-red-500/10 text-red-500' : 'bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-slate-300' }}">
                                            {{ number_format($req['current_stock'], 2) }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center text-blue-400 font-bold">
                                        +{{ number_format($req['incoming'], 2) }}
                                    </td>
                                    <td class="px-8 py-6 text-center text-gray-500">
                                        {{ number_format($req['min_level'], 2) }}
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col gap-1">
                                            @if($req['suggestion_type'] == 'production')
                                                <span class="text-purple-400 text-[10px] font-black uppercase tracking-widest flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[12px]">factory</span>
                                                    Üretim: {{ number_format($req['suggestion_quantity'], 2) }} Adet
                                                </span>
                                            @else
                                                <span class="text-yellow-400 text-[10px] font-black uppercase tracking-widest flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[12px]">shopping_cart</span>
                                                    Satın Alma: {{ number_format($req['suggestion_quantity'], 2) }} Adet
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if($req['suggestion_type'] == 'production')
                                            <button @click="openModal = true; selectedProduct = '{{ $req['product']->id }}'; quantity = '{{ $req['suggestion_quantity'] }}'" class="px-4 py-2 rounded-xl bg-purple-500 hover:bg-purple-600 text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-purple-500/20 transition-all transform hover:-translate-y-0.5">
                                                Planla
                                            </button>
                                        @else
                                             <button class="px-4 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-black text-xs font-bold uppercase tracking-wider shadow-lg shadow-yellow-500/20 transition-all transform hover:-translate-y-0.5">
                                                Sipariş Ver
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-16 text-center text-slate-500 italic">Planlanması gereken bir ana ürün bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <!-- Raw Materials Requirements Table -->
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden rounded-[2.5rem] shadow-2xl">
                <div class="p-8 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-orange-500/[0.02]">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-orange-500/10 text-orange-500">
                            <span class="material-symbols-outlined text-[24px]">engineering</span>
                        </div>
                        Hammadde & Alt Bileşen İhtiyaçları (BOM Patlatma)
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-100 dark:bg-white/5 text-xs uppercase text-gray-700 dark:text-slate-400 font-bold tracking-wider">
                            <tr>
                                <th class="px-8 py-6">Bileşen Adı</th>
                                <th class="px-8 py-6 text-center">Toplam Gerekli Miktar</th>
                                <th class="px-8 py-6 text-center">Birim</th>
                                <th class="px-8 py-6 text-right">Durum</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($rawMaterialNeeds as $need)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-8 py-6">
                                        <span class="text-gray-900 dark:text-white font-bold">{{ $need['name'] }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-center text-orange-500 font-black text-lg">
                                        {{ number_format($need['quantity'], 2) }}
                                    </td>
                                    <td class="px-8 py-6 text-center text-slate-500 font-medium lowercase">
                                        {{ $need['unit'] }}
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <span class="px-3 py-1 rounded-lg bg-orange-500/10 text-orange-500 text-[10px] font-black uppercase tracking-widest border border-orange-500/20">
                                            Satın Alma Öneriliyor
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-16 text-center text-slate-500 italic">Üretim planlarına bağlı ek bir hammadde ihtiyacı yok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>

        <!-- Create Production Order Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openModal = false"></div>
            
            <div class="relative bg-white dark:bg-[#1e1e2d] rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-200 dark:border-white/10 transform transition-all"
                 x-show="openModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="px-8 py-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center bg-gray-50 dark:bg-white/[0.02]">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Hızlı Üretim Emri Oluştur</h3>
                    <button @click="openModal = false" class="text-gray-500 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form action="{{ route('manufacturing.mes.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <!-- Hidden: Order Number (Generated in Controller or needs handling) -->
                    <!-- We'll assume the controller handles order_number generation or we add a hidden rand for now -->
                     <input type="hidden" name="order_number" value="WO-MRP-{{ rand(1000, 9999) }}">
                    <input type="hidden" name="product_id" x-model="selectedProduct">
                    <input type="hidden" name="status" value="pending">

                    <div class="p-4 bg-purple-500/10 border border-purple-500/20 rounded-xl mb-6">
                        <p class="text-sm font-bold text-purple-400 flex items-center gap-2">
                             <span class="material-symbols-outlined text-[18px]">info</span>
                             Bu işlem, seçilen ürün için MRP önerisine dayalı bir iş emri oluşturacaktır.
                        </p>
                    </div>
                    
                    <div>
                         <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Üretilecek Miktar</label>
                        <input type="number" name="quantity" x-model="quantity" min="1" step="0.01" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                         <div>
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Planlanan Başlangıç</label>
                            <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                        </div>
                        <div>
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Termin Tarihi</label>
                            <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+3 days')) }}" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                        </div>
                    </div>

                     <div>
                         <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Notlar</label>
                        <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" placeholder="İş emri notları (Opsiyonel)"></textarea>
                    </div>


                    <div class="pt-6 flex justify-end gap-4 border-t border-gray-200 dark:border-white/10">
                        <button type="button" @click="openModal = false" class="px-6 py-3 rounded-xl text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 font-bold transition-colors">İptal</button>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-1">
                            Emir Oluştur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
