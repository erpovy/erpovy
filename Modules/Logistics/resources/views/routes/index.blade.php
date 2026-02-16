<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                        Rota Planlama
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1 font-medium">Günlük teslimat rotalarını ve duraklarını yönetin.</p>
                </div>
                <a href="{{ route('logistics.routes.create') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-500/30 group">
                    <span class="material-symbols-outlined mr-2 transition-transform group-hover:rotate-90">add</span>
                    YENİ ROTA OLUŞTUR
                </a>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 p-6 rounded-3xl">
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest">Aktif Rotalar</p>
                    <div class="flex items-end justify-between mt-2">
                        <h3 class="text-4xl font-black text-indigo-600 dark:text-indigo-400">{{ $routes->where('status', 'in_progress')->count() }}</h3>
                        <span class="material-symbols-outlined text-indigo-500/50 text-4xl">route</span>
                    </div>
                </div>
                <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 p-6 rounded-3xl">
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest">Taslaklar</p>
                    <div class="flex items-end justify-between mt-2">
                        <h3 class="text-4xl font-black text-amber-600 dark:text-amber-400">{{ $routes->where('status', 'draft')->count() }}</h3>
                        <span class="material-symbols-outlined text-amber-500/50 text-4xl">history_edu</span>
                    </div>
                </div>
                <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 p-6 rounded-3xl">
                    <p class="text-sm font-black text-gray-500 uppercase tracking-widest">Tamamlanan</p>
                    <div class="flex items-end justify-between mt-2">
                        <h3 class="text-4xl font-black text-emerald-600 dark:text-emerald-400">{{ $routes->where('status', 'completed')->count() }}</h3>
                        <span class="material-symbols-outlined text-emerald-500/50 text-4xl">task_alt</span>
                    </div>
                </div>
            </div>

            <!-- Routes Table -->
            <div class="bg-white/50 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-white/10 bg-gray-50/50 dark:bg-white/5">
                                <th class="px-6 py-4 text-xs font-black uppercase text-gray-500 tracking-wider">Rota Bilgisi</th>
                                <th class="px-6 py-4 text-xs font-black uppercase text-gray-500 tracking-wider">Araç</th>
                                <th class="px-6 py-4 text-xs font-black uppercase text-gray-500 tracking-wider">Tarih</th>
                                <th class="px-6 py-4 text-xs font-black uppercase text-gray-500 tracking-wider">Durak Sayısı</th>
                                <th class="px-6 py-4 text-xs font-black uppercase text-gray-500 tracking-wider">Durum</th>
                                <th class="px-6 py-4 text-xs font-black uppercase text-gray-500 tracking-wider text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                            @forelse($routes as $route)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-5">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $route->name }}</p>
                                    @if($route->total_distance)
                                    <p class="text-xs text-gray-500">{{ $route->total_distance }} km</p>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if($route->vehicle)
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-gray-400 text-sm">local_shipping</span>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $route->vehicle->plate_number }}</span>
                                    </div>
                                    @else
                                    <span class="text-xs text-gray-400 italic">Atanmadı</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $route->planned_date->format('d.m.Y') }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-400">
                                        {{ count($route->stops ?? []) }} Durak
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusClasses = [
                                            'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-400',
                                            'optimized' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400',
                                            'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                                            'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
                                        ];
                                        $statusLabels = [
                                            'draft' => 'Taslak',
                                            'optimized' => 'Optimize Edildi',
                                            'in_progress' => 'Yolda',
                                            'completed' => 'Tamamlandı',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider {{ $statusClasses[$route->status] ?? $statusClasses['draft'] }}">
                                        {{ $statusLabels[$route->status] ?? $route->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('logistics.routes.show', $route) }}" 
                                           class="p-2 hover:bg-gray-100 dark:hover:bg-white/10 text-gray-500 rounded-xl transition-colors">
                                            <span class="material-symbols-outlined text-sm">visibility</span>
                                        </a>
                                        <a href="{{ route('logistics.routes.edit', $route) }}" 
                                           class="p-2 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 rounded-xl transition-colors">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                        </a>
                                        <form action="{{ route('logistics.routes.destroy', $route) }}" method="POST" class="inline-block" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 hover:bg-rose-50 dark:hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 rounded-xl transition-colors">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-white/5 rounded-2xl flex items-center justify-center mb-4 text-gray-400">
                                            <span class="material-symbols-outlined text-4xl">map</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Henüz Rota Yok</h3>
                                        <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto mt-2">İlk teslimat rotanızı oluşturarak başlayın.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($routes->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-white/10 bg-gray-50/30 dark:bg-white/2">
                    {{ $routes->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
