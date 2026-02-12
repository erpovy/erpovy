<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-cyan-500/5 to-sky-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        İş Emirleri
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">build</span>
                        Servis ve bakım kayıtlarının yönetimi
                    </p>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('servicemanagement.job-cards.create') }}" class="group flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-500 text-white font-black text-xs uppercase tracking-widest transition-all hover:scale-[1.05] active:scale-[0.95] shadow-lg shadow-blue-500/20">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        YENİ İŞ EMRİ
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <!-- Search and Filter -->
            <x-card class="mb-8 p-4 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('servicemanagement.job-cards.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="İş Emri No, Plaka veya Müşteri ara..." 
                            class="w-full pl-11 pr-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">
                    </div>
                    
                    <div class="w-full md:w-48">
                        <select name="status" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">
                            <option value="">Tüm Durumlar</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Bekliyor</option>
                            <option value="diagnosing" {{ request('status') == 'diagnosing' ? 'selected' : '' }}>Teşhis Ediliyor</option>
                            <option value="waiting_parts" {{ request('status') == 'waiting_parts' ? 'selected' : '' }}>Parça Bekleniyor</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>İşlemde</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                            <option value="invoiced" {{ request('status') == 'invoiced' ? 'selected' : '' }}>Faturalandı</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal</option>
                        </select>
                    </div>

                    <button type="submit" class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-xs uppercase tracking-widest rounded-xl transition-all hover:bg-slate-800 dark:hover:bg-slate-100">
                        FİLTRELE
                    </button>
                    
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('servicemanagement.job-cards.index') }}" class="px-6 py-3 bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-black text-xs uppercase tracking-widest rounded-xl transition-all hover:bg-gray-200 dark:hover:bg-white/10 flex items-center justify-center">
                            SIFIRLA
                        </a>
                    @endif
                </form>
            </x-card>

            <!-- Job Card Table -->
            <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-200 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4">İş Emri</th>
                                <th class="px-6 py-4">Araç / Müşteri</th>
                                <th class="px-6 py-4">Tarih</th>
                                <th class="px-6 py-4">Öncelik</th>
                                <th class="px-6 py-4">Toplam Tutar</th>
                                <th class="px-6 py-4">Durum</th>
                                <th class="px-6 py-4 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($jobCards as $job)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                                                <span class="material-symbols-outlined text-[24px]">assignment</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $job->job_number }}</span>
                                                <span class="text-[10px] font-bold text-slate-500 uppercase">{{ Str::limit($job->customer_complaint, 30) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $job->vehicle->plate_number }}
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-500 uppercase">
                                                {{ $job->customer ? $job->customer->name : 'Dahili Araç' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-700 dark:text-slate-300">
                                                {{ $job->entry_date->format('d.m.Y H:i') }}
                                            </span>
                                            @if($job->expected_completion_date)
                                                <span class="text-[10px] font-bold text-slate-500 uppercase">
                                                    Hedef: {{ $job->expected_completion_date->format('d.m.Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($job->priority == 'urgent')
                                            <span class="px-2 py-1 text-[10px] font-black uppercase tracking-tight rounded bg-rose-500 text-white">ACİL</span>
                                        @elseif($job->priority == 'high')
                                            <span class="px-2 py-1 text-[10px] font-black uppercase tracking-tight rounded bg-orange-500 text-white">YÜKSEK</span>
                                        @elseif($job->priority == 'normal')
                                            <span class="px-2 py-1 text-[10px] font-black uppercase tracking-tight rounded bg-blue-500/10 text-blue-500">NORMAL</span>
                                        @else
                                            <span class="px-2 py-1 text-[10px] font-black uppercase tracking-tight rounded bg-slate-500/10 text-slate-500">DÜŞÜK</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($job->total_amount > 0)
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-emerald-600 dark:text-emerald-400 font-mono">
                                                    {{ number_format($job->total_amount, 2, ',', '.') }} TL
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                                'diagnosing' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                                'waiting_parts' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
                                                'in_progress' => 'bg-blue-500/10 text-blue-500 border-blue-500/20 animate-pulse',
                                                'completed' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                                'invoiced' => 'bg-sky-500/10 text-sky-500 border-sky-500/20',
                                                'cancelled' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                            ];
                                            $statusLabels = [
                                                'pending' => 'BEKLİYOR',
                                                'diagnosing' => 'TEŞHİS',
                                                'waiting_parts' => 'PARÇA BEKLİYOR',
                                                'in_progress' => 'İŞLEMDE',
                                                'completed' => 'TAMAMLANDI',
                                                'invoiced' => 'FATURALANDI',
                                                'cancelled' => 'İPTAL',
                                            ];
                                            $color = $statusColors[$job->status] ?? 'bg-slate-500/10 text-slate-500';
                                            $label = $statusLabels[$job->status] ?? $job->status;
                                        @endphp
                                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-tight rounded-full border {{ $color }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('servicemanagement.job-cards.edit', $job->id) }}" class="p-2 rounded-lg bg-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-white transition-all">
                                                <span class="material-symbols-outlined text-[18px]">edit_square</span>
                                            </a>
                                            <form action="{{ route('servicemanagement.job-cards.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Bu iş emrini silmek istediğinize emin misiniz?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 font-bold uppercase tracking-widest opacity-30 text-xs">
                                        <span class="material-symbols-outlined text-[48px] mb-4 block">assignment_late</span>
                                        Henüz iş emri bulunmuyor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($jobCards->hasPages())
                    <div class="p-6 border-t border-gray-200 dark:border-white/5">
                        {{ $jobCards->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
