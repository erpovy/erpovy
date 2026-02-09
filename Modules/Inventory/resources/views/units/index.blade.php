<x-app-layout>
    <x-slot name="header">Birim Yönetimi</x-slot>
    <x-card>
        <div class="p-6 border-b border-white/5">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white">Birim Listesi</h2>
                <a href="{{ route('inventory.units.create') }}" class="bg-primary-600 hover:bg-primary-500 text-white font-bold py-2 px-4 rounded-lg shadow-neon transition-all flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Yeni Birim
                </a>
            </div>

            @if(session('success'))<div class="mb-4 p-4 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400">{{ session('success') }}</div>@endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Birim Adı</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Sembol</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Tip</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase">Ürün Sayısı</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase">Durum</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="bg-transparent divide-y divide-white/5">
                        @forelse($units as $unit)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $unit->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-primary-400 font-mono">{{ $unit->symbol }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                        {{ ucfirst($unit->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-500/10 text-green-400 border border-green-500/20">{{ $unit->products_count }}</span></td>
                                <td class="px-6 py-4 text-center">
                                    @if($unit->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-500/10 text-green-400 border border-green-500/20">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-500/10 text-red-400 border border-red-500/20">Pasif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('inventory.units.conversions', $unit) }}" class="p-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 border border-blue-500/20 transition-all" title="Çevirimler">
                                            <span class="material-symbols-outlined text-sm">swap_horiz</span>
                                        </a>
                                        <a href="{{ route('inventory.units.edit', $unit) }}" class="p-2 rounded-lg bg-slate-500/10 text-slate-400 hover:bg-slate-500/20 hover:text-white border border-slate-500/20 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('inventory.units.destroy', $unit) }}" method="POST" class="inline" onsubmit="return confirm('Bu birimi silmek istediğinizden emin misiniz?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 border border-red-500/20 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">Henüz birim tanımlanmamış.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $units->links() }}</div>
        </div>
    </x-card>
</x-app-layout>
