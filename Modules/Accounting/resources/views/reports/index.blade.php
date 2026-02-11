<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Finansal Raporlar
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">assessment</span>
                        Gelir Tablosu, Bilanço, Mizan ve KDV Beyannamesi
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Rapor Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Gelir Tablosu -->
                <a href="{{ route('accounting.reports.income-statement') }}" class="group relative block">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl hover:border-green-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="p-3 rounded-2xl bg-gradient-to-br from-green-500/20 to-emerald-500/20 text-green-400">
                                <span class="material-symbols-outlined text-[32px]">trending_up</span>
                            </div>
                            <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 group-hover:text-green-400 transition-colors">arrow_forward</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Gelir Tablosu</h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400">Gelir/Gider analizi ve Net Kar/Zarar hesaplaması</p>
                    </x-card>
                </a>

                <!-- Bilanço -->
                <a href="{{ route('accounting.reports.balance-sheet') }}" class="group relative block">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl hover:border-blue-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 text-blue-400">
                                <span class="material-symbols-outlined text-[32px]">account_balance</span>
                            </div>
                            <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 group-hover:text-blue-400 transition-colors">arrow_forward</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Bilanço</h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400">Aktif/Pasif dengesi ve finansal durum</p>
                    </x-card>
                </a>

                <!-- Mizan -->
                <a href="{{ route('accounting.reports.trial-balance') }}" class="group relative block">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl hover:border-purple-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="p-3 rounded-2xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 text-purple-400">
                                <span class="material-symbols-outlined text-[32px]">table_chart</span>
                            </div>
                            <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 group-hover:text-purple-400 transition-colors">arrow_forward</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Mizan</h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400">Tüm hesapların borç/alacak toplamları</p>
                    </x-card>
                </a>

                <!-- KDV Beyannamesi -->
                <a href="{{ route('accounting.reports.vat-declaration') }}" class="group relative block">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/20 to-amber-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl hover:border-orange-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-4">
                            <div class="p-3 rounded-2xl bg-gradient-to-br from-orange-500/20 to-amber-500/20 text-orange-400">
                                <span class="material-symbols-outlined text-[32px]">receipt_long</span>
                            </div>
                            <span class="material-symbols-outlined text-gray-400 dark:text-slate-600 group-hover:text-orange-400 transition-colors">arrow_forward</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">KDV Beyannamesi</h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400">Hesaplanan/İndirilecek/Ödenecek KDV</p>
                    </x-card>
                </a>

            </div>

            <!-- Bilgilendirme -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl">
                <div class="flex items-start gap-4">
                    <div class="p-2 rounded-xl bg-blue-500/10 text-blue-400">
                        <span class="material-symbols-outlined text-[24px]">info</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Finansal Raporlar Hakkında</h4>
                        <p class="text-sm text-gray-700 dark:text-slate-400 leading-relaxed">
                            Tüm raporlar <span class="text-gray-900 dark:text-white font-semibold">Türk Muhasebe Standartları</span> ve 
                            <span class="text-gray-900 dark:text-white font-semibold">Tek Düzen Hesap Planı</span>'na uygun olarak hazırlanmaktadır. 
                            Raporlar sadece <span class="text-green-400 font-semibold">onaylanmış işlemleri</span> içerir ve 
                            tarih aralığı filtreleme ile özelleştirilebilir.
                        </p>
                    </div>
                </div>
            </x-card>

        </div>
    </div>
</x-app-layout>
