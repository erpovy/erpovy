<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Yeni Depo Oluştur
            </h2>
            <a href="{{ route('inventory.warehouses.index') }}" class="px-4 py-2 bg-white/5 border border-white/10 hover:bg-white/10 text-white text-sm font-medium rounded-lg transition-all">
                Geri Dön
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-card class="p-8">
                <form action="{{ route('inventory.warehouses.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="code" class="block text-sm font-semibold text-white mb-2">Depo Kodu *</label>
                            <input type="text" id="code" name="code" required
                                   class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all font-mono"
                                   placeholder="Örn: DEPO-01">
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-semibold text-white mb-2">Depo Adı *</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                   placeholder="Örn: Ana Depo">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="address" class="block text-sm font-semibold text-white mb-2">Adres</label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"
                                  placeholder="Depo açık adresi..."></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="is_active" value="1" checked
                                   class="w-5 h-5 rounded border-white/10 bg-white/5 text-primary-600 focus:ring-primary-500">
                            <span class="text-white font-medium group-hover:text-primary-400 transition-colors">Hesap Aktif Olsun</span>
                        </label>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="is_default" value="1"
                                   class="w-5 h-5 rounded border-white/10 bg-white/5 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-white font-medium group-hover:text-emerald-400 transition-colors">Varsayılan Depo Olarak İşaretle</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-white/10">
                        <a href="{{ route('inventory.warehouses.index') }}" 
                           class="px-6 py-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white font-medium transition-all">
                            İptal
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-medium transition-all shadow-lg shadow-primary-500/30">
                            Depoyu Oluştur
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
