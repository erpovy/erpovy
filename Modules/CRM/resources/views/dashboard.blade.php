<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-indigo-500/5 to-purple-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        CRM Özeti
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">groups</span>
                        Müşteri ilişkileri ve satış hunisi analizi
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('crm.leads.create') }}" class="flex items-center gap-2 px-5 py-3 rounded-xl bg-gray-900 dark:bg-primary-600 text-white font-bold text-sm hover:bg-gray-800 dark:hover:bg-primary-500 transition-all shadow-lg shadow-gray-200/50 dark:shadow-primary-500/20">
                        <span class="material-symbols-outlined text-[20px]">person_add</span>
                        Yeni Aday
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Ana Metrikler -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Toplam Adaylar -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-blue-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-indigo-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-indigo-500/10 text-indigo-500">
                                    <span class="material-symbols-outlined text-[24px]">leaderboard</span>
                                </div>
                                <div class="text-indigo-400 text-xs font-bold bg-indigo-900/30 px-2 py-1 rounded-lg border border-indigo-500/30">Adaylar</div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ number_format($totalLeads) }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Toplam Potansiyel Müşteri</div>
                        </div>
                    </x-card>
                </div>

                <!-- Aktif Anlaşmalar -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/20 to-orange-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-amber-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-amber-500/10 text-amber-500">
                                    <span class="material-symbols-outlined text-[24px]">handshake</span>
                                </div>
                                <div class="text-amber-400 text-xs font-bold bg-amber-900/30 px-2 py-1 rounded-lg border border-amber-500/30">Anlaşmalar</div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ number_format($activeDeals) }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Süreçteki Aktif Fırsatlar</div>
                        </div>
                    </x-card>
                </div>

                <!-- Kazanılan Tutar -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-emerald-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-500">
                                    <span class="material-symbols-outlined text-[24px]">payments</span>
                                </div>
                                <div class="text-emerald-400 text-xs font-bold bg-emerald-900/30 px-2 py-1 rounded-lg border border-emerald-500/30">Kazanç</div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">₺{{ number_format($totalWonAmount, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Kazanılan Toplam Ciro</div>
                        </div>
                    </x-card>
                </div>

                <!-- Toplam Bağlantılar -->
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-fuchsia-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-purple-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-purple-500/10 text-purple-500">
                                    <span class="material-symbols-outlined text-[24px]">contact_page</span>
                                </div>
                                <div class="text-purple-400 text-xs font-bold bg-purple-900/30 px-2 py-1 rounded-lg border border-purple-500/30">Rehber</div>
                            </div>
                            <div class="text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ number_format($totalContacts) }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Kayıtlı Kişi ve Firmalar</div>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Grafik Analizleri ve Listeler -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Aday Kazanım Trendi -->
                <div class="lg:col-span-2">
                    <x-card class="h-full p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-indigo-500">trending_up</span>
                                    Haftalık Aday Kazanımı
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-slate-500">Son 7 günde eklenen aday sayıları</p>
                            </div>
                        </div>

                        <div class="h-64 flex items-end justify-between gap-4 px-4">
                            @php
                                $days = collect();
                                for($i = 6; $i >= 0; $i--) {
                                    $days->put(now()->subDays($i)->format('Y-m-d'), [
                                        'total' => 0,
                                        'label' => now()->subDays($i)->translatedFormat('D')
                                    ]);
                                }

                                foreach($weeklyLeads as $lead) {
                                    if($days->has($lead->date)) {
                                        $d = $days->get($lead->date);
                                        $d['total'] = $lead->total;
                                        $days->put($lead->date, $d);
                                    }
                                }

                                $maxLeads = collect($days->values())->max('total') ?: 1;
                            @endphp

                            @foreach($days as $date => $data)
                                <div class="flex-1 group flex flex-col justify-end h-full relative">
                                    <div class="w-full bg-indigo-500/40 rounded-t-xl transition-all duration-500 group-hover:bg-indigo-500 group-hover:shadow-[0_0_15px_rgba(99,102,241,0.3)]" 
                                         style="height: {{ ($data['total'] / $maxLeads) * 100 }}%"></div>
                                    <div class="text-[10px] font-bold text-gray-500 text-center mt-2 group-hover:text-primary transition-colors">{{ $data['label'] }}</div>
                                    
                                    <!-- Tooltip -->
                                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-gray-900 border border-white/10 text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10 whitespace-nowrap">
                                        +{{ number_format($data['total']) }} Aday
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                </div>

                <!-- Satış Hunisi (Pipeline) -->
                <div class="lg:col-span-1">
                    <x-card class="h-full p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-500">filter_list</span>
                                Satış Hunisi Dağılımı
                            </h3>
                        </div>

                        <div class="space-y-6">
                            @php
                                $totalDealsCount = $dealStages->sum('total') ?: 1;
                            @endphp
                            @forelse($dealStages as $stage)
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="font-bold text-gray-700 dark:text-slate-400 uppercase tracking-tighter">{{ $stage->stage }}</span>
                                        <span class="text-gray-900 dark:text-white font-black">{{ number_format($stage->total) }}</span>
                                    </div>
                                    <div class="h-2 w-full bg-gray-200 dark:bg-white/5 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full" 
                                             style="width: {{ ($stage->total / $totalDealsCount) * 100 }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 opacity-50">
                                    <span class="material-symbols-outlined text-4xl block mb-2">shuffle</span>
                                    Veri bulunmuyor.
                                </div>
                            @endforelse
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Son İşlemler Tablosu -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Son Anlaşmalar -->
                <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-500">monetization_on</span>
                            Son Anlaşmalar
                        </h3>
                        <a href="{{ route('crm.deals.index') }}" class="text-xs font-bold text-indigo-500 hover:text-indigo-400 transition-colors uppercase tracking-widest">Tümünü Gör</a>
                    </div>

                    <div class="space-y-4">
                        @foreach($recentDeals as $deal)
                            <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/5 border border-transparent hover:border-white/10 transition-all group">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined">payments</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-black text-gray-900 dark:text-white truncate">{{ $deal->title }}</h4>
                                    <p class="text-[10px] text-gray-500 font-medium uppercase">{{ $deal->contact?->full_name ?? $deal->lead?->full_name ?? 'Bilinmeyen' }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">₺{{ number_format($deal->amount, 0, ',', '.') }}</div>
                                    <div class="text-[10px] px-2 py-0.5 rounded-full inline-block {{ $deal->stage == 'Closed Won' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-indigo-500/20 text-indigo-400' }} font-bold uppercase tracking-tighter">
                                        {{ $deal->stage }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card>

                <!-- Son Adaylar -->
                <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-indigo-500">group_add</span>
                            Yeni Adaylar
                        </h3>
                        <a href="{{ route('crm.leads.index') }}" class="text-xs font-bold text-indigo-500 hover:text-indigo-400 transition-colors uppercase tracking-widest">Tümünü Gör</a>
                    </div>

                    <div class="space-y-4">
                        @foreach($recentLeads as $lead)
                            <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/5 border border-transparent hover:border-white/10 transition-all group">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500/20 to-blue-500/10 text-indigo-500 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-black text-gray-900 dark:text-white truncate">{{ $lead->full_name }}</h4>
                                    <p class="text-[10px] text-gray-500 font-medium uppercase">{{ $lead->company_name ?: 'Bireysel' }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-black text-gray-900 dark:text-white">{{ $lead->score ?: 0 }} Puan</div>
                                    <div class="text-[10px] px-2 py-0.5 rounded-full inline-block bg-white/10 text-gray-400 font-bold uppercase tracking-tighter">
                                        {{ $lead->status }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-card>
            </div>

            <!-- Hızlı Aksiyonlar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach([
                    ['route' => 'crm.contacts.index', 'icon' => 'contact_page', 'label' => 'Rehber', 'color' => 'blue'],
                    ['route' => 'crm.leads.index', 'icon' => 'leaderboard', 'label' => 'Aday Listesi', 'color' => 'indigo'],
                    ['route' => 'crm.deals.index', 'icon' => 'handshake', 'label' => 'Anlaşmalar', 'color' => 'emerald'],
                    ['route' => 'crm.contracts.index', 'icon' => 'description', 'label' => 'Sözleşmeler', 'color' => 'purple']
                ] as $action)
                    <a href="{{ route($action['route']) }}" class="group relative overflow-hidden rounded-2xl">
                        <div class="absolute inset-0 bg-white/5 border border-gray-200 dark:border-white/10 transition-colors duration-300 group-hover:border-{{ $action['color'] }}-500/30 group-hover:bg-{{ $action['color'] }}-500/5"></div>
                        
                        <div class="relative p-6 flex items-center justify-center gap-4">
                            <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-{{ $action['color'] }}-500/20 to-{{ $action['color'] }}-500/10 text-{{ $action['color'] }}-400 group-hover:scale-110 transition-transform duration-300">
                                <span class="material-symbols-outlined text-[24px]">{{ $action['icon'] }}</span>
                            </div>
                            <div class="font-black text-gray-900 dark:text-white text-sm uppercase tracking-wide group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                {{ $action['label'] }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </div>

    <!-- Live Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const target = document.getElementById('live-clock');
            if (target) target.textContent = `${hours}:${minutes}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</x-app-layout>
