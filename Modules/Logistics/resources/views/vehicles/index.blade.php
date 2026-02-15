<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-blue-500/10 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-4">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Araç Yönetimi
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Filo ve Araç Durum Takibi
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logistics.vehicles.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-2xl text-sm font-bold shadow-lg shadow-indigo-500/20 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        Yeni Araç Ekle
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="bg-white dark:bg-[#0f172a]/40 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-3xl shadow-glass overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/10">
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Plaka</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Araç Tipi / Marka</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Kapasite</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Durum</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @forelse($vehicles as $vehicle)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-indigo-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-truck text-indigo-500"></i>
                                        </div>
                                        <span class="font-black text-gray-900 dark:text-white tracking-tight">{{ $vehicle->plate_number }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $vehicle->type }}</p>
                                    <p class="text-xs text-gray-500">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ number_format($vehicle->capacity_weight, 0) }} kg</span>
                                </td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusClasses = [
                                            'available' => 'bg-emerald-500/10 text-emerald-500',
                                            'on_route' => 'bg-blue-500/10 text-blue-500',
                                            'maintenance' => 'bg-red-500/10 text-red-500',
                                        ];
                                        $statusLabels = [
                                            'available' => 'Müsait',
                                            'on_route' => 'Yolda',
                                            'maintenance' => 'Bakımda',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusClasses[$vehicle->status] ?? 'bg-gray-500/10 text-gray-500' }}">
                                        {{ $statusLabels[$vehicle->status] ?? $vehicle->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('logistics.vehicles.edit', $vehicle) }}" class="p-2 hover:bg-indigo-500/10 text-indigo-500 rounded-xl transition-colors">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('logistics.vehicles.destroy', $vehicle) }}" method="POST" onsubmit="return confirm('Bu aracı silmek istediğinize emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 hover:bg-red-500/10 text-red-500 rounded-xl transition-colors">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 italic">
                                    Henüz kayıtlı araç bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($vehicles->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
