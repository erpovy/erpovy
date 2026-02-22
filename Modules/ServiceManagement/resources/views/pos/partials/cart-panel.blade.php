{{-- =============================================
     CART PANEL PARTIAL
     ============================================= --}}

{{-- Customer & Clear --}}
<div class="p-3 border-b border-gray-100 dark:border-slate-800 flex items-center gap-2 shrink-0">
    <div class="flex-1 relative">
        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 material-symbols-outlined text-sm text-gray-400 dark:text-slate-500">person_search</span>
        <select
            x-model="selectedCustomer"
            class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl py-2 pl-8 pr-2 text-gray-900 dark:text-slate-100 focus:border-primary focus:ring-0 text-xs font-bold transition-all"
        >
            <option value="" class="bg-white dark:bg-slate-800">Genel Müşteri</option>
            @foreach($contacts as $contact)
                <option value="{{ $contact->id }}" class="bg-white dark:bg-slate-800">{{ $contact->name }}</option>
            @endforeach
        </select>
    </div>
    <button @click="clearCart()"
        class="w-9 h-9 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-500 border border-red-200 dark:border-red-800 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-base">delete_sweep</span>
    </button>
</div>

{{-- Cart Items --}}
<div class="flex-1 overflow-y-auto px-3 py-2 space-y-2 custom-scrollbar min-h-0">
    <template x-for="(item, index) in cart" :key="index">
        <div class="animate-slide-in bg-gray-50 dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-3">
            <div class="flex items-start gap-2 mb-2">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-black text-gray-900 dark:text-white leading-snug line-clamp-2" x-text="item.name"></p>
                    <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-0.5" x-text="formatNumber(item.sale_price) + ' ₺ / adet'"></p>
                </div>
                <button @click="removeFromCart(index)" class="text-gray-300 dark:text-slate-600 hover:text-red-500 transition-colors shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
            <div class="flex items-center justify-between gap-2">
                {{-- Qty stepper --}}
                <div class="flex items-center bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                    <button @click="updateQty(index, -1)" class="w-9 h-9 flex items-center justify-center text-gray-500 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-sm">remove</span>
                    </button>
                    <input type="number" x-model.number="item.quantity" @input="calculateTotals()"
                        class="w-10 bg-transparent border-0 text-center text-sm font-black text-primary py-0 px-0 focus:ring-0">
                    <button @click="updateQty(index, 1)" class="w-9 h-9 flex items-center justify-center text-gray-500 dark:text-slate-400 hover:bg-green-50 dark:hover:bg-green-900/30 hover:text-green-500 transition-colors">
                        <span class="material-symbols-outlined text-sm">add</span>
                    </button>
                </div>
                {{-- Discount --}}
                <div class="flex items-center gap-1.5 bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-700 px-2.5 h-9">
                    <span class="text-[9px] font-black text-gray-400 dark:text-slate-500 uppercase shrink-0">İND%</span>
                    <input type="number" x-model.number="item.discount_rate" @input="calculateTotals()"
                        class="w-10 bg-transparent border-0 text-center text-xs font-black text-orange-500 py-0 px-0 focus:ring-0">
                </div>
                {{-- Line total --}}
                <p class="text-sm font-black text-gray-900 dark:text-white shrink-0"
                    x-text="formatNumber(item.quantity * item.sale_price * (1 - item.discount_rate/100)) + ' ₺'"></p>
            </div>
        </div>
    </template>

    {{-- Empty cart --}}
    <div x-show="cart.length === 0" class="py-12 flex flex-col items-center justify-center text-gray-300 dark:text-slate-700">
        <span class="material-symbols-outlined text-6xl mb-3">shopping_cart</span>
        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-slate-600 text-center">Sepetiniz Boş</p>
        <p class="text-[10px] text-gray-400 dark:text-slate-700 mt-1 text-center max-w-[160px]">Ürün veya hizmet seçerek başlayın</p>
    </div>
</div>

