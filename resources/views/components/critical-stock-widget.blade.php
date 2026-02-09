<!-- Kritik Stok Uyarıları Widget -->
<div x-data="criticalStockWidget()" x-init="loadData()" class="bg-slate-900/50 backdrop-blur-sm border border-white/10 rounded-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-red-400">warning</span>
            Kritik Stok Uyarıları
        </h3>
        <a href="{{ route('inventory.analytics.index') }}" class="text-primary-400 hover:text-primary-300 text-sm">
            Tümünü Gör →
        </a>
    </div>

    <div x-show="loading" class="text-center py-4">
        <span class="text-slate-400">Yükleniyor...</span>
    </div>

    <div x-show="!loading && products.length === 0" class="text-center py-4">
        <span class="text-green-400">✓ Kritik stok yok</span>
    </div>

    <div x-show="!loading && products.length > 0" class="space-y-3">
        <template x-for="product in products" :key="product.id">
            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span x-show="product.risk_level === 'critical'" class="text-xl">🔴</span>
                        <span x-show="product.risk_level === 'high'" class="text-xl">🟠</span>
                        <div>
                            <p class="text-sm font-medium text-white" x-text="product.name"></p>
                            <p class="text-xs text-slate-400">
                                <span x-text="product.code"></span> •
                                Stok: <span x-text="product.current_stock"></span>/<span x-text="product.min_stock"></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-400" x-text="product.days_of_stock + ' gün'"></p>
                    <a :href="`/inventory/analytics/product/${product.id}`" class="text-xs text-primary-400 hover:text-primary-300">Detay</a>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function criticalStockWidget() {
    return {
        products: [],
        loading: true,
        
        async loadData() {
            try {
                const response = await fetch('/inventory/analytics/widget');
                this.products = await response.json();
            } catch (error) {
                console.error('Widget yüklenemedi:', error);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
