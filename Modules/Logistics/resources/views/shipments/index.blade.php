<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-cyan-500/10 to-indigo-500/10 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-4">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Sevkiyat Yönetimi
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Tüm Sevkiyatlar ve Teslimat Süreçleri
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('logistics.shipments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-2xl text-sm font-bold shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        Yeni Sevkiyat Oluştur
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
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Takip No / Müşteri</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Güzergah</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Tahmini Teslimat</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">Durum</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @forelse($shipments as $shipment)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <i class="fa-solid fa-box text-blue-500"></i>
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 dark:text-white tracking-tight">#{{ $shipment->tracking_number }}</p>
                                            <p class="text-xs text-gray-500 font-bold uppercase tracking-tighter">{{ $shipment->contact->name ?? 'Müşteri Belirtilmemiş' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500/50 shrink-0"></span>
                                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $shipment->origin }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-red-500/50 shrink-0"></span>
                                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $shipment->destination }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ $shipment->estimated_delivery ? $shipment->estimated_delivery->format('d.m.Y') : 'Belirtilmedi' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-500/10 text-amber-500',
                                            'in_transit' => 'bg-blue-500/10 text-blue-500',
                                            'delivered' => 'bg-emerald-500/10 text-emerald-500',
                                            'cancelled' => 'bg-red-500/10 text-red-500',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Beklemede',
                                            'in_transit' => 'Taşıma Halinde',
                                            'delivered' => 'Teslim Edildi',
                                            'cancelled' => 'İptal Edildi',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusClasses[$shipment->status] ?? 'bg-gray-500/10 text-gray-500' }}">
                                        {{ $statusLabels[$shipment->status] ?? $shipment->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('logistics.shipments.show', $shipment) }}" class="p-2 hover:bg-gray-500/10 text-gray-500 rounded-xl transition-colors" title="Detayları Gör">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('logistics.shipments.track', ['number' => $shipment->tracking_number]) }}" class="p-2 hover:bg-indigo-500/10 text-indigo-500 rounded-xl transition-colors" title="Takip Et">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </a>
                                        <a href="{{ route('logistics.shipments.edit', $shipment) }}" class="p-2 hover:bg-blue-500/10 text-blue-500 rounded-xl transition-colors">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('logistics.shipments.destroy', $shipment) }}" method="POST" onsubmit="return confirm('Bu sevkiyatı silmek istediğinize emin misiniz?')">
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
                                    Henüz kayıtlı sevkiyat bulunmuyor.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($shipments->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5">
                    {{ $shipments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
