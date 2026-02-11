<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white" style="background: linear-gradient(135deg, #3b82f6, #8b5cf6);">
                    <span class="material-symbols-outlined text-[28px]">storefront</span>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight">
                        {{ __('Modül Pazarı') }}
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium">
                        Sisteminizi genişletmek için modülleri keşfedin
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($modules as $module)
                    <div class="bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl overflow-hidden group hover:border-gray-300 dark:hover:border-white/20 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/10 flex flex-col h-full">
                        <!-- Card Header with Icon and Status -->
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500/20 to-purple-500/20 border border-blue-500/20 flex items-center justify-center group-hover:scale-110 group-hover:border-blue-500/40 transition-all duration-300">
                                    <span class="material-symbols-outlined text-[32px]" style="color: #60a5fa;">{{ $module['icon'] }}</span>
                                </div>
                                <div class="flex flex-col items-end gap-1.5">
                                    @if($module['is_installed'])
                                        <span class="px-3 py-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-500/20 shadow-lg shadow-emerald-500/5">
                                            <span class="inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                                Yüklü
                                            </span>
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 rounded-lg bg-slate-500/10 text-slate-400 text-[10px] font-black uppercase tracking-widest border border-slate-500/20">
                                            Yakında
                                        </span>
                                    @endif
                                    <span class="text-[10px] text-slate-500 font-mono font-bold">{{ $module['version'] }}</span>
                                </div>
                            </div>
                            
                            <!-- Module Info -->
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-400 transition-colors">
                                {{ $module['name'] }}
                            </h3>
                            <p class="text-gray-600 dark:text-slate-400 text-sm leading-relaxed">
                                {{ $module['description'] }}
                            </p>
                        </div>
                        
                        <!-- Card Footer with Action Button -->
                        <div class="p-4 bg-gradient-to-br from-black/30 to-black/20 border-t border-white/5">
                             @if($module['is_installed'])
                                <button class="w-full py-3 rounded-xl bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider hover:bg-red-500/10 hover:text-red-400 hover:border-red-500/30 transition-all border border-white/5 cursor-not-allowed opacity-60" disabled>
                                    <span class="flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                        Kaldır
                                    </span>
                                </button>
                             @else
                                <button class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 text-white text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 hover:scale-[1.02] transition-all border border-blue-400/20" disabled>
                                    <span class="flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">download</span>
                                        Satın Al / Yükle
                                    </span>
                                </button>
                             @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
