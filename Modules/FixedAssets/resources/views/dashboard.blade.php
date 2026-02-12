<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Demirbaş Yönetimi Özeti
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">analytics</span>
                        Varlık durumu, zimmet hareketleri ve bakım takvimi
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('fixedassets.create') }}" class="group flex items-center gap-2 px-6 py-3 rounded-xl bg-primary text-white font-black text-xs uppercase tracking-widest transition-all hover:scale-[1.05] active:scale-[0.95] shadow-neon">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        YENİ DEMİRBAŞ
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Toplam Demirbaş -->
                <x-card class="p-6 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white/10 dark:bg-[#1a2332]/60 backdrop-blur-xl transition-all hover:bg-white/20 dark:hover:bg-[#1a2332]/80">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-30 transition-opacity">
                        <span class="material-symbols-outlined text-[64px] text-primary">inventory_2</span>
                    </div>
                    <div class="relative z-10">
                        <div class="text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary shadow-[0_0_8px_rgba(var(--color-primary),0.5)]"></span>
                            Toplam Varlık
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-zinc-50 mb-1 leading-none">{{ $stats['total_assets'] }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-bold">Toplam Kayıtlı Demirbaş</div>
                    </div>
                </x-card>

                <!-- Toplam Değer -->
                <x-card class="p-6 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white/10 dark:bg-[#1a2332]/60 backdrop-blur-xl transition-all hover:bg-white/20 dark:hover:bg-[#1a2332]/80">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-30 transition-opacity">
                        <span class="material-symbols-outlined text-[64px] text-emerald-500">payments</span>
                    </div>
                    <div class="relative z-10">
                        <div class="text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            Toplam Envanter Değeri
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-zinc-50 mb-1 leading-none">₺{{ number_format($stats['total_value'], 2, ',', '.') }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-bold">Satın Alma Maliyeti</div>
                    </div>
                </x-card>

                <!-- Aktif Kullanımda -->
                <x-card class="p-6 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white/10 dark:bg-[#1a2332]/60 backdrop-blur-xl transition-all hover:bg-white/20 dark:hover:bg-[#1a2332]/80">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-30 transition-opacity">
                        <span class="material-symbols-outlined text-[64px] text-blue-500">person_check</span>
                    </div>
                    <div class="relative z-10">
                        <div class="text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"></span>
                            Zimmetli Varlıklar
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-zinc-50 mb-1 leading-none">{{ $assignmentStats['assigned'] }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-bold">Personel Üzerindeki Toplam</div>
                    </div>
                </x-card>

                <!-- Bakım Bekleyen -->
                <x-card class="p-6 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white/10 dark:bg-[#1a2332]/60 backdrop-blur-xl transition-all hover:bg-white/20 dark:hover:bg-[#1a2332]/80">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-30 transition-opacity">
                        <span class="material-symbols-outlined text-[64px] text-amber-500">build</span>
                    </div>
                    <div class="relative z-10">
                        <div class="text-slate-500 dark:text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] mb-2 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                            İşlem Bekleyen
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-zinc-50 mb-1 leading-none">{{ $stats['maintenance_assets'] }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-bold">Bakım veya Onarımda</div>
                    </div>
                </x-card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Middle Left: Distribution -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Category Distribution -->
                    <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="p-6 border-b border-gray-200 dark:border-white/5 flex items-center justify-between">
                            <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em]">Kategori Bazlı Dağılım</h3>
                            <span class="material-symbols-outlined text-primary text-[20px]">category</span>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($categoryData as $cat)
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center px-1">
                                            <span class="text-sm font-bold text-gray-700 dark:text-slate-300">{{ $cat['name'] }}</span>
                                            <span class="text-xs font-black text-primary">{{ $cat['count'] }} Adet</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                                            @php 
                                                $percent = $stats['total_assets'] > 0 ? ($cat['count'] / $stats['total_assets']) * 100 : 0;
                                            @endphp
                                            <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </x-card>

                    <!-- Availability Summary -->
                    <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl p-6">
                        <div class="flex items-center gap-6">
                             <div class="relative w-24 h-24">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="48" cy="48" r="40" fill="transparent" stroke="currentColor" stroke-width="8" class="text-gray-200 dark:text-white/5" />
                                    @php 
                                        $totalActive = $assignmentStats['assigned'] + $assignmentStats['available'];
                                        $assignedPercent = $totalActive > 0 ? ($assignmentStats['assigned'] / $totalActive) * 100 : 0;
                                        $circumference = 2 * pi() * 40;
                                        $offset = $circumference - ($assignedPercent / 100) * $circumference;
                                    @endphp
                                    <circle cx="48" cy="48" r="40" fill="transparent" stroke="currentColor" stroke-width="8" 
                                            class="text-primary transition-all duration-1000"
                                            stroke-dasharray="{{ $circumference }}"
                                            stroke-dashoffset="{{ $offset }}" />
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center text-sm font-black text-gray-900 dark:text-white">
                                    %{{ round($assignedPercent) }}
                                </div>
                             </div>
                             <div class="flex-1 space-y-2">
                                <div class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Envanter Kullanım Oranı</div>
                                <div class="text-gray-900 dark:text-white font-bold leading-tight">Varlıkların %{{ round($assignedPercent) }} kadarı personele zimmetlenmiş durumda.</div>
                             </div>
                        </div>
                    </x-card>
                </div>

                <!-- Middle Right: Lists -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Recent Activity -->
                    <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-white/5 flex items-center justify-between">
                            <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em]">Son Zimmet Hareketleri</h3>
                            <a href="{{ route('fixedassets.index') }}" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline">Tümünü Gör</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-200 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Demirbaş</th>
                                        <th class="px-6 py-4">Personel</th>
                                        <th class="px-6 py-4">Tarih</th>
                                        <th class="px-6 py-4">Durum</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                                    @foreach($recentAssignments as $assignment)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-black text-gray-900 dark:text-white">{{ $assignment->asset->name }}</span>
                                                    <span class="text-[10px] font-bold text-slate-500">{{ $assignment->asset->code }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-bold text-gray-700 dark:text-slate-300">{{ $assignment->employee->name }}</td>
                                            <td class="px-6 py-4 text-xs font-medium text-slate-500">{{ $assignment->assigned_at->format('d.m.Y') }}</td>
                                            <td class="px-6 py-4">
                                                @if($assignment->returned_at)
                                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-slate-500/10 text-slate-500 border border-slate-500/20">İADE EDİLDİ</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-tight rounded bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">AKTİF ZİMMET</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-card>

                    <!-- Maintenance Schedule -->
                    <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-white/5 flex items-center justify-between">
                            <h3 class="text-gray-900 dark:text-white font-black text-xs uppercase tracking-[0.2em]">Yaklaşan Bakımlar</h3>
                            <span class="material-symbols-outlined text-amber-500 text-[20px]">event_repeat</span>
                        </div>
                        @if($upcomingMaintenances->isEmpty())
                            <div class="p-12 text-center">
                                <span class="material-symbols-outlined text-slate-500 text-[48px] mb-4 opacity-20">event_available</span>
                                <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">Yakında planlanmış bakım bulunmuyor.</p>
                            </div>
                        @else
                            <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($upcomingMaintenances as $maintenance)
                                    <div class="p-4 rounded-xl border border-gray-200 dark:border-white/5 bg-white/5 flex items-center gap-4 group hover:border-amber-500/30 transition-all">
                                        <div class="w-12 h-12 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500">
                                            <span class="material-symbols-outlined text-[24px]">construction</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-gray-900 dark:text-white">{{ $maintenance->asset->name }}</div>
                                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $maintenance->next_maintenance_date->format('d.m.Y') }}</div>
                                        </div>
                                        <div class="ml-auto text-[10px] font-black text-amber-500/50 group-hover:text-amber-500 transition-colors">
                                            KALAN: {{ $maintenance->next_maintenance_date->diffForHumans() }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
