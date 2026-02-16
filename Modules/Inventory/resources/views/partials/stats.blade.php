<!-- Dashboard Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Products -->
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 p-6 shadow-sm group">
        <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-blue-50/50 dark:from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-[24px]">inventory_2</span>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 dark:text-slate-400">Toplam Ürün</p>
                <h3 class="font-display text-2xl font-black text-gray-900 dark:text-white">{{ $totalProducts }}</h3>
            </div>
        </div>
    </div>

    <!-- Total Stock Value -->
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 p-6 shadow-sm group">
        <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-emerald-50/50 dark:from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                <span class="material-symbols-outlined text-[24px]">payments</span>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 dark:text-slate-400">Toplam Stok Değeri</p>
                <h3 class="font-display text-2xl font-black text-gray-900 dark:text-white">₺{{ number_format($totalStockValue, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Critical Stock -->
    <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 p-6 shadow-sm group">
        <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-red-50/50 dark:from-red-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="relative flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 dark:bg-red-500/20 text-red-600 dark:text-red-400">
                <span class="material-symbols-outlined text-[24px]">warning</span>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-500 dark:text-slate-400">Kritik Stok</p>
                <h3 class="font-display text-2xl font-black text-gray-900 dark:text-white">{{ $criticalStockCount }}</h3>
            </div>
        </div>
        @if($criticalStockCount > 0)
            <a href="{{ route('inventory.analytics.index') }}" class="absolute bottom-6 right-6 text-xs font-bold text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 flex items-center gap-1">
                İncele <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </a>
        @endif
    </div>

    <!-- Link to Full Analytics -->
    <a href="{{ route('inventory.analytics.index') }}" class="col-span-full md:col-start-auto flex items-center justify-center gap-2 p-3 rounded-xl border border-dashed border-gray-300 dark:border-white/20 text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-primary-400 hover:border-primary/50 dark:hover:border-primary/50 transition-all group">
        <span class="material-symbols-outlined group-hover:scale-110 transition-transform">insights</span>
        <span class="text-sm font-bold">Detaylı Analiz Raporu</span>
    </a>
</div>
