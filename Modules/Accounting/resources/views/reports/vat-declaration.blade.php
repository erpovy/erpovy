<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/5 via-yellow-500/5 to-amber-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        KDV Beyannamesi
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">receipt_long</span>
                        Hesaplanan/İndirilecek/Ödenecek KDV Raporu
                    </p>
                </div>
                <a href="{{ route('accounting.reports.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-900 dark:text-white text-sm font-medium transition-all">
                    <span class="material-symbols-outlined text-[18px] align-middle">arrow_back</span>
                    Raporlara Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Özet Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Hesaplanan KDV -->
                <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 text-blue-400">
                            <span class="material-symbols-outlined text-[32px]">calculate</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1">Hesaplanan KDV (Satışlar)</h3>
                    <p class="text-3xl font-black text-blue-400">{{ number_format($calculated_vat, 2) }}₺</p>
                    <p class="text-xs text-slate-500 mt-2">391 - Hesaplanan KDV</p>
                </x-card>

                <!-- İndirilecek KDV -->
                <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-green-500/20 to-emerald-500/20 text-green-400">
                            <span class="material-symbols-outlined text-[32px]">trending_down</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1">İndirilecek KDV (Alışlar)</h3>
                    <p class="text-3xl font-black text-green-400">{{ number_format($deductible_vat, 2) }}₺</p>
                    <p class="text-xs text-slate-500 mt-2">191 - İndirilecek KDV</p>
                </x-card>

                <!-- Ödenecek/Devreden KDV -->
                <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-{{ $payable_vat >= 0 ? 'orange' : 'purple' }}-500/20 to-{{ $payable_vat >= 0 ? 'red' : 'pink' }}-500/20 text-{{ $payable_vat >= 0 ? 'orange' : 'purple' }}-400">
                            <span class="material-symbols-outlined text-[32px]">{{ $payable_vat >= 0 ? 'payments' : 'account_balance' }}</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-gray-600 dark:text-slate-400 mb-1">{{ $payable_vat >= 0 ? 'Ödenecek KDV' : 'Devreden KDV' }}</h3>
                    <p class="text-3xl font-black text-{{ $payable_vat >= 0 ? 'orange' : 'purple' }}-400">{{ number_format(abs($payable_vat), 2) }}₺</p>
                    <p class="text-xs text-slate-500 mt-2">{{ $payable_vat >= 0 ? 'Devlete ödenecek' : 'Sonraki döneme devredecek' }}</p>
                </x-card>
            </div>

            <!-- KDV Hesaplama Detayı -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-400">description</span>
                        KDV Hesaplama Detayı
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-slate-400">{{ $start_date }} - {{ $end_date }}</span>
                </div>

                <div class="space-y-4">
                    <!-- Hesaplanan KDV -->
                    <div class="flex items-center justify-between p-4 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-blue-500/20">
                                <span class="material-symbols-outlined text-blue-400 text-[20px]">add</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Hesaplanan KDV</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">Satışlardan kaynaklanan KDV (391)</p>
                            </div>
                        </div>
                        <p class="text-xl font-black text-blue-400">{{ number_format($calculated_vat, 2) }}₺</p>
                    </div>

                    <!-- İndirilecek KDV -->
                    <div class="flex items-center justify-between p-4 rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-100 dark:border-green-500/20">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-green-500/20">
                                <span class="material-symbols-outlined text-green-400 text-[20px]">remove</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">İndirilecek KDV</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400">Alışlardan kaynaklanan KDV (191)</p>
                            </div>
                        </div>
                        <p class="text-xl font-black text-green-400">{{ number_format($deductible_vat, 2) }}₺</p>
                    </div>

                    <!-- Ayırıcı -->
                    <div class="border-t-2 border-dashed border-gray-200 dark:border-white/10 my-4"></div>

                    <!-- Ödenecek/Devreden KDV -->
                    <div class="flex items-center justify-between p-6 rounded-xl bg-white dark:bg-gradient-to-br from-{{ $payable_vat >= 0 ? 'orange' : 'purple' }}-500/20 to-{{ $payable_vat >= 0 ? 'red' : 'pink' }}-500/20 border-2 border-{{ $payable_vat >= 0 ? 'orange' : 'purple' }}-500/30">
                        <div class="flex items-center gap-3">
                            <div class="p-3 rounded-xl bg-{{ $payable_vat >= 0 ? 'orange' : 'purple' }}-500/30">
                                <span class="material-symbols-outlined text-{{ $payable_vat >= 0 ? 'orange' : 'purple' }}-400 text-[28px]">{{ $payable_vat >= 0 ? 'payments' : 'account_balance' }}</span>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $payable_vat >= 0 ? 'Ödenecek KDV' : 'Devreden KDV' }}</p>
                                <p class="text-sm text-gray-500 dark:text-slate-400">Hesaplanan - İndirilecek = {{ $payable_vat >= 0 ? 'Ödenecek' : 'Devreden' }}</p>
                            </div>
                        </div>
                        <p class="text-4xl font-black text-{{ $payable_vat >= 0 ? 'orange' : 'purple' }}-400">{{ number_format(abs($payable_vat), 2) }}₺</p>
                    </div>
                </div>
            </x-card>

            <!-- KDV Formülü -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                <div class="flex items-center justify-center gap-4 text-center">
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 dark:text-slate-400 mb-1">Hesaplanan KDV</p>
                        <p class="text-2xl font-black text-blue-400">{{ number_format($calculated_vat, 2) }}₺</p>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 dark:text-slate-400 text-[32px]">remove</span>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 dark:text-slate-400 mb-1">İndirilecek KDV</p>
                        <p class="text-2xl font-black text-green-400">{{ number_format($deductible_vat, 2) }}₺</p>
                    </div>
                    <span class="material-symbols-outlined text-gray-400 dark:text-slate-400 text-[32px]">drag_handle</span>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 dark:text-slate-400 mb-1">{{ $payable_vat >= 0 ? 'Ödenecek' : 'Devreden' }}</p>
                        <p class="text-2xl font-black text-{{ $payable_vat >= 0 ? 'orange' : 'purple' }}-400">{{ number_format(abs($payable_vat), 2) }}₺</p>
                    </div>
                </div>
            </x-card>

            <!-- Açıklama -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-400 text-[24px]">info</span>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">KDV Beyannamesi Hakkında</h4>
                        <div class="text-sm text-gray-600 dark:text-slate-400 leading-relaxed space-y-2">
                            <p>
                                <strong class="text-blue-400">Hesaplanan KDV (391):</strong> Satışlarınızdan tahsil ettiğiniz KDV tutarıdır. 
                                Bu tutar, müşterilerinizden aldığınız ve devlete ödemeniz gereken KDV'dir.
                            </p>
                            <p>
                                <strong class="text-green-400">İndirilecek KDV (191):</strong> Alışlarınızda ödediğiniz KDV tutarıdır. 
                                Bu tutar, tedarikçilerinize ödediğiniz ve hesaplanan KDV'den düşebileceğiniz KDV'dir.
                            </p>
                            <p>
                                <strong class="text-{{ $payable_vat >= 0 ? 'orange' : 'purple' }}-400">{{ $payable_vat >= 0 ? 'Ödenecek KDV:' : 'Devreden KDV:' }}</strong> 
                                {{ $payable_vat >= 0 ? 'Hesaplanan KDV\'den İndirilecek KDV düşüldükten sonra kalan ve devlete ödemeniz gereken tutar.' : 'İndirilecek KDV, Hesaplanan KDV\'den fazla olduğunda oluşan ve sonraki döneme devredecek tutar.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>

        </div>
    </div>
</x-app-layout>
