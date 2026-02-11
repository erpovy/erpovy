<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-400">history</span>
            {{ __('Tüm Aktiviteler') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card class="p-6 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 backdrop-blur-xl">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sistem Aktiviteleri</h3>
                        <p class="text-gray-600 dark:text-slate-400 text-sm">Son gerçekleşen işlemlerin listesi</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($activities as $activity)
                        <a href="{{ $activity['link'] ?? '#' }}" class="block group">
                            <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 border border-gray-200 dark:border-white/5 hover:border-gray-300 dark:hover:border-white/20 transition-all duration-300">
                                <!-- Icon -->
                                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-{{ $activity['color'] }}-500/10 text-{{ $activity['color'] }}-400 group-hover:scale-110 transition-transform shadow-[0_0_15px_rgba(0,0,0,0.3)]">
                                    <span class="material-symbols-outlined text-2xl">{{ $activity['icon'] }}</span>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-gray-900 dark:text-white font-bold text-base truncate pr-4 group-hover:text-{{ $activity['color'] }}-400 transition-colors">
                                            {{ $activity['text'] }}
                                        </h4>
                                        <span class="text-xs font-medium text-gray-600 dark:text-slate-500 bg-gray-100 dark:bg-slate-900/50 px-2 py-1 rounded-lg border border-gray-300 dark:border-white/5 whitespace-nowrap">
                                            {{ $activity['time'] }}
                                        </span>
                                    </div>
                                    @if(isset($activity['description']) && $activity['description'])
                                        <p class="text-sm text-gray-600 dark:text-slate-400 truncate">
                                            {{ $activity['description'] }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Arrow -->
                                <div class="text-gray-500 dark:text-slate-600 group-hover:text-gray-900 dark:group-hover:text-white transition-colors group-hover:translate-x-1 duration-300">
                                    <span class="material-symbols-outlined">arrow_forward</span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-500">
                                <span class="material-symbols-outlined text-4xl">history_toggle_off</span>
                            </div>
                            <h3 class="text-white font-bold text-lg">Haraket Yok</h3>
                            <p class="text-slate-500">Henüz sistemde kaydedilmiş bir aktivite bulunmuyor.</p>
                        </div>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
