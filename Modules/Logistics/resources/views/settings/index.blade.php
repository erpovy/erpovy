<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-cyan-500/10 to-indigo-500/10 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-4">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Lojistik Ayarları
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Modül Yapılandırması ve Varsayılan Değerler
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logistics.dashboard') }}" class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 px-4 py-2 rounded-xl text-sm font-bold text-gray-700 dark:text-white hover:bg-gray-50 transition-all flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-arrow-left"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-[#0f172a]/40 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl shadow-glass overflow-hidden shadow-2xl shadow-indigo-500/5">
                <form action="{{ route('logistics.settings.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Tracking Prefix -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <i class="fa-solid fa-hashtag text-indigo-500"></i>
                                Takip No Öneki
                            </label>
                            <input type="text" name="tracking_prefix" value="{{ old('tracking_prefix', $settings['tracking_prefix'] ?? 'TRK-') }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all font-bold"
                                placeholder="Örn: TRK-">
                            <p class="text-[10px] text-gray-400 italic">Sevkiyat takip numaralarının başlangıç karakterleri.</p>
                        </div>

                        <!-- Default Carrier -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <i class="fa-solid fa-truck text-indigo-500"></i>
                                Varsayılan Taşıyıcı
                            </label>
                            <input type="text" name="default_carrier" value="{{ old('default_carrier', $settings['default_carrier'] ?? '') }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all"
                                placeholder="Örn: Yurtiçi Kargo">
                        </div>

                        <!-- Default Origin Address -->
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <i class="fa-solid fa-warehouse text-indigo-500"></i>
                                Varsayılan Çıkış Noktası (Depo)
                            </label>
                            <input type="text" name="default_origin" value="{{ old('default_origin', $settings['default_origin'] ?? '') }}"
                                class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all"
                                placeholder="Örn: İstanbul Ana Depo">
                        </div>

                        <!-- Auto Generate Tracking Toggle -->
                        <div class="md:col-span-2 flex items-center justify-between p-4 bg-indigo-500/5 rounded-2xl border border-indigo-500/10">
                            <div>
                                <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Otomatik Takip No</h4>
                                <p class="text-xs text-gray-500">Yeni sevkiyatlarda otomatik takip numarası üretilsin mi?</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="auto_generate_tracking" value="1" 
                                    class="sr-only peer" {{ ($settings['auto_generate_tracking'] ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-100 dark:border-white/5">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-indigo-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            AYARLARI KAYDET
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
