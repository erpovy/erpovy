<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Kullanıcı Yönetimi') }}
            </h2>
            <a href="{{ route('hr.users.create') }}" class="px-4 py-2 bg-primary hover:bg-primary-600 text-white rounded-lg transition-colors text-sm font-medium">
                + Yeni Kullanıcı
            </a>
        </div>
    </x-slot>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-400">
                <thead class="bg-white/5 text-xs uppercase text-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-3">Kullanıcı</th>
                        <th scope="col" class="px-6 py-3">E-posta</th>
                        <th scope="col" class="px-6 py-3">Departman</th>
                        <th scope="col" class="px-6 py-3">Rol</th>
                        <th scope="col" class="px-6 py-3">Durum</th>
                        <th scope="col" class="px-6 py-3 text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-primary to-purple-600 p-[1px]">
                                        <div class="h-full w-full rounded-full bg-slate-900 flex items-center justify-center font-bold text-white text-xs">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">Eklenme: {{ $user->created_at->format('d.m.Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->employee)
                                    <span class="text-gray-300">{{ $user->employee->department?->name ?? '-' }}</span>
                                @else
                                    <span class="text-gray-600">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-400/20">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/20">Aktif</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button class="text-blue-400 hover:text-blue-300 transition-colors" title="Görüntüle">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <a href="{{ route('hr.users.edit', $user) }}" class="text-yellow-400 hover:text-yellow-300 transition-colors" title="Düzenle">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('hr.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
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
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Kayıtlı kullanıcı bulunamadı.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $users->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>
