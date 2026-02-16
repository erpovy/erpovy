<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('ecommerce.index') }}" class="p-2 rounded-xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Mağaza Ayarları
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium">
                        Bağlı olan e-ticaret platformlarını yönetin
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('ecommerce.platforms.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-gray-900 dark:text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                    <div class="relative flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        Yeni Mağaza Ekle
                    </div>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8">
            <x-card class="overflow-hidden border-none bg-white/5 backdrop-blur-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/2">
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Mağaza Adı</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Tip</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">URL</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Durum</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Son Senkronizasyon</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($platforms as $platform)
                                <tr class="group hover:bg-white/2 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $platform->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-white/10 text-[10px] font-black uppercase text-gray-400">
                                            {{ $platform->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 italic text-sm text-gray-500 dark:text-slate-400">
                                        {{ $platform->store_url }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $platform->status === 'active' ? 'bg-emerald-500/20 text-emerald-500' : 'bg-rose-500/20 text-rose-500' }}">
                                            {{ $platform->status === 'active' ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $platform->last_sync_at ? $platform->last_sync_at->format('d.m.Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="testConnection('{{ $platform->id }}')" class="p-2 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500/20 transition-colors" title="Bağlantıyı Test Et">
                                                <span class="material-symbols-outlined text-[20px]">cloud_sync</span>
                                            </button>
                                            <a href="{{ route('ecommerce.platforms.edit', $platform) }}" class="p-2 rounded-lg bg-amber-500/10 text-amber-500 hover:bg-amber-500/20 transition-colors">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <form action="{{ route('ecommerce.platforms.destroy', $platform) }}" method="POST" class="inline" onsubmit="return confirm('Bu mağazayı silmek istediğinizden emin misiniz?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500/20 transition-colors">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400 italic">
                                        Mağaza kaydı bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>

    @push('scripts')
    <script>
        function testConnection(id) {
            if (!confirm('Bağlantı testi yapılsın mı?')) return;
            
            fetch(`/ecommerce/platforms/${id}/test-connection`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                alert('Bağlantı sırasında bir hata oluştu.');
            });
        }
    </script>
    @endpush
</x-app-layout>
