{{-- CART PANEL PARTIAL — always dark --}}

{{-- Customer & Clear --}}
<div class="p-3 flex items-center gap-2 shrink-0" style="border-bottom:1px solid #30363d;">
    <div class="flex-1 relative">
        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 material-symbols-outlined text-sm" style="color:#484f58;">person_search</span>
        <select x-model="selectedCustomer"
            class="w-full rounded-xl py-2 pl-8 pr-2 text-xs font-bold border-0 outline-none focus:ring-1 focus:ring-blue-500"
            style="background:#21262d;color:#f0f6fc;appearance:auto;">
            <option value="" style="background:#21262d;">Genel Müşteri</option>
            @foreach($contacts as $contact)
                <option value="{{ $contact->id }}" style="background:#21262d;">{{ $contact->name }}</option>
            @endforeach
        </select>
    </div>
    <button @click="clearCart()" class="w-9 h-9 rounded-xl flex items-center justify-center transition-all shrink-0"
        style="background:#3d1f1f;color:#f85149;border:1px solid #6e1c1c;"
        onmouseover="this.style.background='#f85149';this.style.color='#fff'"
        onmouseout="this.style.background='#3d1f1f';this.style.color='#f85149'">
        <span class="material-symbols-outlined text-base">delete_sweep</span>
    </button>
</div>

{{-- Cart Items --}}
<div class="flex-1 overflow-y-auto px-3 py-2 space-y-2 custom-scrollbar min-h-0">
    <template x-for="(item, index) in cart" :key="index">
        <div class="animate-slide-in rounded-2xl p-3" style="background:#1c2128;border:1px solid #30363d;">
            <div class="flex items-start gap-2 mb-2">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-black leading-snug line-clamp-2" style="color:#f0f6fc;" x-text="item.name"></p>
                    <p class="text-[10px] mt-0.5" style="color:#484f58;" x-text="formatNumber(item.sale_price) + ' ₺ / adet'"></p>
                </div>
                <button @click="removeFromCart(index)" class="transition-colors shrink-0 mt-0.5" style="color:#30363d;"
                    onmouseover="this.style.color='#f85149'" onmouseout="this.style.color='#30363d'">
                    <span class="material-symbols-outlined text-base">close</span>
                </button>
            </div>
            <div class="flex items-center justify-between gap-2">
                {{-- Qty --}}
                <div class="flex items-center rounded-xl overflow-hidden" style="background:#0d1117;border:1px solid #30363d;">
                    <button @click="updateQty(index, -1)" class="w-9 h-9 flex items-center justify-center transition-colors" style="color:#8b949e;"
                        onmouseover="this.style.color='#f85149'" onmouseout="this.style.color='#8b949e'">
                        <span class="material-symbols-outlined text-sm">remove</span>
                    </button>
                    <input type="number" x-model.number="item.quantity" @input="calculateTotals()"
                        class="w-10 border-0 text-center text-sm font-black focus:ring-0 outline-none"
                        style="background:transparent;color:#137fec;">
                    <button @click="updateQty(index, 1)" class="w-9 h-9 flex items-center justify-center transition-colors" style="color:#8b949e;"
                        onmouseover="this.style.color='#3fb950'" onmouseout="this.style.color='#8b949e'">
                        <span class="material-symbols-outlined text-sm">add</span>
                    </button>
                </div>
                {{-- Discount --}}
                <div class="flex items-center gap-1.5 h-9 px-2.5 rounded-xl" style="background:#0d1117;border:1px solid #30363d;">
                    <span class="text-[9px] font-black uppercase shrink-0" style="color:#484f58;">İND%</span>
                    <input type="number" x-model.number="item.discount_rate" @input="calculateTotals()"
                        class="w-10 border-0 text-center text-xs font-black focus:ring-0 outline-none"
                        style="background:transparent;color:#e3b341;">
                </div>
                {{-- Line total --}}
                <p class="text-sm font-black shrink-0" style="color:#f0f6fc;"
                    x-text="formatNumber(item.quantity * item.sale_price * (1 - item.discount_rate/100)) + ' ₺'"></p>
            </div>
        </div>
    </template>

    {{-- Empty --}}
    <div x-show="cart.length === 0" class="py-12 flex flex-col items-center justify-center">
        <span class="material-symbols-outlined text-6xl mb-3" style="color:#21262d;">shopping_cart</span>
        <p class="text-xs font-bold uppercase tracking-widest text-center" style="color:#30363d;">Sepetiniz Boş</p>
        <p class="text-[10px] mt-1 text-center max-w-[160px]" style="color:#21262d;">Ürün veya hizmet seçerek başlayın</p>
    </div>
</div>

