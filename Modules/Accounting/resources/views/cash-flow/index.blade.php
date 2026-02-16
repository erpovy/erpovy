<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                    Nakit Akış Öngörüsü
                </h2>
                <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">insights</span>
                    Gelecek {{ $days }} günlük finansal projeksiyon
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('accounting.cash-flow.index', ['days' => 15]) }}" class="px-4 py-2 rounded-lg text-xs font-bold {{ $days == 15 ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white dark:bg-white/5 text-gray-600' }}">15 Gün</a>
                <a href="{{ route('accounting.cash-flow.index', ['days' => 30]) }}" class="px-4 py-2 rounded-lg text-xs font-bold {{ $days == 30 ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white dark:bg-white/5 text-gray-600' }}">30 Gün</a>
                <a href="{{ route('accounting.cash-flow.index', ['days' => 60]) }}" class="px-4 py-2 rounded-lg text-xs font-bold {{ $days == 60 ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white dark:bg-white/5 text-gray-600' }}">60 Gün</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <!-- Summary Cards -->
            <x-card class="p-6 border-emerald-500/20 bg-emerald-500/5">
                <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500/60 mb-1">Mevcut Bakiye</p>
                <h3 class="text-2xl font-black text-emerald-500">{{ number_format($data['total_current_balance'], 2, ',', '.') }} ₺</h3>
            </x-card>

            <x-card class="p-6 border-rose-500/20 bg-rose-500/5">
                <p class="text-[10px] font-black uppercase tracking-widest text-rose-500/60 mb-1">En Düşük Nokta</p>
                <h3 class="text-2xl font-black text-rose-500">{{ number_format($data['min_balance'], 2, ',', '.') }} ₺</h3>
            </x-card>

            <x-card class="p-6 border-blue-500/20 bg-blue-500/5">
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-500/60 mb-1">Riskli Gün Sayısı</p>
                <h3 class="text-2xl font-black text-blue-500">{{ count($data['risk_days']) }} Gün</h3>
            </x-card>

            <x-card class="p-6 border-amber-500/20 bg-amber-500/5">
                <p class="text-[10px] font-black uppercase tracking-widest text-amber-500/60 mb-1">Öngörü Score</p>
                <h3 class="text-2xl font-black text-amber-500">{{ $data['min_balance'] < 0 ? 'Kritik' : 'Sağlıklı' }}</h3>
            </x-card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Chart -->
            <x-card class="lg:col-span-2 p-6 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl">
                <h3 class="font-black text-lg mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">show_chart</span>
                    Bakiye Projeksiyonu
                </h3>
                <div class="h-[400px]">
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </x-card>

            <!-- Upcoming List -->
            <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl">
                <h3 class="font-black text-lg mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-500">pending_actions</span>
                    Önemli Haraketler
                </h3>
                <div class="space-y-4 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
                    @foreach($data['forecast'] as $day)
                        @if($day['inflow'] > 0 || $day['outflow'] > 0)
                            <div class="p-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-gray-500">{{ \Carbon\Carbon::parse($day['date'])->translatedFormat('d M, l') }}</span>
                                    <span class="text-[10px] font-black {{ $day['closing_balance'] < 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                                        Bakiye: {{ number_format($day['closing_balance'], 0, ',', '.') }} ₺
                                    </span>
                                </div>
                                <div class="space-y-1">
                                    @foreach($day['details'] as $detail)
                                        <div class="text-[11px] flex items-center gap-2 text-gray-600 dark:text-slate-400">
                                            <span class="w-1 h-1 rounded-full {{ str_contains($detail, 'Alacak') ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                            {{ $detail }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </x-card>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('cashFlowChart').getContext('2d');
        const data = @json($data['forecast']);
        
        const labels = data.map(d => d.date);
        const balances = data.map(d => d.closing_balance);
        const inflows = data.map(d => d.inflow);
        const outflows = data.map(d => d.outflow);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Beklenen Bakiye',
                        data: balances,
                        borderColor: '#137fec',
                        backgroundColor: 'rgba(19, 127, 236, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            callback: function(val, index) {
                                return index % 5 === 0 ? this.getLabelForValue(val) : '';
                            },
                            color: '#64748b'
                        }
                    },
                    y: {
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#64748b' }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
