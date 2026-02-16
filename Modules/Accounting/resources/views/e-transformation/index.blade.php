<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    e-Dönüşüm Yönetimi
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">cloud_sync</span>
                    e-Fatura ve e-Arşiv belgelerinin merkezi takibi
                </p>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('accounting.e-transformation.sync-incoming') }}" method="POST">
                    @csrf
                    <button type="submit" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-gray-900 dark:text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">sync</span>
                            Portalı Senkronize Et
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-card class="p-6 border-none bg-gradient-to-br from-blue-500/10 to-blue-600/5 backdrop-blur-xl">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-2xl bg-blue-500/20">
                            <span class="material-symbols-outlined text-blue-500">download</span>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-blue-500/60 uppercase tracking-widest mb-1">Gelen e-Faturalar</span>
                            <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['incoming_count'] }}</span>
                        </div>
                    </div>
                </x-card>

                <x-card class="p-6 border-none bg-gradient-to-br from-purple-500/10 to-purple-600/5 backdrop-blur-xl">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-2xl bg-purple-500/20">
                            <span class="material-symbols-outlined text-purple-500">upload</span>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-purple-500/60 uppercase tracking-widest mb-1">Giden e-Belgeler</span>
                            <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['outgoing_count'] }}</span>
                        </div>
                    </div>
                </x-card>

                <x-card class="p-6 border-none bg-gradient-to-br from-orange-500/10 to-orange-600/5 backdrop-blur-xl">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-2xl bg-orange-500/20">
                            <span class="material-symbols-outlined text-orange-500">pending_actions</span>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-orange-500/60 uppercase tracking-widest mb-1">Onay Bekleyenler</span>
                            <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['pending_approval'] }}</span>
                        </div>
                    </div>
                </x-card>

                <x-card class="p-6 border-none bg-gradient-to-br from-rose-500/10 to-rose-600/5 backdrop-blur-xl">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-2xl bg-rose-500/20">
                            <span class="material-symbols-outlined text-rose-500">error</span>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-rose-500/60 uppercase tracking-widest mb-1">Hatalı Faturalar</span>
                            <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['failed_count'] }}</span>
                        </div>
                    </div>
                </x-card>

                <x-card class="p-6 border-none bg-gradient-to-br from-green-500/10 to-green-600/5 backdrop-blur-xl">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-2xl bg-green-500/20">
                            <span class="material-symbols-outlined text-green-500">calendar_month</span>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-green-500/60 uppercase tracking-widest mb-1">Aylık Toplam</span>
                            <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['monthly_total'] }}</span>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <a href="{{ route('accounting.e-transformation.incoming') }}" class="group block p-8 rounded-3xl bg-white/5 border border-gray-200 dark:border-white/5 hover:border-primary/30 transition-all">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-4xl">inbox</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Gelen Kutusu</h3>
                            <p class="text-gray-500 dark:text-slate-400 mt-1">Tedarikçilerden gelen e-Faturaları görüntüleyin.</p>
                        </div>
                        <span class="material-symbols-outlined ml-auto text-gray-300 group-hover:text-primary transition-colors">arrow_forward</span>
                    </div>
                </a>

                <a href="{{ route('accounting.e-transformation.outgoing') }}" class="group block p-8 rounded-3xl bg-white/5 border border-gray-200 dark:border-white/5 hover:border-primary/30 transition-all">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-4xl">outbox</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Giden Kutusu</h3>
                            <p class="text-gray-500 dark:text-slate-400 mt-1">Giden e-Fatura ve e-Arşiv belgelerini takip edin.</p>
                        </div>
                        <span class="material-symbols-outlined ml-auto text-gray-300 group-hover:text-purple-500 transition-colors">arrow_forward</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
