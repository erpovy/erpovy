<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
    <div x-data="posSystem()" class="fixed inset-0 z-50 bg-gray-100 dark:bg-[#0a0f1c] overflow-hidden flex flex-col font-sans select-none">

        {{-- ===== HEADER ===== --}}
        <header class="shrink-0 bg-white dark:bg-[#111827] border-b border-gray-200 dark:border-white/10 shadow-sm">
            {{-- Top row --}}
            <div class="flex items-center gap-3 px-3 py-2.5">
                <a href="{{ route('servicemanagement.index') }}" class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-500 dark:text-slate-400 hover:bg-primary/10 hover:text-primary transition-all shrink-0">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                </a>

                <div class="shrink-0">
                    <h1 class="text-sm font-black text-gray-900 dark:text-white tracking-tight leading-none uppercase">SERVİS POS</h1>
                    <p class="text-[9px] font-bold text-primary uppercase tracking-widest">X1M #S-01</p>
                </div>

                {{-- Search bar (grows) --}}
                <div class="flex-1 relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-base text-gray-400 dark:text-slate-500">search</span>
                    <input
                        type="text"
                        x-model="searchQuery"
                        @input.debounce.300ms="searchProducts()"
                        placeholder="Ürün ya da hizmet ara..."
                        class="w-full bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl py-2 pl-9 pr-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-primary focus:ring-0 transition-all text-sm"
                    >
                </div>

                {{-- Clock (hidden on small) --}}
                <div id="pos-clock" class="hidden md:block text-xs font-black text-gray-600 dark:text-slate-300 shrink-0 tabular-nums">--:--:--</div>

                <button @click="toggleFullscreen()" class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 hidden lg:flex items-center justify-center text-gray-500 dark:text-slate-400 hover:text-primary transition-all shrink-0">
                    <span class="material-symbols-outlined text-lg">fullscreen</span>
                </button>
            </div>

            {{-- Filter Pills --}}
            <div class="flex items-center gap-2 px-3 pb-2.5 overflow-x-auto no-scrollbar">
                <button @click="filterType = 'all'; searchProducts()"
                    :class="filterType === 'all' ? 'bg-primary text-white shadow-md shadow-primary/30' : 'bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 hover:bg-primary/10 hover:text-primary'"
                    class="flex-shrink-0 flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wide transition-all">
                    <span class="material-symbols-outlined text-sm">apps</span> Hepsi
                </button>
                <button @click="filterType = 'good'; searchProducts()"
                    :class="filterType === 'good' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 hover:text-blue-600'"
                    class="flex-shrink-0 flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wide transition-all">
                    <span class="material-symbols-outlined text-sm">inventory_2</span> Ürün
                </button>
                <button @click="filterType = 'service'; searchProducts()"
                    :class="filterType === 'service' ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 hover:bg-purple-50 dark:hover:bg-purple-500/10 hover:text-purple-600'"
                    class="flex-shrink-0 flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wide transition-all">
                    <span class="material-symbols-outlined text-sm">build</span> Hizmet
                </button>
                <div class="flex-1"></div>
                <span class="text-[10px] font-bold text-gray-400 dark:text-slate-500 shrink-0" x-text="products.length + ' sonuç'"></span>
            </div>
        </header>

        {{-- ===== MAIN AREA ===== --}}
        <div class="flex-1 flex overflow-hidden">

            {{-- ===== PRODUCT GRID ===== --}}
            <section class="flex-1 overflow-y-auto p-3 custom-scrollbar">
                {{-- Grid: 2 col on mobile, 3 on tablet, 4 on large --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-3 pb-24 lg:pb-4">
                    <template x-for="product in products" :key="product.id">
                        <div
                            @click="addToCart(product)"
                            class="group relative bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-white/5 rounded-2xl overflow-hidden hover:border-primary/60 hover:shadow-lg hover:shadow-primary/10 active:scale-95 transition-all cursor-pointer flex flex-col"
                        >
                            {{-- Product Icon Area --}}
                            <div class="relative h-24 sm:h-28 flex items-center justify-center"
                                :class="product.product_type && product.product_type.code === 'service' ? 'bg-purple-50 dark:bg-purple-900/20' : 'bg-blue-50 dark:bg-blue-900/20'">
                                <span class="material-symbols-outlined text-5xl transition-transform group-hover:scale-110 group-active:scale-90 duration-150"
                                    :class="product.product_type && product.product_type.code === 'service' ? 'text-purple-400 dark:text-purple-500' : 'text-blue-400 dark:text-blue-500'"
                                    x-text="(product.product_type && product.product_type.code === 'service') || product.type === 'service' ? 'build' : 'inventory_2'">
                                </span>
                                {{-- Stock badge --}}
                                <div x-show="product.type !== 'service' && !(product.product_type && product.product_type.code === 'service')"
                                    class="absolute top-2 right-2 px-2 py-0.5 bg-white/80 dark:bg-black/40 backdrop-blur-sm rounded-full text-[9px] font-black border border-gray-200 dark:border-white/10"
                                    :class="(product.stock || 0) <= 0 ? 'text-red-500 border-red-300 dark:border-red-500/30' : 'text-gray-600 dark:text-slate-300'">
                                    STK <span x-text="product.stock || 0"></span>
                                </div>
                                {{-- Type badge --}}
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[9px] font-black"
                                    :class="(product.product_type && product.product_type.code === 'service') || product.type === 'service' ? 'bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400' : 'bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400'">
                                    <span x-text="(product.product_type && product.product_type.code === 'service') || product.type === 'service' ? 'HİZMET' : 'ÜRÜN'"></span>
                                </div>
                                {{-- Tap feedback --}}
                                <div class="absolute inset-0 bg-white/30 dark:bg-white/10 opacity-0 group-active:opacity-100 transition-opacity duration-75 pointer-events-none"></div>
                            </div>

                            {{-- Product Info --}}
                            <div class="p-3 flex-1 flex flex-col justify-between gap-2">
                                <h3 class="text-xs font-black text-gray-900 dark:text-white leading-snug group-hover:text-primary transition-colors line-clamp-2" x-text="product.name"></h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-black text-primary" x-text="parseFloat(product.sale_price).toLocaleString('tr-TR', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' ₺'"></span>
                                    <div class="w-7 h-7 rounded-lg bg-primary/10 group-hover:bg-primary flex items-center justify-center text-primary group-hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-base">add</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Empty State --}}
                <div x-show="products.length === 0" class="h-64 flex flex-col items-center justify-center text-gray-300 dark:text-slate-700">
                    <span class="material-symbols-outlined text-7xl mb-3">search_off</span>
                    <p class="text-sm font-bold uppercase tracking-widest">Sonuç Bulunamadı</p>
                </div>
            </section>

            {{-- ===== DESKTOP SIDEBAR ===== --}}
            <aside class="hidden lg:flex w-80 xl:w-96 flex-col bg-white dark:bg-[#111827] border-l border-gray-200 dark:border-white/10 shrink-0 overflow-hidden">
                @include('servicemanagement::pos.partials.cart-panel')
            </aside>
        </div>

        {{-- ===== MOBILE: Floating Cart Button ===== --}}
        <div class="lg:hidden fixed bottom-5 right-4 z-50">
            <button @click="cartOpen = true"
                class="relative flex items-center gap-3 pl-4 pr-5 py-3.5 rounded-2xl bg-primary text-white font-black text-sm shadow-2xl shadow-primary/40 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-xl">shopping_cart</span>
                <span>Sepet</span>
                <template x-if="cart.length > 0">
                    <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 text-white text-[10px] font-black flex items-center justify-center shadow-lg" x-text="cart.length"></span>
                </template>
                <template x-if="cart.length > 0">
                    <span class="text-white/80 font-bold text-xs" x-text="formatNumber(total) + ' ₺'"></span>
                </template>
            </button>
        </div>

        {{-- ===== MOBILE: Cart Bottom Drawer ===== --}}
        <div x-show="cartOpen"
             x-cloak
             @click.self="cartOpen = false"
             class="lg:hidden fixed inset-0 z-40 bg-black/50 backdrop-blur-sm flex items-end"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            <div class="w-full bg-white dark:bg-[#111827] rounded-t-3xl shadow-2xl flex flex-col max-h-[92vh] overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-full"
                 x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-y-0"
                 x-transition:leave-end="translate-y-full">
                {{-- Drawer Handle --}}
                <div class="flex items-center justify-between px-4 pt-3 pb-2 border-b border-gray-100 dark:border-white/5 shrink-0">
                    <div class="w-10 h-1 bg-gray-300 dark:bg-white/20 rounded-full mx-auto absolute left-1/2 -translate-x-1/2 top-2"></div>
                    <h2 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Sepet</h2>
                    <button @click="cartOpen = false" class="w-8 h-8 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-500 dark:text-slate-400">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    @include('servicemanagement::pos.partials.cart-panel')
                </div>
            </div>
        </div>

        {{-- ===== SUCCESS MODAL ===== --}}
        <div x-show="showSuccessModal"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             style="display: none;">
            <div class="bg-white dark:bg-[#1e293b] border border-gray-200 dark:border-white/10 rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-green-400 to-emerald-500 rounded-t-3xl"></div>
                <div class="w-20 h-20 rounded-full bg-emerald-500/15 text-emerald-500 flex items-center justify-center mx-auto mb-5 animate-bounce">
                    <span class="material-symbols-outlined text-5xl">check_circle</span>
                </div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2 uppercase tracking-tighter">Servis Tamamlandı!</h2>
                <p class="text-sm text-gray-500 dark:text-slate-400 mb-8">İşlem başarıyla kaydedildi.</p>
                <button @click="closeSuccessModal()"
                    class="w-full py-3.5 rounded-2xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-black text-sm uppercase tracking-wider hover:opacity-90 transition-all active:scale-95">
                    TAMAM
                </button>
            </div>
        </div>

        {{-- ===== OCR OVERLAY ===== --}}
        <div x-show="isOcrScanning" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center bg-slate-950/85 backdrop-blur-md">
            <div class="text-center max-w-sm w-full px-6">
                <div x-show="ocrPreview" class="mb-6 rounded-2xl overflow-hidden border-2 border-primary/40 shadow-2xl">
                    <img :src="ocrPreview" class="w-full h-auto max-h-52 object-contain bg-slate-900" alt="OCR Target">
                    <div class="bg-primary/10 py-2 text-[10px] text-primary font-black uppercase tracking-widest border-t border-primary/20">ODAKLANAN BÖLGE</div>
                </div>
                <div x-show="!ocrPreview" class="relative w-28 h-28 mx-auto mb-6">
                    <div class="absolute inset-0 border-4 border-primary/20 rounded-2xl"></div>
                    <div class="absolute inset-x-0 top-0 h-1 bg-primary rounded-full animate-[scan_2s_ease-in-out_infinite] shadow-[0_0_20px_#137fec]"></div>
                    <span class="material-symbols-outlined text-[56px] text-primary absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">document_scanner</span>
                </div>
                <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tighter" x-text="ocrStatus">Karakterler Okunuyor</h3>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest animate-pulse">Lütfen bekleyin...</p>
            </div>
        </div>
    </div>

    {{-- ===== NOTIFICATION TOAST ===== --}}
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
        class="fixed bottom-20 lg:bottom-6 left-1/2 -translate-x-1/2 z-[110] px-6 py-4 rounded-2xl border backdrop-blur-xl shadow-2xl flex items-center gap-3 max-w-xs w-full"
        :class="type === 'success' ? 'bg-emerald-50/90 dark:bg-emerald-500/15 border-emerald-300 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-400' : 'bg-red-50/90 dark:bg-red-500/15 border-red-300 dark:border-red-500/30 text-red-700 dark:text-red-400'"
        style="display: none;">
        <div :class="type === 'success' ? 'bg-emerald-500' : 'bg-red-500'" class="w-2.5 h-2.5 rounded-full animate-pulse shrink-0"></div>
        <span class="font-bold text-sm" x-text="message"></span>
    </div>

    <style>
        @keyframes slide-in { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        .animate-slide-in { animation: slide-in 0.25s ease-out forwards; }
        @keyframes scan { 0%, 100% { top: 0; } 50% { top: calc(100% - 4px); } }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.08); border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.06); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>

    <script>
        function posSystem() {
            return {
                searchQuery: '',
                filterType: 'all',
                products: [],
                cart: [],
                cartOpen: false,
                selectedCustomer: '',
                paymentMethod: 'cash',
                receivedAmount: 0,
                changeAmount: 0,
                isProcessing: false,
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
                    const target = document.getElementById('pos-clock');
                    if (target) target.textContent = new Date().toLocaleTimeString('tr-TR');
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
                        this.cart.unshift({
                            product_id: product.id,
                            name: product.name,
                            sale_price: product.sale_price,
                            vat_rate: product.vat_rate,
                            quantity: 1,
                            discount_rate: 0
                        });
                    }
                    this.calculateTotals();
                    this.notify('Eklendi: ' + product.name);
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

                calculateTotals() {
                    let sub = 0, tax = 0, disc = 0;
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
                    this.changeAmount = (this.paymentMethod === 'cash' && this.receivedAmount > this.total)
                        ? this.receivedAmount - this.total : 0;
                },

                async checkout() {
                    if (this.cart.length === 0) return;
                    this.isProcessing = true;
                    try {
                        const response = await fetch(`{{ route('servicemanagement.pos.checkout') }}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
                            this.cartOpen = false;
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
                            if (this.currentMileage === 0) this.currentMileage = result.current_mileage;
                            if (result.maintenance_status === 'overdue') this.notify('DİKKAT: Bakım zamanı geçmiş!', 'error');
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
                                const grayData = new Uint8ClampedArray(data);
                                for (let i = 0; i < grayData.length; i += 4) {
                                    const gray = (data[i] * 0.299 + data[i+1] * 0.587 + data[i+2] * 0.114);
                                    grayData[i] = grayData[i+1] = grayData[i+2] = gray;
                                }
                                const hist = new Array(256).fill(0);
                                for (let i = 0; i < grayData.length; i += 4) hist[Math.round(grayData[i])]++;
                                let total = grayData.length / 4, sum = 0;
                                for (let i = 0; i < 256; i++) sum += i * hist[i];
                                let sumB = 0, wB = 0, wF = 0, varMax = 0, threshold = 0;
                                for (let i = 0; i < 256; i++) {
                                    wB += hist[i]; if (wB === 0) continue;
                                    wF = total - wB; if (wF === 0) break;
                                    sumB += i * hist[i];
                                    let mB = sumB / wB, mF = (sum - sumB) / wF;
                                    let varBetween = wB * wF * (mB - mF) * (mB - mF);
                                    if (varBetween > varMax) { varMax = varBetween; threshold = i; }
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
                    let score = 0;
                    if (/[0-8][0-9][A-Z]{1,3}[0-9]{2,4}/.test(cleaned)) {
                        score += 100;
                        if (cleaned.length === 7 || cleaned.length === 8) score += 50;
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
                        this.ocrStatus = 'Taranıyor...';
                        const worker = await Tesseract.createWorker('eng', 1);
                        await worker.setParameters({
                            tessedit_char_whitelist: '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
                            tessedit_pageseg_mode: '11',
                        });
                        const res1 = await worker.recognize(pass1);
                        const res2 = await worker.recognize(pass2);
                        await worker.terminate();
                        const score1 = this.scoreResult(res1.data.text);
                        const score2 = this.scoreResult(res2.data.text);
                        let bestText = score1 >= score2 ? res1.data.text : res2.data.text;
                        const raw = (bestText || '').toUpperCase().replace(/[^A-Z0-9]/g, '');
                        const plateRegex = /([0-8][0-9][A-Z]{1,3}[0-9]{2,4})/;
                        const plateMatch = raw.match(plateRegex);
                        if (plateMatch) {
                            let candidate = plateMatch[0];
                            if (candidate.length === 9 && /[0-9]{4}$/.test(candidate)) candidate = candidate.substring(0, 8);
                            this.plateNumber = candidate;
                            this.checkVehicleStatus();
                            this.notify('Plaka doğrulandı: ' + this.plateNumber);
                        } else if (raw.length >= 5) {
                            this.plateNumber = raw.substring(0, 10);
                            this.notify('Plaka yakalandı (Tahmin): ' + this.plateNumber);
                        } else {
                            this.notify('Okuma başarısız. Tekrar deneyin.', 'error');
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
                    return parseFloat(val || 0).toLocaleString('tr-TR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }
    </script>
</x-app-layout>
