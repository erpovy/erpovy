<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('ecommerce.platforms.index') }}" class="p-2 rounded-xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    Yeni Mağaza Ekle
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium">
                    WooCommerce mağazanızı Erpovy'ye bağlayın
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-4xl px-6">
            <x-card class="p-8 border-none bg-white/5 backdrop-blur-xl">
                <form action="{{ route('ecommerce.platforms.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Mağaza Adı</label>
                            <input type="text" name="name" class="w-full px-4 py-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 outline-none transition-all" placeholder="Örn: Online Mağaza" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Platform Tipi</label>
                            <select name="type" class="w-full px-4 py-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 outline-none transition-all">
                                <option value="woocommerce">WooCommerce</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Mağaza URL (https://...)</label>
                        <input type="url" name="store_url" class="w-full px-4 py-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 outline-none transition-all" placeholder="https://magazaniz.com" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Consumer Key (CK_...)</label>
                            <input type="text" name="consumer_key" class="w-full px-4 py-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 outline-none transition-all" placeholder="ck_xxxx..." required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Consumer Secret (CS_...)</label>
                            <input type="password" name="consumer_secret" class="w-full px-4 py-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary/50 outline-none transition-all" placeholder="cs_xxxx..." required>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-white/5">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Entegrasyon Ayarları</label>
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox" name="settings[sync_images]" value="1" checked class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary bg-gray-100 dark:bg-white/5">
                                </div>
                                <span class="text-sm font-bold text-gray-700 dark:text-slate-300">Ürün Görsellerini Senkronize Et</span>
                            </label>
                            <p class="text-[10px] text-gray-500 pl-8">Etkinleştirildiğinde, ürün görselleri WooCommerce'den indirilir. Devre dışı bırakmak disk alanından tasarruf sağlar.</p>
                        </div>
                    </div>

                    <div class="space-y-2 pt-4 border-t border-white/5">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest pl-1">Durum</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="status" value="active" checked class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary">
                                <span class="text-sm font-bold text-gray-700 dark:text-slate-300">Aktif</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="status" value="passive" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 focus:ring-primary">
                                <span class="text-sm font-bold text-gray-700 dark:text-slate-300">Pasif</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end">
                        <button type="submit" class="px-12 py-4 rounded-2xl bg-primary text-gray-900 dark:text-white font-black uppercase tracking-widest hover:scale-105 transition-all shadow-[0_0_30px_rgba(var(--color-primary),0.4)]">
                            Kaydet ve Bağlantıyı Kur
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