{{-- Vehicle & Maintenance --}}
<div class="px-3 py-3 bg-gray-50 dark:bg-slate-950/50 border-t border-gray-100 dark:border-slate-800 space-y-3 shrink-0">

    {{-- Maintenance Alert --}}
    <template x-if="vehicleStatus && vehicleStatus.status === 'exists'">
        <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl border"
            :class="{
                'bg-rose-50 dark:bg-rose-900/20 border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400': vehicleStatus.maintenance_status === 'overdue',
                'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400': vehicleStatus.maintenance_status === 'upcoming',
                'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400': vehicleStatus.maintenance_status === 'healthy'
            }">
            <span class="material-symbols-outlined text-lg shrink-0" x-text="vehicleStatus.maintenance_status === 'healthy' ? 'check_circle' : 'warning'"></span>
            <div class="flex-1 min-w-0">
                <p class="text-[9px] font-black uppercase tracking-wider" x-text="vehicleStatus.maintenance_status === 'overdue' ? 'KRİTİK BAKIM ZAMANI' : (vehicleStatus.maintenance_status === 'upcoming' ? 'BAKIM YAKLAŞTI' : 'ARAÇ DURUMU İYİ')"></p>
                <p class="text-[10px] font-bold opacity-75 truncate" x-text="vehicleStatus.brand + ' ' + vehicleStatus.model"></p>
            </div>
        </div>
    </template>

    {{-- Plate Input --}}
    <div>
        <label class="text-[9px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest block mb-1.5">ARAÇ PLAKASI</label>
        <div class="relative flex items-stretch h-14 bg-white dark:bg-slate-800 border-2 border-gray-800 dark:border-slate-600 rounded-xl overflow-hidden shadow-sm">
            <div class="w-9 bg-blue-700 flex flex-col items-center justify-end pb-2 shrink-0">
                <span class="text-[8px] font-black text-white">TR</span>
            </div>
            <input
                type="text"
                x-model="plateNumber"
                @input.debounce.500ms="checkVehicleStatus()"
                placeholder="34 ABC 123"
                class="flex-1 min-w-0 bg-transparent border-0 text-center text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest focus:ring-0 placeholder-gray-300 dark:placeholder-slate-600">
            <button @click="$refs.ocrInput.click()"
                class="w-12 flex items-center justify-center bg-gray-100 dark:bg-slate-700 hover:bg-primary hover:text-white dark:text-slate-300 transition-all border-l border-gray-800 dark:border-slate-600 shrink-0">
                <span class="material-symbols-outlined text-xl">photo_camera</span>
            </button>
            <input type="file" x-ref="ocrInput" @change="handleOcr($event)" accept="image/*" class="hidden" capture="environment">
        </div>
    </div>

    {{-- Mileage --}}
    <div>
        <label class="text-[9px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest block mb-1.5">GÜNCEL KM</label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-base text-gray-500 dark:text-slate-400">speed</span>
            <input
                type="number"
                x-model.number="currentMileage"
                placeholder="0"
                class="w-full bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 rounded-xl py-2.5 pl-9 pr-4 text-sm font-black text-gray-900 dark:text-white placeholder-gray-300 dark:placeholder-slate-600 focus:border-primary focus:ring-0 transition-all">
        </div>
    </div>
</div>

{{-- Totals & Checkout --}}
<div class="px-3 pb-4 pt-3 bg-white dark:bg-slate-900 border-t border-gray-100 dark:border-slate-800 shrink-0 space-y-3">
    {{-- Totals --}}
    <div class="space-y-1.5 text-xs">
        <div class="flex justify-between text-gray-500 dark:text-slate-400">
            <span class="font-semibold">Ara Toplam</span>
            <span class="font-bold" x-text="formatNumber(subtotal) + ' ₺'"></span>
        </div>
        <div x-show="discountTotal > 0" class="flex justify-between text-orange-500 dark:text-orange-400">
            <span class="font-semibold">İndirim</span>
            <span class="font-bold" x-text="'- ' + formatNumber(discountTotal) + ' ₺'"></span>
        </div>
        <div class="flex justify-between text-gray-500 dark:text-slate-400">
            <span class="font-semibold">KDV</span>
            <span class="font-bold" x-text="formatNumber(taxTotal) + ' ₺'"></span>
        </div>
        <div class="flex justify-between text-gray-900 dark:text-white pt-1.5 border-t border-gray-100 dark:border-slate-800">
            <span class="font-black text-sm">TOPLAM</span>
            <span class="font-black text-xl text-primary" x-text="formatNumber(total) + ' ₺'"></span>
        </div>
    </div>

    {{-- Checkout Button --}}
    <button
        @click="checkout()"
        :disabled="cart.length === 0 || isProcessing"
        class="w-full py-4 rounded-2xl bg-primary text-white font-black text-sm uppercase tracking-[0.15em] shadow-lg shadow-primary/30 hover:brightness-110 transition-all disabled:opacity-30 disabled:pointer-events-none active:scale-95 relative overflow-hidden group">
        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/10 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
        <span x-show="!isProcessing" class="flex items-center justify-center gap-2 relative z-10">
            SERVİSİ TAMAMLA
            <span class="material-symbols-outlined text-lg">rocket_launch</span>
        </span>
        <span x-show="isProcessing" class="flex items-center justify-center gap-2 relative z-10">
            <span class="animate-spin material-symbols-outlined text-lg">sync</span>
            İŞLENİYOR...
        </span>
    </button>
</div>
