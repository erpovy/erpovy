<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

    <div x-data="posSystem()" class="fixed inset-0 z-50 overflow-hidden flex flex-col select-none" style="background:#0d1117;font-family:'Plus Jakarta Sans',sans-serif;">

        {{-- ===== HEADER ===== --}}
        <header style="background:#161b22;border-bottom:1px solid #30363d;" class="shrink-0">
            <div class="flex items-center gap-3 px-3 py-2.5">
                <a href="{{ route('servicemanagement.index') }}" class="w-9 h-9 rounded-xl flex items-center justify-center transition-all" style="background:#21262d;color:#8b949e;" onmouseover="this.style.color='#137fec'" onmouseout="this.style.color='#8b949e'">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                </a>
                <div class="shrink-0">
                    <h1 class="text-sm font-black tracking-tight leading-none uppercase" style="color:#f0f6fc;">SERVİS POS</h1>
                    <p class="text-[9px] font-bold uppercase tracking-widest" style="color:#137fec;">X1M #S-01</p>
                </div>
                <div class="flex-1 relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-base" style="color:#484f58;">search</span>
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="searchProducts()"
                        placeholder="Ürün ya da hizmet ara..."
                        class="w-full rounded-xl py-2 pl-9 pr-3 text-sm border-0 outline-none focus:ring-1 focus:ring-blue-500"
                        style="background:#21262d;color:#f0f6fc;placeholder-color:#484f58;">
                </div>
                <div id="pos-clock" class="hidden md:block text-xs font-black tabular-nums shrink-0" style="color:#8b949e;">--:--:--</div>
                <button @click="toggleFullscreen()" class="w-9 h-9 rounded-xl hidden lg:flex items-center justify-center transition-all shrink-0" style="background:#21262d;color:#8b949e;" onmouseover="this.style.color='#137fec'" onmouseout="this.style.color='#8b949e'">
                    <span class="material-symbols-outlined text-lg">fullscreen</span>
                </button>
            </div>
            <div class="flex items-center gap-2 px-3 pb-2.5 overflow-x-auto no-scrollbar">
                <button @click="filterType = 'all'; searchProducts()"
                    :style="filterType === 'all' ? 'background:#137fec;color:#fff;' : 'background:#21262d;color:#8b949e;'"
                    class="flex-shrink-0 flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wide transition-all">
                    <span class="material-symbols-outlined text-sm">apps</span> Hepsi
                </button>
                <button @click="filterType = 'good'; searchProducts()"
                    :style="filterType === 'good' ? 'background:#1f6feb;color:#fff;' : 'background:#21262d;color:#8b949e;'"
                    class="flex-shrink-0 flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wide transition-all">
                    <span class="material-symbols-outlined text-sm">inventory_2</span> Ürün
                </button>
                <button @click="filterType = 'service'; searchProducts()"
                    :style="filterType === 'service' ? 'background:#6e40c9;color:#fff;' : 'background:#21262d;color:#8b949e;'"
                    class="flex-shrink-0 flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wide transition-all">
                    <span class="material-symbols-outlined text-sm">build</span> Hizmet
                </button>
                <div class="flex-1"></div>
                <span class="text-[10px] font-bold shrink-0" style="color:#484f58;" x-text="products.length + ' sonuç'"></span>
            </div>
        </header>

        {{-- ===== MAIN ===== --}}
        <div class="flex-1 flex overflow-hidden">

            {{-- PRODUCT GRID --}}
            <section class="flex-1 overflow-y-auto p-3 custom-scrollbar">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pb-24 lg:pb-4">
                    <template x-for="product in products" :key="product.id">
                        <div @click="addToCart(product)"
                            class="group relative rounded-2xl overflow-hidden cursor-pointer transition-all active:scale-95 flex flex-col"
                            style="background:#161b22;border:1px solid #30363d;"
                            onmouseover="this.style.borderColor='#137fec'"
                            onmouseout="this.style.borderColor='#30363d'">
                            {{-- Icon Area --}}
                            <div class="relative h-24 sm:h-28 flex items-center justify-center"
                                :style="(product.product_type && product.product_type.code === 'service') || product.type === 'service' ? 'background:#1a1123;' : 'background:#0d1b2a;'">
                                <span class="material-symbols-outlined text-5xl transition-transform group-hover:scale-110 duration-150"
                                    :style="(product.product_type && product.product_type.code === 'service') || product.type === 'service' ? 'color:#7c3aed;' : 'color:#1f6feb;'"
                                    x-text="(product.product_type && product.product_type.code === 'service') || product.type === 'service' ? 'build' : 'inventory_2'">
                                </span>
                                {{-- Stock --}}
                                <div x-show="!((product.product_type && product.product_type.code === 'service') || product.type === 'service')"
                                    class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-[9px] font-black"
                                    :style="(product.stock || 0) <= 0 ? 'background:#3d1f1f;color:#f85149;' : 'background:#1c2128;color:#8b949e;'">
                                    STK <span x-text="product.stock || 0"></span>
                                </div>
                                {{-- Type badge --}}
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-[9px] font-black"
                                    :style="(product.product_type && product.product_type.code === 'service') || product.type === 'service' ? 'background:#1e0f3a;color:#8b5cf6;' : 'background:#0d1b2a;color:#388bfd;'">
                                    <span x-text="(product.product_type && product.product_type.code === 'service') || product.type === 'service' ? 'HİZMET' : 'ÜRÜN'"></span>
                                </div>
                            </div>
                            {{-- Info --}}
                            <div class="p-3 flex-1 flex flex-col justify-between gap-2" style="background:#161b22;">
                                <h3 class="text-xs font-black leading-snug line-clamp-2 transition-colors" style="color:#f0f6fc;" x-text="product.name"></h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-black" style="color:#137fec;" x-text="parseFloat(product.sale_price).toLocaleString('tr-TR', {minimumFractionDigits:2, maximumFractionDigits:2}) + ' ₺'"></span>
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center transition-all" style="background:#1f6feb22;color:#137fec;" onmouseover="this.style.background='#137fec';this.style.color='#fff'" onmouseout="this.style.background='#1f6feb22';this.style.color='#137fec'" @click.stop="addToCart(product)">
                                        <span class="material-symbols-outlined text-base">add</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <div x-show="products.length === 0" class="h-64 flex flex-col items-center justify-center" style="color:#30363d;">
                    <span class="material-symbols-outlined text-7xl mb-3">search_off</span>
                    <p class="text-sm font-bold uppercase tracking-widest">Sonuç Bulunamadı</p>
                </div>
            </section>

            {{-- DESKTOP SIDEBAR --}}
            <aside class="hidden lg:flex w-80 xl:w-96 flex-col shrink-0 overflow-hidden" style="background:#161b22;border-left:1px solid #30363d;">
                @include('servicemanagement::pos.partials.cart-panel')
            </aside>
        </div>

        {{-- MOBILE FAB --}}
        <div class="lg:hidden fixed bottom-5 right-4 z-50">
            <button @click="cartOpen = true"
                class="relative flex items-center gap-2.5 pl-4 pr-5 py-3.5 rounded-2xl font-black text-sm active:scale-95 transition-all"
                style="background:#137fec;color:#fff;box-shadow:0 8px 24px rgba(19,127,236,0.4);">
                <span class="material-symbols-outlined text-xl">shopping_cart</span>
                <span>Sepet</span>
                <template x-if="cart.length > 0">
                    <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full text-white text-[10px] font-black flex items-center justify-center shadow-lg" style="background:#f85149;" x-text="cart.length"></span>
                </template>
                <template x-if="cart.length > 0">
                    <span class="font-bold text-xs opacity-80" x-text="formatNumber(total) + ' ₺'"></span>
                </template>
            </button>
        </div>

        {{-- MOBILE DRAWER --}}
        <div x-show="cartOpen" x-cloak @click.self="cartOpen = false"
             class="lg:hidden fixed inset-0 z-40 flex items-end"
             style="background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             style="display:none;">
            <div class="w-full rounded-t-3xl shadow-2xl flex flex-col max-h-[92vh] overflow-hidden"
                 style="background:#161b22;"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                <div class="flex items-center justify-between px-4 pt-4 pb-3 shrink-0" style="border-bottom:1px solid #30363d;">
                    <div class="w-10 h-1 rounded-full absolute left-1/2 -translate-x-1/2 top-2" style="background:#30363d;"></div>
                    <h2 class="text-sm font-black uppercase tracking-tight" style="color:#f0f6fc;">Sepet</h2>
                    <button @click="cartOpen = false" class="w-8 h-8 rounded-full flex items-center justify-center" style="background:#21262d;color:#8b949e;">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    @include('servicemanagement::pos.partials.cart-panel')
                </div>
            </div>
        </div>

        {{-- SUCCESS MODAL --}}
        <div x-show="showSuccessModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="background:rgba(0,0,0,0.8);backdrop-filter:blur(6px);display:none;">
            <div class="rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl relative overflow-hidden" style="background:#161b22;border:1px solid #30363d;">
                <div class="absolute top-0 inset-x-0 h-1.5 rounded-t-3xl" style="background:linear-gradient(90deg,#3fb950,#2ea043);"></div>
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 animate-bounce" style="background:#1a3d27;color:#3fb950;">
                    <span class="material-symbols-outlined text-5xl">check_circle</span>
                </div>
                <h2 class="text-2xl font-black mb-2 uppercase tracking-tighter" style="color:#f0f6fc;">Servis Tamamlandı!</h2>
                <p class="text-sm mb-8" style="color:#8b949e;">İşlem başarıyla kaydedildi.</p>
                <button @click="closeSuccessModal()" class="w-full py-3.5 rounded-2xl font-black text-sm uppercase tracking-wider transition-all active:scale-95" style="background:#f0f6fc;color:#0d1117;">
                    TAMAM
                </button>
            </div>
        </div>

        {{-- OCR OVERLAY --}}
        <div x-show="isOcrScanning" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center" style="background:rgba(1,4,9,0.95);backdrop-filter:blur(8px);">
            <div class="text-center max-w-sm w-full px-6">
                <div x-show="ocrPreview" class="mb-6 rounded-2xl overflow-hidden shadow-2xl" style="border:2px solid #137fec40;">
                    <img :src="ocrPreview" class="w-full h-auto max-h-52 object-contain" style="background:#0d1117;" alt="OCR">
                    <div class="py-2 text-[10px] font-black uppercase tracking-widest" style="background:#0d1b2a;color:#137fec;border-top:1px solid #137fec30;">ODAKLANAN BÖLGE</div>
                </div>
                <div x-show="!ocrPreview" class="relative w-28 h-28 mx-auto mb-6">
                    <div class="absolute inset-0 rounded-2xl" style="border:3px solid #137fec20;"></div>
                    <div class="absolute inset-x-0 top-0 h-0.5 rounded-full animate-[scan_2s_ease-in-out_infinite]" style="background:#137fec;box-shadow:0 0 16px #137fec;"></div>
                    <span class="material-symbols-outlined absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" style="font-size:56px;color:#137fec;">document_scanner</span>
                </div>
                <h3 class="text-xl font-black mb-2 uppercase tracking-tighter" style="color:#f0f6fc;" x-text="ocrStatus">Karakterler Okunuyor</h3>
                <p class="text-sm font-bold uppercase tracking-widest animate-pulse" style="color:#484f58;">Lütfen bekleyin...</p>
            </div>
        </div>
    </div>

    {{-- TOAST --}}
    <div x-data="{ show: false, message: '', type: 'success' }"
        x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8"
        class="fixed bottom-20 lg:bottom-6 left-1/2 -translate-x-1/2 z-[110] px-5 py-3.5 rounded-2xl flex items-center gap-3 max-w-xs w-full shadow-2xl"
        :style="type === 'success' ? 'background:#161b22;border:1px solid #238636;color:#3fb950;' : 'background:#161b22;border:1px solid #da3633;color:#f85149;'"
        style="display:none;">
        <div class="w-2.5 h-2.5 rounded-full animate-pulse shrink-0" :style="type === 'success' ? 'background:#3fb950;' : 'background:#f85149;'"></div>
        <span class="font-bold text-sm" x-text="message"></span>
    </div>

    <style>
        @keyframes slide-in { from { opacity:0;transform:translateX(20px); } to { opacity:1;transform:translateX(0); } }
        .animate-slide-in { animation:slide-in 0.25s ease-out forwards; }
        @keyframes scan { 0%,100% { top:0; } 50% { top:calc(100% - 2px); } }
        .custom-scrollbar::-webkit-scrollbar { width:4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background:transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background:#30363d;border-radius:10px; }
        .no-scrollbar::-webkit-scrollbar { display:none; }
        .no-scrollbar { -ms-overflow-style:none;scrollbar-width:none; }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance:none;margin:0; }
        .line-clamp-2 { display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
        input::placeholder { color:#484f58; }
        select option { background:#21262d;color:#f0f6fc; }
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
                    const t = document.getElementById('pos-clock');
                    if (t) t.textContent = new Date().toLocaleTimeString('tr-TR');
                },

                async searchProducts() {
                    try {
                        let url = `{{ route('servicemanagement.pos.products') }}?search=${this.searchQuery}`;
                        if (this.filterType !== 'all') url += `&type=${this.filterType}`;
                        const r = await fetch(url);
                        this.products = await r.json();
                    } catch (e) { console.error(e); }
                },

                addToCart(product) {
                    const existing = this.cart.find(i => i.product_id === product.id);
                    if (existing) { existing.quantity++; }
                    else {
                        this.cart.unshift({ product_id: product.id, name: product.name, sale_price: product.sale_price, vat_rate: product.vat_rate, quantity: 1, discount_rate: 0 });
                    }
                    this.calculateTotals();
                    this.notify('Eklendi: ' + product.name);
                },

                removeFromCart(index) { this.cart.splice(index, 1); this.calculateTotals(); },

                updateQty(index, delta) {
                    this.cart[index].quantity += delta;
                    if (this.cart[index].quantity <= 0) this.removeFromCart(index);
                    this.calculateTotals();
                },

                calculateTotals() {
                    let sub = 0, tax = 0, disc = 0;
                    this.cart.forEach(item => {
                        const base = item.quantity * item.sale_price;
                        const d = base * (item.discount_rate / 100);
                        sub += base; disc += d; tax += (base - d) * (item.vat_rate / 100);
                    });
                    this.subtotal = sub; this.discountTotal = disc; this.taxTotal = tax;
                    this.total = sub - disc + tax;
                    this.changeAmount = (this.paymentMethod === 'cash' && this.receivedAmount > this.total) ? this.receivedAmount - this.total : 0;
                },

                async checkout() {
                    if (this.cart.length === 0) return;
                    this.isProcessing = true;
                    try {
                        const r = await fetch(`{{ route('servicemanagement.pos.checkout') }}`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ items: this.cart, contact_id: this.selectedCustomer, payment_method: this.paymentMethod, discount_total: this.discountTotal, received_amount: this.receivedAmount, plate_number: this.plateNumber, current_mileage: this.currentMileage })
                        });
                        const result = await r.json();
                        if (result.success) { this.cartOpen = false; this.showSuccessModal = true; }
                        else { this.notify(result.message || 'Bir hata oluştu', 'error'); }
                    } catch (e) { this.notify('Sistem hatası!', 'error'); }
                    finally { this.isProcessing = false; }
                },

                closeSuccessModal() {
                    this.showSuccessModal = false;
                    this.cart = []; this.receivedAmount = 0; this.changeAmount = 0;
                    this.plateNumber = ''; this.currentMileage = 0; this.vehicleStatus = null;
                    this.calculateTotals(); this.searchProducts();
                },

                async checkVehicleStatus() {
                    if (!this.plateNumber || this.plateNumber.length < 5) { this.vehicleStatus = null; return; }
                    try {
                        const r = await fetch(`/service-management/api/vehicle-status/${encodeURIComponent(this.plateNumber.replace(/\s+/g,''))}`);
                        const result = await r.json();
                        this.vehicleStatus = result;
                        if (result.status === 'exists') {
                            if (this.currentMileage === 0) this.currentMileage = result.current_mileage;
                            if (result.maintenance_status === 'overdue') this.notify('DİKKAT: Bakım zamanı geçmiş!', 'error');
                        }
                    } catch (e) {}
                },

                clearCart() {
                    if (confirm('Sepeti temizlemek istediğinize emin misiniz?')) { this.cart = []; this.calculateTotals(); }
                },

                toggleFullscreen() {
                    if (!document.fullscreenElement) document.documentElement.requestFullscreen();
                    else if (document.exitFullscreen) document.exitFullscreen();
                },

                preprocessImage(file) {
                    return new Promise(resolve => {
                        const reader = new FileReader();
                        reader.onload = e => {
                            const img = new Image();
                            img.onload = () => {
                                const canvas = document.createElement('canvas');
                                const ctx = canvas.getContext('2d');
                                const fw = img.width * 0.8, fh = img.height * 0.5;
                                const fx = (img.width - fw) / 2, fy = img.height * 0.4;
                                canvas.width = fw; canvas.height = fh;
                                ctx.drawImage(img, fx, fy, fw, fh, 0, 0, fw, fh);
                                const imgData = ctx.getImageData(0, 0, fw, fh);
                                const d = imgData.data;
                                const gray = new Uint8ClampedArray(d);
                                for (let i = 0; i < gray.length; i += 4) { const g = d[i]*0.299+d[i+1]*0.587+d[i+2]*0.114; gray[i]=gray[i+1]=gray[i+2]=g; }
                                const hist = new Array(256).fill(0);
                                for (let i = 0; i < gray.length; i += 4) hist[Math.round(gray[i])]++;
                                let total=gray.length/4, sum=0;
                                for (let i = 0; i < 256; i++) sum += i*hist[i];
                                let sumB=0,wB=0,wF=0,varMax=0,threshold=0;
                                for (let i = 0; i < 256; i++) {
                                    wB+=hist[i]; if(!wB)continue; wF=total-wB; if(!wF)break;
                                    sumB+=i*hist[i]; let mB=sumB/wB,mF=(sum-sumB)/wF,vb=wB*wF*(mB-mF)*(mB-mF);
                                    if(vb>varMax){varMax=vb;threshold=i;}
                                }
                                ctx.putImageData(new ImageData(gray, fw, fh), 0, 0);
                                const p1 = canvas.toDataURL('image/jpeg', 0.9);
                                const bin = new ImageData(new Uint8ClampedArray(gray), fw, fh);
                                for(let i=0;i<bin.data.length;i+=4){const v=bin.data[i]>threshold?255:0;bin.data[i]=bin.data[i+1]=bin.data[i+2]=v;}
                                ctx.putImageData(bin, 0, 0);
                                this.ocrPreview = canvas.toDataURL('image/jpeg', 0.9);
                                resolve({ pass1: p1, pass2: this.ocrPreview });
                            };
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                },

                scoreResult(text) {
                    if (!text) return -1;
                    const c = text.toUpperCase().replace(/[^A-Z0-9]/g,'');
                    let s = 0;
                    if (/[0-8][0-9][A-Z]{1,3}[0-9]{2,4}/.test(c)) {
                        s += 100;
                        if (c.length===7||c.length===8) s+=50;
                        if (c.length>8) s-=(c.length-8)*10;
                    }
                    return s + c.length;
                },

                async handleOcr(event) {
                    const file = event.target.files[0]; if (!file) return;
                    this.isOcrScanning = true; this.ocrStatus = 'Hazırlanıyor...';
                    try {
                        const {pass1, pass2} = await this.preprocessImage(file);
                        this.ocrStatus = 'Taranıyor...';
                        const worker = await Tesseract.createWorker('eng', 1);
                        await worker.setParameters({ tessedit_char_whitelist: '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', tessedit_pageseg_mode: '11' });
                        const r1 = await worker.recognize(pass1);
                        const r2 = await worker.recognize(pass2);
                        await worker.terminate();
                        const best = this.scoreResult(r1.data.text) >= this.scoreResult(r2.data.text) ? r1.data.text : r2.data.text;
                        const raw = (best||'').toUpperCase().replace(/[^A-Z0-9]/g,'');
                        const m = raw.match(/([0-8][0-9][A-Z]{1,3}[0-9]{2,4})/);
                        if (m) {
                            let c = m[0];
                            if (c.length===9&&/[0-9]{4}$/.test(c)) c=c.substring(0,8);
                            this.plateNumber = c; this.checkVehicleStatus(); this.notify('Plaka doğrulandı: '+this.plateNumber);
                        } else if (raw.length >= 5) {
                            this.plateNumber = raw.substring(0,10); this.notify('Plaka yakalandı: '+this.plateNumber);
                        } else { this.notify('Okuma başarısız.', 'error'); }
                    } catch (err) { this.notify('Hata: '+err.message, 'error'); }
                    finally { this.isOcrScanning = false; event.target.value = ''; }
                },

                notify(message, type = 'success') {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message, type } }));
                },

                formatNumber(val) {
                    return parseFloat(val||0).toLocaleString('tr-TR', {minimumFractionDigits:2, maximumFractionDigits:2});
                }
            }
        }
    </script>
</x-app-layout>
