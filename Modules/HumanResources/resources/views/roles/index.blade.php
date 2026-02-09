<x-app-layout>
    <x-card>
        <div class="p-6 border-b border-white/5">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ __('Rol Yönetimi') }}
                </h2>
                <a href="{{ route('hr.roles.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-600 text-white rounded-lg transition-colors text-sm font-medium">
                    + Yeni Rol
                </a>
            </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-400">
                <thead class="bg-white/5 text-xs uppercase text-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">Rol Adı</th>
                        <th scope="col" class="px-6 py-3">Eklenme Tarihi</th>
                        <th scope="col" class="px-6 py-3 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($roles as $role)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-white">{{ $role->name }}</div>
                            </td>
                            <td class="px-6 py-4">{{ $role->created_at->format('d.m.Y H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('hr.roles.show', $role) }}" class="text-blue-400 hover:text-blue-300 transition-colors" title="Görüntüle">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    <a href="{{ route('hr.roles.edit', $role) }}" class="text-yellow-400 hover:text-yellow-300 transition-colors" title="Düzenle">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('hr.roles.destroy', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu rolü silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors pt-1" title="Sil">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Kayıtlı rol bulunamadı.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($roles->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $roles->links() }}
            </div>
        @endif
        </div>
    </x-card>
</x-app-layout>
