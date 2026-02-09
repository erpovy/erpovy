<x-app-layout>
    <x-slot name="header">
        İnsan Kaynakları
    </x-slot>

    <x-card>
        <div class="p-6 border-b border-white/5">
            <div class="flex justify-between items-center mb-6">
                <!-- Wrapper for Title -->
                <div class="flex items-center gap-6">
                    <h2 class="text-xl font-bold text-white">Personel Listesi</h2>
                </div>

                <a href="{{ route('hr.employees.create') }}" class="bg-primary hover:bg-primary/80 text-white font-bold py-2 px-4 rounded-lg shadow-neon transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                    Yeni Çalışan Ekle
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/5">
                    <thead class="bg-white/5">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Ad Soyad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Departman / Pozisyon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">İletişim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">İşe Giriş</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="bg-transparent divide-y divide-white/5">
                        @forelse($employees as $employee)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold border border-white/10">
                                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">{{ $employee->full_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-300">{{ $employee->department->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $employee->position ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-300">{{ $employee->email }}</div>
                                    <div class="text-xs text-slate-500">{{ $employee->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($employee->status === 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400">Aktif</span>
                                    @elseif($employee->status === 'inactive')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-500/20 dark:text-gray-400">Pasif</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400">Ayrıldı</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                    {{ $employee->hire_date ? $employee->hire_date->format('d.m.Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('hr.employees.edit', $employee) }}" class="text-primary hover:text-primary-300 mr-3">Düzenle</a>
                                    
                                    <form action="{{ route('hr.employees.destroy', $employee) }}" method="POST" class="inline-block" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                    Henüz kayıtlı çalışan bulunmamaktadır.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $employees->links() }}
            </div>
        </div>
    </x-card>
</x-app-layout>
