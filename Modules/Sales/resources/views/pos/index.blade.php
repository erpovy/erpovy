<x-app-layout>
    <div x-data="posSystem()" class="fixed inset-0 z-50 bg-[#0f172a] overflow-hidden flex flex-col font-sans select-none">
        <!-- POS Header -->
        <header class="h-20 bg-white/5 border-b border-white/10 backdrop-blur-3xl flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-6">
                <a href="{{ route('sales.sales.index') }}" class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white transition-all group">
                    <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tight">SATIŞ NOKTASI</h1>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-[0.2em]">Erpovy X1M • Terminal #01</p>
                </div>
            </div>

            <div class="flex-1 max-w-xl px-12">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-4 flex items-center text-slate-500 group-focus-within:text-primary transition-colors">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                    <input 
                        type="text" 
                        x-model="searchQuery" 
                        @input.debounce.300ms="searchProducts()"
                        placeholder="Ürün ismi veya barkod..." 
                        class="w-full bg-white/5 border-white/10 border-2 rounded-2xl py-3 pl-14 pr-6 text-white placeholder-slate-500 focus:border-primary focus:ring-0 transition-all font-medium text-sm"
                    >
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Quick Filters -->
                <div class="flex bg-white/5 p-1 rounded-xl border border-white/10">
                    <button @click="filterType = 'all'; searchProducts()" :class="filterType === 'all' ? 'bg-primary text-white' : 'text-slate-400 hover:text-white'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase transition-all">Hepsi</button>
                    <button @click="filterType = 'good'; searchProducts()" :class="filterType === 'good' ? 'bg-primary text-white' : 'text-slate-400 hover:text-white'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase transition-all">Ürün</button>
                    <button @click="filterType = 'service'; searchProducts()" :class="filterType === 'service' ? 'bg-primary text-white' : 'text-slate-400 hover:text-white'" class="px-4 py-1.5 rounded-lg text-xs font-black uppercase transition-all">Hizmet</button>
                </div>

                <div class="text-right hidden sm:block">
                    <div class="text-sm font-black text-white" id="pos-clock">--:--:--</div>
                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ now()->format('d.m.Y') }}</div>
                </div>
                
                <button @click="toggleFullscreen()" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                    <span class="material-symbols-outlined text-[20px]">fullscreen</span>
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex overflow-hidden">
            <!-- Left: Products Grid -->
            <section class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-[#0f172a]">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">
                    <template x-for="product in products" :key="product.id">
                        <div 
                            @click="addToCart(product)"
                            class="group relative bg-[#1e293b]/50 border border-white/5 rounded-[2.5rem] p-6 hover:border-primary/50 hover:bg-primary/5 transition-all duration-500 cursor-pointer overflow-hidden flex flex-col h-full"
                        >
                            <div class="aspect-square rounded-[2rem] bg-slate-900/50 flex items-center justify-center mb-4 overflow-hidden relative shrink-0">
                                <span class="material-symbols-outlined text-4xl text-slate-700 group-hover:scale-110 transition-transform duration-700" x-text="product.type === 'service' ? 'eco' : 'inventory_2'"></span>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                                <div x-show="product.type !== 'service'" class="absolute bottom-4 left-4 px-3 py-1 bg-black/40 backdrop-blur-md rounded-xl border border-white/10 text-[9px] font-black text-white uppercase tracking-widest">
                                    Stok: <span x-text="product.stock || 0"></span>
                                </div>
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="text-sm font-black text-white group-hover:text-primary transition-colors leading-tight mb-1" x-text="product.name"></h3>
                                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest" x-text="product.code"></div>
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <div class="text-primary font-black text-xl tracking-tighter">
                                    ₺<span x-text="formatNumber(product.sale_price)"></span>
                                </div>
                                <div class="w-12 h-12 rounded-[1.25rem] bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 group-hover:bg-primary group-hover:text-white transition-all shadow-xl scale-90 group-hover:scale-100">
                                    <span class="material-symbols-outlined text-[24px]">add_shopping_cart</span>
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
            <aside class="w-[480px] bg-[#1e293b]/80 border-l border-white/10 backdrop-blur-3xl flex flex-col shrink-0 overflow-hidden">
                <!-- Customer & Actions -->
                <div class="p-6 border-b border-white/10 flex items-center gap-4">
                    <div class="flex-1">
                        <label class="block text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Müşteri Seçimi</label>
                        <select 
                            x-model="selectedCustomer" 
                            class="w-full bg-white/5 border-white/10 rounded-2xl py-3 px-4 text-white focus:border-primary focus:ring-0 text-sm font-bold"
                        >
                            <option value="" class="bg-[#0f172a]">Genel Müşteri</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}" class="bg-[#0f172a]">{{ $contact->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button @click="clearCart()" class="mt-5 w-12 h-12 rounded-2xl bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center" title="Sepeti Temizle">
                        <span class="material-symbols-outlined">delete_sweep</span>
                    </button>
                </div>

                <!-- Cart Items -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
                    <template x-for="(item, index) in cart" :key="index">
                        <div class="relative group animate-slide-in">
                            <div class="flex items-center gap-4 bg-white/5 rounded-3xl p-4 border border-white/5 hover:border-white/10 transition-all">
                                <div class="w-14 h-14 rounded-2xl bg-slate-900/50 flex items-center justify-center text-slate-600">
                                    <span class="material-symbols-outlined text-3xl">shopping_basket</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-black text-white truncate pr-6" x-text="item.name"></div>
                                    <div class="flex items-center gap-3 mt-2">
                                        <div class="flex items-center bg-white/5 rounded-xl border border-white/10 p-1">
                                            <button @click="updateQty(index, -1)" class="w-8 h-8 rounded-lg hover:bg-white/10 text-white transition-all flex items-center justify-center">
                                                <span class="material-symbols-outlined text-sm">remove</span>
                                            </button>
                                            <input type="number" x-model.number="item.quantity" @input="calculateTotals()" class="w-12 bg-transparent border-0 text-center text-sm font-black text-primary py-0 focus:ring-0">
                                            <button @click="updateQty(index, 1)" class="w-8 h-8 rounded-lg hover:bg-white/10 text-white transition-all flex items-center justify-center">
                                                <span class="material-symbols-outlined text-sm">add</span>
                                            </button>
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-500">x ₺<span x-text="formatNumber(item.sale_price)"></span></span>
                                    </div>
                                    <!-- Line Discount -->
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="text-[9px] font-black text-slate-600 uppercase">İndirim %</span>
                                        <input type="number" x-model.number="item.discount_rate" @input="calculateTotals()" class="w-12 bg-white/5 border-white/10 rounded-lg text-center text-[10px] font-black text-orange-400 py-1 focus:border-orange-500 focus:ring-0">
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-black text-white tracking-tighter">₺<span x-text="formatNumber(calculateLineTotal(item))"></span></div>
                                    <button @click="removeFromCart(index)" class="absolute top-4 right-4 text-slate-600 hover:text-red-500 transition-colors">
                                        <span class="material-symbols-outlined text-lg">close</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center opacity-10 py-20">
                        <span class="material-symbols-outlined text-8xl mb-4">add_shopping_cart</span>
                        <p class="text-sm font-black uppercase tracking-[0.2em] text-center">Sepetiniz Boş</p>
                    </div>
                </div>

                <!-- Footer / Checkout -->
                <div class="p-8 bg-[#0f172a]/80 backdrop-blur-4xl border-t border-white/10 space-y-6 shrink-0">
                    <div class="grid grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <div class="flex justify-between text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                <span>Ara Toplam</span>
                                <span class="text-white">₺<span x-text="formatNumber(subtotal)"></span></span>
                            </div>
                            <div class="flex justify-between text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                <span>KDV (%<span x-data="{rate: 20}" x-text="rate"></span>)</span>
                                <span class="text-white">₺<span x-text="formatNumber(taxTotal)"></span></span>
                            </div>
                            <div x-show="discountTotal > 0" class="flex justify-between text-[11px] font-bold text-orange-400 uppercase tracking-widest">
                                <span>Toplam İndirim</span>
                                <span>-₺<span x-text="formatNumber(discountTotal)"></span></span>
                            </div>
                        </div>
                        <div class="flex flex-col justify-end text-right">
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Genel Toplam</span>
                            <div class="text-5xl font-black text-primary tracking-tighter leading-none">
                                <span class="text-2xl opacity-50 mr-1">₺</span><span x-text="formatNumber(total)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment & Change -->
                    <div class="bg-white/5 rounded-[2rem] p-6 border border-white/10">
                        <div class="flex items-center gap-6 mb-4">
                            <div class="flex-1">
                                <label class="block text-[9px] font-black text-slate-500 uppercase mb-2">Ödenen Tutar (Nakit)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary font-black">₺</span>
                                    <input type="number" x-model.number="receivedAmount" @input="calculateChange()" class="w-full bg-white/5 border-white/10 rounded-2xl py-4 pl-10 pr-4 text-2xl font-black text-white focus:border-primary focus:ring-0 tracking-tighter">
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-[9px] font-black text-slate-500 uppercase mb-2">Para Üstü</label>
                                <div class="text-3xl font-black text-green-400 tracking-tighter py-3">
                                    ₺<span x-text="formatNumber(changeAmount)"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <button 
                                @click="paymentMethod = 'cash'" 
                                :class="paymentMethod === 'cash' ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20 scale-105' : 'bg-white/5 text-slate-500 border-white/10 opacity-50'"
                                class="flex items-center justify-center gap-3 p-4 rounded-2xl border transition-all font-black uppercase text-xs"
                            >
                                <span class="material-symbols-outlined">payments</span>
                                Nakit
                            </button>
                            <button 
                                @click="paymentMethod = 'card'"
                                :class="paymentMethod === 'card' ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-600/20 scale-105' : 'bg-white/5 text-slate-500 border-white/10 opacity-50'"
                                class="flex items-center justify-center gap-3 p-4 rounded-2xl border transition-all font-black uppercase text-xs"
                            >
                                <span class="material-symbols-outlined">credit_card</span>
                                Kart
                            </button>
                        </div>
                    </div>

                    <button 
                        @click="checkout()"
                        :disabled="cart.length === 0 || isProcessing"
                        class="w-full py-6 rounded-[2.5rem] bg-primary text-white font-black text-xl uppercase tracking-[0.3em] shadow-2xl shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-30 disabled:grayscale disabled:pointer-events-none group relative overflow-hidden"
                    >
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shimmer"></div>
                        <span x-show="!isProcessing" class="flex items-center justify-center gap-4 relative">
                            Siparişi Tamamla
                            <span class="material-symbols-outlined text-[28px] group-hover:translate-x-2 transition-transform">send_to_mobile</span>
                        </span>
                        <span x-show="isProcessing" class="flex items-center justify-center gap-4 relative">
                            <span class="animate-spin material-symbols-outlined">progress_activity</span>
                            İşleniyor...
                        </span>
                    </button>
                </div>
            </aside>
        </main>

        <!-- Checkout Success Modal -->
        <div x-show="showSuccessModal" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/80 backdrop-blur-sm" style="display: none;">
            <div class="bg-[#1e293b] border border-white/10 rounded-[3rem] p-12 max-w-lg w-full text-center shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 inset-x-0 h-2 bg-primary"></div>
                <div class="w-24 h-24 rounded-full bg-green-500/20 text-green-500 flex items-center justify-center mx-auto mb-8 animate-bounce">
                    <span class="material-symbols-outlined text-6xl">check_circle</span>
                </div>
                <h2 class="text-3xl font-black text-white mb-4 uppercase tracking-tighter">Satış Tamamlandı!</h2>
                <p class="text-slate-400 font-bold mb-10 leading-relaxed uppercase text-xs tracking-widest">İşlem başarıyla işlendi ve mali kaydı oluşturuldu.</p>
                
                <div class="space-y-4">
                    <button @click="closeSuccessModal()" class="w-full py-5 rounded-2xl bg-white/5 border border-white/10 text-white font-black uppercase tracking-widest hover:bg-white/10 transition-all">Yeni Satış</button>
                    <button @click="printReceipt()" class="w-full py-5 rounded-2xl bg-primary text-white font-black uppercase tracking-widest hover:scale-105 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">print</span>
                        Bilgi Fişi Yazdır
                    </button>
                </div>
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
                subtotal: 0,
                taxTotal: 0,
                discountTotal: 0,
                total: 0,
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
                        let url = `{{ route('sales.pos.products') }}?search=${this.searchQuery}`;
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
                    this.notify('Ürün eklendi');
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
                        const response = await fetch(`{{ route('sales.pos.checkout') }}`, {
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
                                received_amount: this.receivedAmount
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
                    this.calculateTotals();
                    this.searchProducts();
                },

                printReceipt() {
                    // Quick and dirty receipt print
                    const printWindow = window.open('', '', 'width=300,height=600');
                    if (!printWindow) return;
                    
                    let productsHtml = '';
                    this.cart.forEach(item => {
                        productsHtml += `
                            <div style="display:flex;justify-content:space-between;font-size:12px;">
                                <span>${item.name} x${item.quantity}</span>
                                <span>${this.formatNumber(this.calculateLineTotal(item))}TL</span>
                            </div>
                        `;
                    });

                    printWindow.document.write(`
                        <div style="font-family:monospace;padding:20px;width:260px;">
                            <h2 style="text-align:center;margin-bottom:5px;">ERPOVY X1M</h2>
                            <p style="text-align:center;font-size:10px;margin-bottom:20px;">Bilgi Fişi<br>${new Date().toLocaleString()}</p>
                            <hr style="border-top:1px dashed #000;">
                            <div style="margin:10px 0;">${productsHtml}</div>
                            <hr style="border-top:1px dashed #000;">
                            <div style="font-weight:bold;display:flex;justify-content:space-between;font-size:14px;margin-top:10px;">
                                <span>TOPLAM:</span>
                                <span>${this.formatNumber(this.total)}TL</span>
                            </div>
                            <p style="text-align:center;font-size:10px;margin-top:30px;">Teşekkür Ederiz!</p>
                        </div>
                    `);
                    printWindow.document.close();
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close();
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
