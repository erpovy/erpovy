<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
    <div x-data="posSystem()" class="fixed inset-0 z-50 bg-gray-50 dark:bg-[#0f172a] overflow-hidden flex flex-col font-sans select-none">
        <!-- POS Header -->
        <header class="h-14 bg-white dark:bg-white/5 border-b border-gray-200 dark:border-white/10 backdrop-blur-3xl flex items-center justify-between px-4 shrink-0">
            <div class="flex items-center gap-6">
                <a href="{{ route('servicemanagement.index') }}" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-500 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-white/10 hover:text-gray-900 dark:hover:text-white transition-all group shrink-0">
                    <span class="material-symbols-outlined text-lg group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                </a>
                <div class="shrink-0">
                    <h1 class="text-sm font-black text-gray-900 dark:text-white tracking-tight leading-none uppercase">SERVİS POS</h1>
                    <p class="text-[8px] font-bold text-primary uppercase tracking-widest">X1M #S-01</p>
                </div>
            </div>

            <div class="flex-1 max-w-md px-4">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-3 flex items-center text-gray-400 dark:text-slate-500 group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </div>
                    <input 
                        type="text" 
                        x-model="searchQuery" 
                        @input.debounce.300ms="searchProducts()"
                        placeholder="Ürün ismi veya barkod..." 
                        class="w-full bg-gray-100 dark:bg-white/5 border-gray-200 dark:border-white/10 border rounded-xl py-1.5 pl-10 pr-4 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-slate-500 focus:border-primary focus:ring-0 transition-all font-medium text-xs"
                    >
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Quick Filters -->
                <div class="flex bg-gray-100 dark:bg-white/5 p-0.5 rounded-lg border border-gray-200 dark:border-white/10">
                    <button @click="filterType = 'all'; searchProducts()" :class="filterType === 'all' ? 'bg-primary text-white' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="px-3 py-1 rounded-md text-[10px] font-black uppercase transition-all">HEPSİ</button>
                    <button @click="filterType = 'good'; searchProducts()" :class="filterType === 'good' ? 'bg-primary text-white' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="px-3 py-1 rounded-md text-[10px] font-black uppercase transition-all">ÜRÜN</button>
                    <button @click="filterType = 'service'; searchProducts()" :class="filterType === 'service' ? 'bg-primary text-white' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="px-3 py-1 rounded-md text-[10px] font-black uppercase transition-all">HİZMET</button>
                </div>

                <div class="text-right hidden sm:block shrink-0">
                    <div class="text-xs font-black text-gray-900 dark:text-white" id="pos-clock">--:--:--</div>
                </div>
                
                <button @click="toggleFullscreen()" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-center text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-all shrink-0">
                    <span class="material-symbols-outlined text-lg">fullscreen</span>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col md:flex-row overflow-hidden">
            <!-- Left: Products Grid -->
            <section class="flex-1 overflow-y-auto p-2 lg:p-3 custom-scrollbar bg-gray-50 dark:bg-[#0f172a]">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 2xl:grid-cols-5 gap-2">
                    <template x-for="product in products" :key="product.id">
                        <div 
                            @click="addToCart(product)"
                            class="group relative bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 rounded-xl p-2 hover:border-primary/50 hover:bg-primary/5 transition-all cursor-pointer flex flex-col h-full"
                        >
                            <div class="aspect-[16/10] rounded-lg bg-gray-100 dark:bg-slate-900/50 flex items-center justify-center mb-1.5 overflow-hidden relative shrink-0">
                                <span class="material-symbols-outlined text-2xl text-gray-400 dark:text-slate-700 group-hover:scale-105 transition-transform" x-text="product.type === 'service' ? 'eco' : 'inventory_2'"></span>
                                <div x-show="product.type !== 'service'" class="absolute bottom-1 right-1 px-1.5 py-0.5 bg-black/60 backdrop-blur-md rounded text-[7px] font-black text-white uppercase tracking-tighter">
                                    STK:<span x-text="product.stock || 0"></span>
                                </div>
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="text-[10px] font-black text-gray-900 dark:text-white group-hover:text-primary transition-colors leading-[1.1] mb-0.5" x-text="product.name"></h3>
                            </div>

                            <div class="flex items-center justify-end mt-1 pt-1 border-t border-gray-100 dark:border-white/5">
                                <div class="w-6 h-6 rounded-md bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-center text-gray-400 dark:text-slate-400 group-hover:bg-primary group-hover:text-white transition-all scale-75">
                                    <span class="material-symbols-outlined text-sm">add</span>
                                </div>
                            </div>
                            <!-- Click Wave Effect -->
                            <div class="absolute inset-0 bg-primary/20 opacity-0 group-active:opacity-100 transition-opacity duration-100 pointer-events-none"></div>
                        </div>
                    </template>
                </div>

                <!-- Empty State -->
                <div x-show="products.length === 0" class="h-full flex flex-col items-center justify-center opacity-20">
                    <span class="material-symbols-outlined text-[150px] mb-6 animate-pulse">search_off</span>
                    <h3 class="text-2xl font-black uppercase tracking-[0.4em]">Sonuç Bulunamadı</h3>
                </div>
            </section>

            <!-- Right: Checkout Sidebar -->
            <aside class="w-full md:w-64 lg:w-72 xl:w-80 bg-white dark:bg-[#1e293b]/80 border-l border-gray-200 dark:border-white/10 backdrop-blur-3xl flex flex-col shrink-0 overflow-hidden">
                <!-- Customer & Actions -->
                <div class="p-2.5 border-b border-gray-200 dark:border-white/10 flex items-center gap-2">
                    <div class="flex-1 relative group">
                        <div class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors">
                            <span class="material-symbols-outlined text-sm">person_search</span>
                        </div>
                        <select 
                            x-model="selectedCustomer" 
                            class="w-full bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-lg py-1.5 pl-8 pr-2 text-gray-900 dark:text-white focus:border-primary focus:ring-0 text-[10px] font-bold transition-all"
                        >
                            <option value="" class="bg-white dark:bg-[#0f172a]">Genel Müşteri</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}" class="bg-white dark:bg-[#0f172a]">{{ $contact->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button @click="clearCart()" class="w-8 h-8 rounded-lg bg-red-500/10 text-red-500 border border-red-500/10 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center shrink-0" title="Temizle">
                        <span class="material-symbols-outlined text-base">delete_sweep</span>
                    </button>
                </div>

                <!-- Cart Items -->
                <div class="flex-1 overflow-y-auto p-2 space-y-1.5 custom-scrollbar">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="relative group animate-slide-in">
                            <div class="flex items-center gap-2 bg-gray-50 dark:bg-white/5 rounded-xl p-2 border border-gray-200 dark:border-white/5 hover:border-gray-300 dark:hover:border-white/10 transition-all">
                                <div class="w-7 h-7 rounded bg-gray-200 dark:bg-slate-900/50 flex items-center justify-center text-gray-500 dark:text-slate-600 shrink-0">
                                    <span class="material-symbols-outlined text-lg">shopping_basket</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[9px] font-black text-gray-900 dark:text-white truncate pr-4" x-text="item.name"></div>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <div class="flex items-center bg-gray-100 dark:bg-white/5 rounded border border-gray-200 dark:border-white/10 px-0.5">
                                            <button @click="updateQty(index, -1)" class="w-4 h-4 hover:text-red-500 transition-all flex items-center justify-center">
                                                <span class="material-symbols-outlined text-[10px]">remove</span>
                                            </button>
                                            <input type="number" x-model.number="item.quantity" @input="calculateTotals()" class="w-6 bg-transparent border-0 text-center text-[10px] font-black text-primary py-0 px-0 focus:ring-0">
                                            <button @click="updateQty(index, 1)" class="w-4 h-4 hover:text-green-500 transition-all flex items-center justify-center">
                                                <span class="material-symbols-outlined text-[10px]">add</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Line Discount -->
                                    <div class="mt-0.5 flex items-center gap-1.5">
                                        <span class="text-[7px] font-black text-gray-500 dark:text-slate-600 uppercase">İND %</span>
                                        <input type="number" x-model.number="item.discount_rate" @input="calculateTotals()" class="w-8 bg-gray-100 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded text-center text-[8px] font-black text-orange-400 py-0 px-0 focus:border-orange-500 focus:ring-0">
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <button @click="removeFromCart(index)" class="absolute top-1.5 right-1.5 text-gray-400 dark:text-slate-600 hover:text-red-500 transition-colors">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="cart.length === 0" class="flex-1 flex flex-col items-center justify-center py-12 px-6">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 rounded-3xl bg-primary/5 flex items-center justify-center group relative overflow-hidden">
                                <span class="material-symbols-outlined text-3xl text-primary/20 group-hover:scale-110 transition-transform">shopping_cart</span>
                                <div class="absolute inset-0 bg-gradient-to-tr from-primary/10 to-transparent"></div>
                            </div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-500/20 border border-red-500/30 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[10px] text-red-500 font-bold">add</span>
                            </div>
                        </div>
                        <h4 class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-[0.2em] mb-1">Sepetiniz Boş</h4>
                        <p class="text-[8px] text-gray-500 dark:text-slate-500 text-center leading-relaxed max-w-[140px]">Lütfen servis satışına başlamak için bir ürün veya hizmet seçin.</p>
                    </div>
                </div>

                <!-- Vehicle & Maintenance Info -->
                <div class="px-3 py-2 bg-gray-50/50 dark:bg-white/5 border-t border-gray-200 dark:border-white/10 space-y-2">
                    <!-- Maintenance Alert Badge -->
                    <template x-if="vehicleStatus && vehicleStatus.status === 'exists'">
                        <div class="flex items-center justify-between px-3 py-1.5 rounded-lg border animate-pulse"
                            :class="{
                                'bg-rose-500/10 border-rose-500/20 text-rose-500': vehicleStatus.maintenance_status === 'overdue',
                                'bg-amber-500/10 border-amber-500/20 text-amber-500': vehicleStatus.maintenance_status === 'upcoming',
                                'bg-emerald-500/10 border-emerald-500/20 text-emerald-500': vehicleStatus.maintenance_status === 'healthy'
                            }"
                        >
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm" x-text="vehicleStatus.maintenance_status === 'healthy' ? 'check_circle' : 'warning'"></span>
                                <span class="text-[9px] font-black uppercase tracking-widest" x-text="vehicleStatus.maintenance_status === 'overdue' ? 'KRİTİK BAKIM ZAMANI' : (vehicleStatus.maintenance_status === 'upcoming' ? 'BAKIM YAKLAŞTI' : 'ARAÇ DURUMU İYİ')"></span>
                            </div>
                            <span class="text-[9px] font-bold opacity-70" x-text="vehicleStatus.brand + ' ' + vehicleStatus.model"></span>
                        </div>
                    </template>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-[8px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest pl-1 text-left">ARAÇ PLAKASI</label>
                        <div class="relative flex items-stretch h-14 bg-white dark:bg-slate-900 border-2 border-gray-900 dark:border-white rounded-lg overflow-hidden shadow-sm group">
                            <!-- TR Stripe -->
                            <div class="w-8 bg-blue-700 flex flex-col items-center justify-end pb-2 shrink-0">
                                <span class="text-[8px] font-black text-white leading-none">TR</span>
                            </div>
                            <!-- Input -->
                            <input 
                                type="text" 
                                x-model="plateNumber" 
                                @input.debounce.500ms="checkVehicleStatus()"
                                placeholder="34 ABC 123" 
                                class="flex-1 min-w-0 bg-transparent border-0 text-center text-2xl font-black text-gray-900 dark:text-white uppercase tracking-[0.1em] focus:ring-0 py-0 placeholder:text-gray-200 dark:placeholder:text-white/10"
                            >
                            <!-- OCR Trigger -->
                            <button 
                                @click="$refs.ocrInput.click()"
                                class="w-14 flex items-center justify-center bg-gray-100 dark:bg-white/5 hover:bg-primary hover:text-white dark:hover:bg-primary transition-all border-l border-gray-900 dark:border-white group/ocr shrink-0"
                                title="Plaka Fotoğrafı Okut"
                            >
                                <span class="material-symbols-outlined text-2xl group-hover/ocr:scale-110 transition-transform">photo_camera</span>
                            </button>
                            <input 
                                type="file" 
                                x-ref="ocrInput" 
                                @change="handleOcr($event)" 
                                accept="image/*" 
                                class="hidden"
                                capture="environment"
                            >
                        </div>
                    </div>

                    <!-- Current Mileage Input -->
                    <div class="flex flex-col gap-1.5 mt-2">
                        <label class="text-[8px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest pl-1 text-left">GÜNCEL KM</label>
                        <div class="relative group">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-sm text-gray-400">speed</span>
                            <input 
                                type="number" 
                                x-model.number="currentMileage"
                                placeholder="0" 
                                class="w-full bg-white dark:bg-slate-900 border-2 border-gray-200 dark:border-white/10 rounded-lg py-2 pl-9 pr-4 text-sm font-black text-gray-900 dark:text-white focus:border-primary focus:ring-0 transition-all"
                            >
                        </div>
                    </div>
                </div>

                <!-- Footer / Checkout -->
                <div class="p-3 bg-gray-50/80 dark:bg-[#0f172a]/80 backdrop-blur-4xl border-t border-gray-200 dark:border-white/10 space-y-3 shrink-0">
                    <!-- Checkout Button Container -->
                    <div class="pt-1">
                        <button 
                            @click="checkout()"
                            :disabled="cart.length === 0 || isProcessing"
                            class="w-full py-3 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-primary/30 hover:shadow-primary/50 transition-all disabled:opacity-30 disabled:grayscale disabled:pointer-events-none active:scale-95 group relative overflow-hidden shrink-0"
                        >
                            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                            <span x-show="!isProcessing" class="flex items-center justify-center gap-2 relative z-10">
                                SERVİSİ TAMAMLA
                                <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">rocket_launch</span>
                            </span>
                            <span x-show="isProcessing" class="flex items-center justify-center gap-2 relative z-10">
                                <span class="animate-spin material-symbols-outlined text-lg">sync</span>
                                İŞLENİYOR...
                            </span>
                        </button>
                    </div>
                </div>
            </aside>
        </main>

        <!-- Checkout Success Modal -->
        <div x-show="showSuccessModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" style="display: none;">
            <div class="bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-white/10 rounded-xl p-6 max-w-sm w-full text-center shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-1 bg-primary"></div>
                <div class="w-16 h-16 rounded-full bg-green-500/20 text-green-500 flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <span class="material-symbols-outlined text-4xl">check_circle</span>
                </div>
                <h2 class="text-xl font-black text-gray-900 dark:text-white mb-2 uppercase tracking-tighter">Servis Tamamlandı!</h2>
                <div class="text-xs text-gray-500 dark:text-slate-400 mb-6">İşlem başarıyla kaydedildi.</div>
                
                <button 
                    @click="closeSuccessModal()" 
                    class="w-full py-2 rounded-lg bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white font-black text-xs uppercase hover:bg-gray-200 dark:hover:bg-white/10 transition-all"
                >
                    TAMAM
                </button>
            </div>
        </div>

        <!-- OCR Scanning Overlay -->
        <div x-show="isOcrScanning" 
             x-cloak
             class="fixed inset-0 z-[120] flex items-center justify-center bg-slate-950/80 backdrop-blur-md">
            <div class="text-center max-w-md px-6">
                <!-- Preview of what the OCR is seeing -->
                <div x-show="ocrPreview" class="mb-6 rounded-2xl overflow-hidden border-2 border-primary/30 shadow-2xl">
                    <img :src="ocrPreview" class="w-full h-auto max-h-48 object-contain bg-slate-900" alt="OCR Target">
                    <div class="bg-primary/10 py-2 text-[10px] text-primary font-black uppercase tracking-widest border-t border-primary/20">ODAKLANAN BÖLGE</div>
                </div>

                <div x-show="!ocrPreview" class="relative w-24 h-24 mx-auto mb-6">
                    <div class="absolute inset-0 border-4 border-primary/20 rounded-2xl"></div>
                    <div class="absolute inset-x-0 top-0 h-0.5 bg-primary animate-[scan_2s_ease-in-out_infinite] shadow-[0_0_15px_#137fec]"></div>
                    <span class="material-symbols-outlined text-[48px] text-primary absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">document_scanner</span>
                </div>
                
                <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tighter" x-text="ocrStatus">Karakterler Okunuyor</h3>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest animate-pulse">Lütfen bekleyin...</p>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div 
        x-data="{ show: false, message: '', type: 'success' }" 
        x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition duration-300 ease-out"
        x-transition:enter-start="opacity-0 translate-y-8"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition duration-200 ease-in"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-8"
        class="fixed bottom-12 left-1/2 -translate-x-1/2 z-[110] px-10 py-5 rounded-[2rem] border-2 backdrop-blur-4xl shadow-3xl flex items-center gap-6"
        :class="type === 'success' ? 'bg-green-500/10 border-green-500/20 text-green-400' : 'bg-red-500/10 border-red-500/20 text-red-400'"
        style="display: none;"
    >
        <div :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'" class="w-3 h-3 rounded-full animate-pulse"></div>
        <span class="font-black text-sm uppercase tracking-[0.2em]" x-text="message"></span>
    </div>

    <style>
        @keyframes slide-in { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        .animate-slide-in { animation: slide-in 0.3s ease-out forwards; }
        @keyframes shimmer { 100% { transform: translateX(100%); } }
        .group-hover\:animate-shimmer { animation: shimmer 1.5s infinite; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.1); }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        
        @keyframes scan {
            0%, 100% { top: 0; }
            50% { top: 100%; }
        }
    </style>

    <script>
        function posSystem() {
            return {
                searchQuery: '',
                filterType: 'all',
                products: [],
                cart: [],
                selectedCustomer: '',
                paymentMethod: 'cash',
                receivedAmount: 0,
                changeAmount: 0,
                isProcessing: false,
                // OCR Properties
                isOcrScanning: false,
                ocrStatus: 'Analiz Ediliyor',
                ocrPreview: null,
                plateNumber: '',
                subtotal: 0,
                taxTotal: 0,
                discountTotal: 0,
                total: 0,
                currentMileage: 0,
                vehicleStatus: null,
                showSuccessModal: false,

                init() {
                    this.searchProducts();
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);
                },

                updateClock() {
                    const now = new Date();
                    const target = document.getElementById('pos-clock');
                    if (target) target.textContent = now.toLocaleTimeString('tr-TR');
                },

                async searchProducts() {
                    try {
                        let url = `{{ route('servicemanagement.pos.products') }}?search=${this.searchQuery}`;
                        if (this.filterType !== 'all') url += `&type=${this.filterType}`;
                        const response = await fetch(url);
                        this.products = await response.json();
                    } catch (error) {
                        console.error('Ürün araması başarısız:', error);
                    }
                },

                addToCart(product) {
                    const existingItem = this.cart.find(item => item.product_id === product.id);
                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        this.cart.unshift({ // Add to top
                            product_id: product.id,
                            name: product.name,
                            sale_price: product.sale_price,
                            vat_rate: product.vat_rate,
                            quantity: 1,
                            discount_rate: 0
                        });
                    }
                    this.calculateTotals();
                    this.notify('Ürün/Hizmet eklendi');
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.calculateTotals();
                },

                updateQty(index, delta) {
                    this.cart[index].quantity += delta;
                    if (this.cart[index].quantity <= 0) this.removeFromCart(index);
                    this.calculateTotals();
                },

                calculateLineTotal(item) {
                    const baseTotal = item.quantity * item.sale_price;
                    const discount = baseTotal * (item.discount_rate / 100);
                    return baseTotal - discount;
                },

                calculateTotals() {
                    let sub = 0;
                    let tax = 0;
                    let disc = 0;

                    this.cart.forEach(item => {
                        const lineBase = item.quantity * item.sale_price;
                        const lineDiscount = lineBase * (item.discount_rate / 100);
                        const lineSubAfterDisc = lineBase - lineDiscount;
                        
                        sub += lineBase;
                        disc += lineDiscount;
                        tax += lineSubAfterDisc * (item.vat_rate / 100);
                    });

                    this.subtotal = sub;
                    this.discountTotal = disc;
                    this.taxTotal = tax;
                    this.total = sub - disc + tax;
                    this.calculateChange();
                },

                calculateChange() {
                    if (this.paymentMethod === 'cash' && this.receivedAmount > this.total) {
                        this.changeAmount = this.receivedAmount - this.total;
                    } else {
                        this.changeAmount = 0;
                    }
                },

                async checkout() {
                    if (this.cart.length === 0) return;
                    this.isProcessing = true;
                    
                    try {
                        const response = await fetch(`{{ route('servicemanagement.pos.checkout') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                items: this.cart,
                                contact_id: this.selectedCustomer,
                                payment_method: this.paymentMethod,
                                discount_total: this.discountTotal,
                                received_amount: this.receivedAmount,
                                plate_number: this.plateNumber,
                                current_mileage: this.currentMileage
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.showSuccessModal = true;
                        } else {
                            this.notify(result.message || 'Bir hata oluştu', 'error');
                        }
                    } catch (error) {
                        this.notify('Sistem hatası!', 'error');
                    } finally {
                        this.isProcessing = false;
                    }
                },

                closeSuccessModal() {
                    this.showSuccessModal = false;
                    this.cart = [];
                    this.receivedAmount = 0;
                    this.changeAmount = 0;
                    this.plateNumber = '';
                    this.currentMileage = 0;
                    this.vehicleStatus = null;
                    this.calculateTotals();
                    this.searchProducts();
                },

                async checkVehicleStatus() {
                    if (!this.plateNumber || this.plateNumber.length < 5) {
                        this.vehicleStatus = null;
                        return;
                    }

                    try {
                        const cleanPlate = this.plateNumber.replace(/\s+/g, '');
                        const response = await fetch(`/service-management/api/vehicle-status/${encodeURIComponent(cleanPlate)}`);
                        const result = await response.json();
                        this.vehicleStatus = result;
                        
                        if (result.status === 'exists') {
                            if (this.currentMileage === 0) {
                                this.currentMileage = result.current_mileage;
                            }
                            if (result.maintenance_status === 'overdue') {
                                this.notify('DİKKAT: Aracın periyodik bakım zamanı geçmiş!', 'error');
                            }
                        }
                    } catch (error) {
                        console.error('Araç durumu kontrol edilemedi:', error);
                    }
                },

                clearCart() {
                    if (confirm('Sepeti temizlemek istediğinize emin misiniz?')) {
                        this.cart = [];
                        this.calculateTotals();
                    }
                },

                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen();
                    } else {
                        if (document.exitFullscreen) document.exitFullscreen();
                    }
                },

                preprocessImage(file) {
                    return new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const img = new Image();
                            img.onload = () => {
                                const canvas = document.createElement('canvas');
                                const ctx = canvas.getContext('2d');
                                
                                const focusWidth = img.width * 0.8;
                                const focusHeight = img.height * 0.5;
                                const focusX = (img.width - focusWidth) / 2;
                                const focusY = img.height * 0.4;
                                
                                canvas.width = focusWidth;
                                canvas.height = focusHeight;
                                ctx.drawImage(img, focusX, focusY, focusWidth, focusHeight, 0, 0, focusWidth, focusHeight);

                                const imageData = ctx.getImageData(0, 0, focusWidth, focusHeight);
                                const data = imageData.data;
                                
                                // Copy for Pass 1 (Grayscale)
                                const grayData = new Uint8ClampedArray(data);
                                for (let i = 0; i < grayData.length; i += 4) {
                                    const gray = (data[i] * 0.299 + data[i+1] * 0.587 + data[i+2] * 0.114);
                                    grayData[i] = grayData[i+1] = grayData[i+2] = gray;
                                }
                                
                                // Thresholding logic for Pass 2
                                const hist = new Array(256).fill(0);
                                for (let i = 0; i < grayData.length; i += 4) hist[Math.round(grayData[i])]++;
                                
                                let total = grayData.length / 4, sum = 0;
                                for (let i = 0; i < 256; i++) sum += i * hist[i];
                                let sumB = 0, wB = 0, wF = 0, varMax = 0, threshold = 0;
                                for (let i = 0; i < 256; i++) {
                                    wB += hist[i];
                                    if (wB === 0) continue;
                                    wF = total - wB;
                                    if (wF === 0) break;
                                    sumB += i * hist[i];
                                    let mB = sumB / wB;
                                    let mF = (sum - sumB) / wF;
                                    let varBetween = wB * wF * (mB - mF) * (mB - mF);
                                    if (varBetween > varMax) {
                                        varMax = varBetween;
                                        threshold = i;
                                    }
                                }

                                const pass2Data = new ImageData(new Uint8ClampedArray(grayData), focusWidth, focusHeight);
                                for (let i = 0; i < pass2Data.data.length; i += 4) {
                                    const v = pass2Data.data[i] > threshold ? 255 : 0;
                                    pass2Data.data[i] = pass2Data.data[i+1] = pass2Data.data[i+2] = v;
                                }

                                ctx.putImageData(new ImageData(grayData, focusWidth, focusHeight), 0, 0);
                                const pass1Url = canvas.toDataURL('image/jpeg', 0.9);
                                
                                ctx.putImageData(pass2Data, 0, 0);
                                const pass2Url = canvas.toDataURL('image/jpeg', 0.9);

                                this.ocrPreview = pass2Url;
                                resolve({ pass1: pass1Url, pass2: pass2Url });
                            };
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                },

                scoreResult(text) {
                    if (!text) return -1;
                    const cleaned = text.toUpperCase().replace(/[^A-Z0-9]/g, '');
                    
                    // Standard Turkish Patterns:
                    // 1. 00 A 0000 (7)
                    // 2. 00 AA 000 (7)
                    // 3. 00 AAA 00 (7)
                    // 4. 00 AA 0000 (8)
                    // 5. 00 AAA 000 (8)
                    
                    let score = 0;
                    if (/[0-8][0-9][A-Z]{1,3}[0-9]{2,4}/.test(cleaned)) {
                        score += 100;
                        // High priority for standard lengths (7 or 8)
                        if (cleaned.length === 7 || cleaned.length === 8) score += 50;
                        // Penalty for unusually long plates (likely noise)
                        if (cleaned.length > 8) score -= (cleaned.length - 8) * 10;
                    }

                    return score + cleaned.length;
                },

                async handleOcr(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    this.isOcrScanning = true;
                    this.ocrStatus = 'Hazırlanıyor...';
                    
                    try {
                        const { pass1, pass2 } = await this.preprocessImage(file);
                        this.ocrStatus = 'Taranıyor (Hassas)...';

                        const worker = await Tesseract.createWorker('eng', 1);
                        await worker.setParameters({
                            tessedit_char_whitelist: '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                            tessedit_pageseg_mode: '11',
                        });

                        // Recognition Pass 1
                        const res1 = await worker.recognize(pass1);
                        // Recognition Pass 2
                        const res2 = await worker.recognize(pass2);
                        await worker.terminate();

                        const score1 = this.scoreResult(res1.data.text);
                        const score2 = this.scoreResult(res2.data.text);
                        
                        let bestText = score1 >= score2 ? res1.data.text : res2.data.text;
                        console.log('Pass 1 Score:', score1, 'Text:', res1.data.text);
                        console.log('Pass 2 Score:', score2, 'Text:', res2.data.text);

                        const raw = (bestText || '').toUpperCase().replace(/[^A-Z0-9]/g, '');
                        
                        // Refined Regex for Turkish plates (prioritizes standard formats)
                        const plateRegex = /([0-8][0-9][A-Z]{1,3}[0-9]{2,4})/;
                        const plateMatch = raw.match(plateRegex);
                        
                        if (plateMatch) {
                            let candidate = plateMatch[0];
                            
                            // Heuristic: If we have 4 digits at the end but the total length is 9, 
                            // it's highly likely the last digit is noise (like a frame edge interpreted as '5' or '1')
                            if (candidate.length === 9 && /[0-9]{4}$/.test(candidate)) {
                                candidate = candidate.substring(0, 8);
                            }

                            this.plateNumber = candidate;
                            this.checkVehicleStatus();
                            this.notify('Plaka doğrulandı: ' + this.plateNumber);
                        } else if (raw.length >= 5) {
                            this.plateNumber = raw.substring(0, 10);
                            this.notify('Plaka yakalandı (Tahmin): ' + this.plateNumber);
                        } else {
                            this.notify('Okuma başarısız. Lütfen tekrar deneyin.', 'error');
                        }
                    } catch (err) {
                        this.notify('Sistem Hatası: ' + err.message, 'error');
                    } finally {
                        this.isOcrScanning = false;
                        event.target.value = '';
                    }
                },

                notify(message, type = 'success') {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message, type } }));
                },

                formatNumber(val) {
                    return parseFloat(val).toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }
    </script>
</x-app-layout>