{{-- Vehicle --}}
<div class="px-3 py-3 space-y-3 shrink-0" style="background:#0d1117;border-top:1px solid #30363d;">

    {{-- Maintenance alert --}}
    <template x-if="vehicleStatus && vehicleStatus.status === 'exists'">
        <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl"
            :style="vehicleStatus.maintenance_status === 'overdue' ? 'background:#3d1f1f;border:1px solid #6e1c1c;color:#f85149;' : vehicleStatus.maintenance_status === 'upcoming' ? 'background:#2d2209;border:1px solid #5c3f00;color:#e3b341;' : 'background:#0d2a1a;border:1px solid #1a4731;color:#3fb950;'">
            <span class="material-symbols-outlined text-lg shrink-0" x-text="vehicleStatus.maintenance_status === 'healthy' ? 'check_circle' : 'warning'"></span>
            <div class="flex-1 min-w-0">
                <p class="text-[9px] font-black uppercase tracking-wider" x-text="vehicleStatus.maintenance_status === 'overdue' ? 'KRİTİK BAKIM ZAMANI' : (vehicleStatus.maintenance_status === 'upcoming' ? 'BAKIM YAKLAŞTI' : 'ARAÇ DURUMU İYİ')"></p>
                <p class="text-[10px] font-bold truncate opacity-75" x-text="vehicleStatus.brand + ' ' + vehicleStatus.model"></p>
            </div>
        </div>
    </template>

    {{-- Plate --}}
    <div>
        <label class="text-[9px] font-black uppercase tracking-widest block mb-1.5" style="color:#484f58;">ARAÇ PLAKASI</label>
        <div class="relative flex items-stretch h-14 rounded-xl overflow-hidden shadow-sm" style="background:#21262d;border:2px solid #30363d;">
            <div class="w-9 flex flex-col items-center justify-end pb-2 shrink-0" style="background:#1f6feb;">
                <span class="text-[8px] font-black text-white">TR</span>
            </div>
            <input type="text" x-model="plateNumber" @input.debounce.500ms="checkVehicleStatus()"
                placeholder="34 ABC 123"
                class="flex-1 min-w-0 border-0 text-center text-xl font-black uppercase tracking-widest focus:ring-0 outline-none"
                style="background:transparent;color:#f0f6fc;">
            <button @click="$refs.ocrInput.click()"
                class="w-12 flex items-center justify-center transition-all shrink-0"
                style="background:#21262d;color:#8b949e;border-left:2px solid #30363d;"
                onmouseover="this.style.background='#137fec';this.style.color='#fff'"
                onmouseout="this.style.background='#21262d';this.style.color='#8b949e'">
                <span class="material-symbols-outlined text-xl">photo_camera</span>
            </button>
            <input type="file" x-ref="ocrInput" @change="handleOcr($event)" accept="image/*" class="hidden" capture="environment">
        </div>
    </div>

    {{-- KM --}}
    <div>
        <label class="text-[9px] font-black uppercase tracking-widest block mb-1.5" style="color:#484f58;">GÜNCEL KM</label>
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-base" style="color:#484f58;">speed</span>
            <input type="number" x-model.number="currentMileage" placeholder="0"
                class="w-full rounded-xl py-2.5 pl-9 pr-4 text-sm font-black border-0 outline-none focus:ring-1 focus:ring-blue-500"
                style="background:#21262d;color:#f0f6fc;border:2px solid #30363d;">
        </div>
    </div>
</div>

{{-- Totals & Checkout --}}
<div class="px-3 pb-4 pt-3 space-y-3 shrink-0" style="background:#161b22;border-top:1px solid #30363d;">
    <div class="space-y-1.5 text-xs">
        <div class="flex justify-between" style="color:#8b949e;">
            <span class="font-semibold">Ara Toplam</span>
            <span class="font-bold" x-text="formatNumber(subtotal) + ' ₺'"></span>
        </div>
        <div x-show="discountTotal > 0" class="flex justify-between" style="color:#e3b341;">
            <span class="font-semibold">İndirim</span>
            <span class="font-bold" x-text="'- ' + formatNumber(discountTotal) + ' ₺'"></span>
        </div>
        <div class="flex justify-between" style="color:#8b949e;">
            <span class="font-semibold">KDV</span>
            <span class="font-bold" x-text="formatNumber(taxTotal) + ' ₺'"></span>
        </div>
        <div class="flex justify-between pt-1.5" style="border-top:1px solid #30363d;">
            <span class="font-black text-sm" style="color:#f0f6fc;">TOPLAM</span>
            <span class="font-black text-xl" style="color:#137fec;" x-text="formatNumber(total) + ' ₺'"></span>
        </div>
    </div>

    <button @click="checkout()" :disabled="cart.length === 0 || isProcessing"
        class="w-full py-4 rounded-2xl font-black text-sm uppercase tracking-[0.15em] transition-all disabled:opacity-30 disabled:pointer-events-none active:scale-95 relative overflow-hidden group text-white"
        style="background:#137fec;box-shadow:0 4px 16px rgba(19,127,236,0.3);">
        <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-700" style="background:linear-gradient(90deg,transparent,rgba(255,255,255,0.1),transparent);"></div>
        <span x-show="!isProcessing" class="flex items-center justify-center gap-2 relative z-10">
            SERVİSİ TAMAMLA <span class="material-symbols-outlined text-lg">rocket_launch</span>
        </span>
        <span x-show="isProcessing" class="flex items-center justify-center gap-2 relative z-10">
            <span class="animate-spin material-symbols-outlined text-lg">sync</span> İŞLENİYOR...
        </span>
    </button>
</div>
