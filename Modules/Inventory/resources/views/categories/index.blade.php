<x-app-layout>
    <x-slot name="header">
        Kategori Yönetimi
    </x-slot>

    <x-card>
        <div class="p-6 border-b border-white/5">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-white">Kategori Listesi</h2>
                <a href="{{ route('inventory.categories.create') }}" class="bg-primary-600 hover:bg-primary-500 text-white font-bold py-2 px-4 rounded-lg shadow-neon transition-all flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Yeni Kategori
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">İkon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Kategori Adı</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Açıklama</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wider">Alt Kategoriler</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wider">Ürün Sayısı</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="bg-transparent divide-y divide-white/5">
                        @forelse($categories as $category)
                            @include('inventory::categories.partials.category-row', ['category' => $category, 'level' => 0])
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                    Henüz kategori tanımlanmamış.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-card>
</x-app-layout>
