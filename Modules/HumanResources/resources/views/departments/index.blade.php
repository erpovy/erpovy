<x-app-layout>
    <x-slot name="header">
        İnsan Kaynakları
    </x-slot>

    <x-card>
        <div class="p-6 border-b border-gray-200 dark:border-white/5">
            <div class="flex justify-between items-center mb-6">
                <!-- Wrapper for Title -->
                <div class="flex items-center gap-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Departman Listesi</h2>
                </div>

                <a href="{{ route('hr.departments.create') }}" class="bg-primary hover:bg-primary/80 text-white font-bold py-2 px-4 rounded-lg shadow-neon transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                    Yeni Departman Ekle
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
                    <thead class="bg-gray-100 dark:bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-slate-400 uppercase tracking-wider">Departman Adı</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-slate-400 uppercase tracking-wider">Açıklama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-slate-400 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-slate-400 uppercase tracking-wider">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="bg-transparent divide-y divide-gray-200 dark:divide-white/5">
                        @forelse($departments as $department)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $department->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 dark:text-slate-300">{{ Str::limit($department->description, 50) ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($department->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400">Aktif</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400">Pasif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('hr.departments.edit', $department) }}" class="text-primary hover:text-primary-300 mr-3">Düzenle</a>
                                    
                                    <form action="{{ route('hr.departments.destroy', $department) }}" method="POST" class="inline-block" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 dark:text-slate-500">
                                    Henüz kayıtlı departman bulunmamaktadır.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $departments->links() }}
            </div>
        </div>
    </x-card>
</x-app-layout>
