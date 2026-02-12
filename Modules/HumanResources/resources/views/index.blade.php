<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[28px]">badge</span>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight">
                        {{ __('İnsan Kaynakları') }}
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium">
                        {{ now()->translatedFormat('d F Y, l') }}
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-12 space-y-8">
            
            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
                {{-- Total Employees --}}
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-blue-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-blue-500/10 text-blue-500 ring-1 ring-blue-500/20">
                                <span class="material-symbols-outlined text-[24px]">groups</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Toplam Çalışan</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $totalEmployees }}</div>
                        <div class="text-blue-400 text-sm mt-4 flex items-center font-bold bg-blue-500/10 w-fit px-3 py-1.5 rounded-lg border border-blue-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">person_add</span>
                            Tüm Personel
                        </div>
                    </div>
                </x-card>

                {{-- Active Employees --}}
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-green-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-green-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-green-500/10 text-green-500 ring-1 ring-green-500/20">
                                <span class="material-symbols-outlined text-[24px]">check_circle</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Aktif Çalışan</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $activeEmployees }}</div>
                        <div class="text-green-400 text-sm mt-4 flex items-center font-bold bg-green-500/10 w-fit px-3 py-1.5 rounded-lg border border-green-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">work</span>
                            Çalışan Durumda
                        </div>
                    </div>
                </x-card>

                {{-- Total Departments --}}
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-purple-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-purple-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-purple-500/10 text-purple-500 ring-1 ring-purple-500/20">
                                <span class="material-symbols-outlined text-[24px]">corporate_fare</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Departmanlar</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $totalDepartments }}</div>
                        <div class="text-purple-400 text-sm mt-4 flex items-center font-bold bg-purple-500/10 w-fit px-3 py-1.5 rounded-lg border border-purple-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">apartment</span>
                            Aktif Birimler
                        </div>
                    </div>
                </x-card>

                {{-- Pending Leaves --}}
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-orange-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-orange-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-orange-500/10 text-orange-500 ring-1 ring-orange-500/20">
                                <span class="material-symbols-outlined text-[24px]">pending_actions</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Bekleyen İzinler</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $pendingLeaves }}</div>
                        <div class="text-orange-400 text-sm mt-4 flex items-center font-bold bg-orange-500/10 w-fit px-3 py-1.5 rounded-lg border border-orange-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">schedule</span>
                            Onay Bekliyor
                        </div>
                    </div>
                </x-card>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Recent Employees --}}
                <div class="lg:col-span-2">
                    <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden rounded-[2.5rem] shadow-2xl">
                        <div class="p-8 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-gray-50 dark:bg-white/[0.02]">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                                <div class="p-2.5 rounded-xl bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined text-[24px]">person_add</span>
                                </div>
                                Son Eklenen Çalışanlar
                            </h3>
                            <a href="{{ route('hr.employees.index') }}" class="text-primary hover:text-primary/80 text-sm font-bold flex items-center gap-1">
                                Tümünü Gör
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-100 dark:bg-white/5 text-xs uppercase text-gray-700 dark:text-slate-400 font-bold tracking-wider">
                                    <tr>
                                        <th class="px-8 py-6">Çalışan</th>
                                        <th class="px-8 py-6">Departman</th>
                                        <th class="px-8 py-6">Pozisyon</th>
                                        <th class="px-8 py-6">İşe Giriş</th>
                                        <th class="px-8 py-6">Durum</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                                    @forelse($recentEmployees as $employee)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                            <td class="px-8 py-6">
                                                <div class="flex flex-col">
                                                    <span class="text-gray-900 dark:text-white font-bold">{{ $employee->full_name }}</span>
                                                    <span class="text-xs text-gray-500 dark:text-slate-500 font-bold">{{ $employee->email }}</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-6 text-gray-900 dark:text-white font-bold">
                                                {{ $employee->department->name ?? '-' }}
                                            </td>
                                            <td class="px-8 py-6 text-gray-700 dark:text-slate-300">
                                                {{ $employee->position ?? '-' }}
                                            </td>
                                            <td class="px-8 py-6 text-gray-700 dark:text-slate-300">
                                                {{ $employee->hire_date ? $employee->hire_date->format('d.m.Y') : '-' }}
                                            </td>
                                            <td class="px-8 py-6">
                                                @if($employee->status === 'active')
                                                    <span class="px-3 py-1 rounded-lg bg-green-500/10 text-green-500 text-[10px] font-black uppercase tracking-widest border border-green-500/20">Aktif</span>
                                                @else
                                                    <span class="px-3 py-1 rounded-lg bg-slate-500/10 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-500/20">Pasif</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-8 py-16 text-center">
                                                <div class="w-20 h-20 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <span class="material-symbols-outlined text-4xl text-slate-600">person_off</span>
                                                </div>
                                                <p class="text-gray-500 dark:text-slate-500 font-medium text-lg">Henüz çalışan kaydı yok.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>

                {{-- Right Column --}}
                <div class="space-y-8">
                    {{-- Leave Statistics --}}
                    <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 rounded-[2.5rem] shadow-2xl">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-500">calendar_today</span>
                            İzin Durumu
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 rounded-xl bg-orange-500/5 border border-orange-500/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-orange-500 text-[20px]">pending</span>
                                    </div>
                                    <span class="text-gray-700 dark:text-slate-300 font-bold">Bekleyen</span>
                                </div>
                                <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $leaveStats['pending'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-4 rounded-xl bg-green-500/5 border border-green-500/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-green-500 text-[20px]">check_circle</span>
                                    </div>
                                    <span class="text-gray-700 dark:text-slate-300 font-bold">Onaylanan</span>
                                </div>
                                <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $leaveStats['approved'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-4 rounded-xl bg-red-500/5 border border-red-500/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-red-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-red-500 text-[20px]">cancel</span>
                                    </div>
                                    <span class="text-gray-700 dark:text-slate-300 font-bold">Reddedilen</span>
                                </div>
                                <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $leaveStats['rejected'] }}</span>
                            </div>
                        </div>
                    </x-card>

                    {{-- Department Distribution --}}
                    <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 rounded-[2.5rem] shadow-2xl">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-500">corporate_fare</span>
                            Departman Dağılımı
                        </h3>
                        <div class="space-y-3">
                            @forelse($departmentStats as $dept)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5 hover:border-purple-500/30 transition-colors">
                                    <span class="text-gray-900 dark:text-white font-bold text-sm">{{ $dept->name }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500 dark:text-slate-500 font-bold">{{ $dept->employees_count }} kişi</span>
                                        <div class="w-12 h-2 bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-purple-500 to-blue-500" style="width: {{ min(($dept->employees_count / $totalEmployees) * 100, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-slate-500 text-sm text-center py-4">Departman bulunamadı</p>
                            @endforelse
                        </div>
                    </x-card>

                    {{-- Quick Actions --}}
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-gradient-to-br from-blue-500/5 to-purple-500/5 dark:from-blue-500/10 dark:to-purple-500/10 rounded-[2.5rem] shadow-2xl border-2">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-500">bolt</span>
                            Hızlı İşlemler
                        </h3>
                        <div class="space-y-2">
                            <a href="{{ route('hr.employees.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-white/10 hover:bg-blue-50 dark:hover:bg-white/20 border border-gray-200 dark:border-white/10 hover:border-blue-500/30 transition-all group">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-blue-500 text-[20px]">person_add</span>
                                </div>
                                <span class="text-gray-900 dark:text-white font-bold text-sm">Yeni Çalışan Ekle</span>
                            </a>
                            <a href="{{ route('hr.departments.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-white/10 hover:bg-purple-50 dark:hover:bg-white/20 border border-gray-200 dark:border-white/10 hover:border-purple-500/30 transition-all group">
                                <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-purple-500 text-[20px]">corporate_fare</span>
                                </div>
                                <span class="text-gray-900 dark:text-white font-bold text-sm">Departmanları Yönet</span>
                            </a>
                            <a href="{{ route('hr.leaves.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-white dark:bg-white/10 hover:bg-orange-50 dark:hover:bg-white/20 border border-gray-200 dark:border-white/10 hover:border-orange-500/30 transition-all group">
                                <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-orange-500 text-[20px]">calendar_month</span>
                                </div>
                                <span class="text-gray-900 dark:text-white font-bold text-sm">İzin Talepleri</span>
                            </a>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
