<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    e-Ticaret Entegrasyonu
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">shopping_bag</span>
                    Mağazalarınız ve ürün senkronizasyon yönetimi
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('ecommerce.platforms.index') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95">
                    <div class="relative flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">settings</span>
                        Mağaza Ayarları
                    </div>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ loading: false }">
        <!-- Preloader Overlay -->
        <div x-show="loading" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/60 backdrop-blur-md"
             style="display: none;">
            <div class="bg-white dark:bg-slate-900 p-10 rounded-[40px] shadow-2xl flex flex-col items-center gap-6 max-w-md w-full mx-4 border border-white/10 border-t-primary/50">
                <div class="relative w-24 h-24">
                    <div class="absolute inset-0 border-[6px] border-primary/10 rounded-full"></div>
                    <div class="absolute inset-0 border-[6px] border-primary border-t-transparent rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-5xl animate-pulse">sync</span>
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">Veriler Senkronize Ediliyor</h3>
                    <p class="text-gray-500 dark:text-slate-400 font-medium">Bu işlem ürün sayısına bağlı olarak birkaç dakika sürebilir. Lütfen sayfayı kapatmayın.</p>
                </div>
                
                <!-- Simple progress bar simulation/animation -->
                <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-primary animate-[loading_2s_ease-in-out_infinite]" style="width: 30%"></div>
                </div>
            </div>
        </div>

        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            @if($platforms->isEmpty())
                <x-card class="p-12 border-none bg-gradient-to-br from-primary/10 to-purple-600/5 backdrop-blur-xl flex flex-col items-center text-center">
                    <div class="w-24 h-24 rounded-3xl bg-primary/20 flex items-center justify-center text-primary mb-6">
                        <span class="material-symbols-outlined text-5xl">storefront</span>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">Henüz Mağaza Eklenmemiş</h3>
                    <p class="text-gray-500 dark:text-slate-400 max-w-md mb-8">
                        WooCommerce mağazanızı bağlayarak ürünlerinizi ve siparişlerinizi yönetmeye hemen başlayabilirsiniz.
                    </p>
                    <a href="{{ route('ecommerce.platforms.create') }}" class="px-8 py-4 rounded-2xl bg-primary text-gray-900 dark:text-white font-black uppercase tracking-widest hover:scale-105 transition-all shadow-[0_0_30px_rgba(var(--color-primary),0.4)]">
                        İlk Mağazanızı Ekleyin
                    </a>
                </x-card>
            @else
                <!-- Platforms Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($platforms as $platform)
                        <x-card class="p-8 border-none bg-white/5 backdrop-blur-xl border border-white/5 hover:border-primary/30 transition-all relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $platform->status === 'active' ? 'bg-emerald-500/20 text-emerald-500' : 'bg-rose-500/20 text-rose-500' }}">
                                    {{ $platform->status === 'active' ? 'Aktif' : 'Pasif' }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-4xl">shopping_cart</span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $platform->name }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-slate-400 truncate max-w-[200px]">{{ $platform->store_url }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 py-4 border-y border-white/5 text-center">
                                    <div>
                                        <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Son Senkronizasyon</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $platform->last_sync_at ? $platform->last_sync_at->diffForHumans() : 'Hiç yapılmadı' }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tip</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white uppercase">{{ $platform->type }}</span>
                                    </div>
                                </div>

                                <div class="flex gap-3 mt-2">
                                    <form action="{{ route('ecommerce.platforms.sync-products', $platform) }}" method="POST" class="flex-1" @submit="loading = true">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-primary/10 text-primary font-bold text-xs uppercase tracking-widest hover:bg-primary/20 transition-all">
                                            <span class="material-symbols-outlined text-[18px]">inventory</span>
                                            Ürünleri Çek
                                        </button>
                                    </form>
                                    <form action="{{ route('ecommerce.platforms.sync-orders', $platform) }}" method="POST" class="flex-1" @submit="loading = true">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-purple-500/10 text-purple-500 font-bold text-xs uppercase tracking-widest hover:bg-purple-500/20 transition-all">
                                            <span class="material-symbols-outlined text-[18px]">list_alt</span>
                                            Siparişleri Çek
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            @endif

            <!-- Quick Stats/Info Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <x-card class="col-span-2 p-8 border-none bg-white/5 backdrop-blur-xl">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        Entegrasyon Özeti
                    </h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5">
                            <div class="flex items-center gap-4">
                                <div class="p-2 rounded-lg bg-emerald-500/20 text-emerald-500">
                                    <span class="material-symbols-outlined">check_circle</span>
                                </div>
                                <span class="font-medium text-gray-700 dark:text-slate-300">Aktif Mağaza Sayısı</span>
                            </div>
                            <span class="text-xl font-black text-gray-900 dark:text-white">{{ $platforms->where('status', 'active')->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5">
                            <div class="flex items-center gap-4">
                                <div class="p-2 rounded-lg bg-blue-500/20 text-blue-500">
                                    <span class="material-symbols-outlined">sync</span>
                                </div>
                                <span class="font-medium text-gray-700 dark:text-slate-300">Toplam Ürün Senkronizasyonu</span>
                            </div>
                            <span class="text-xl font-black text-gray-900 dark:text-white">-</span>
                        </div>
                    </div>
                </x-card>

                <x-card class="p-8 border-none bg-gradient-to-br from-primary/5 to-purple-600/5 backdrop-blur-xl">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Yardım & Destek</h3>
                    <p class="text-gray-500 dark:text-slate-400 text-sm mb-6">
                        WooCommerce API anahtarlarını oluşturmak için mağaza panelinizden Ayarlar > Gelişmiş > REST API bölümüne gidin.
                    </p>
                    <ul class="space-y-3 text-sm font-medium">
                        <li>
                            <a href="#" class="flex items-center gap-2 text-primary hover:underline">
                                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                Kurulum Kılavuzu
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-2 text-primary hover:underline">
                                <span class="material-symbols-outlined text-[18px]">help</span>
                                Sıkça Sorulan Sorular
                            </a>
                        </li>
                    </ul>
                </x-card>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(300%); }
        }
    </style>
    @endpush
</x-app-layout>
