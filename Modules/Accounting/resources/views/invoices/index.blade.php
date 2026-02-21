<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Fatura Yönetimi
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">description</span>
                        Satış ve Alış Faturaları Takibi
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('accounting.invoices.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-gray-900 dark:text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add_circle</span>
                            Yeni Fatura Kes
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Row 1: Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['label' => 'Toplam Fatura', 'value' => $stats['total_count'], 'icon' => 'list_alt', 'color' => 'blue', 'type' => 'count'],
                    ['label' => 'Toplam Tutar', 'value' => $stats['total_amount'], 'icon' => 'payments', 'color' => 'green', 'type' => 'currency'],
                    ['label' => 'Bekleyen Ödemeler', 'value' => $stats['pending_count'], 'icon' => 'pending_actions', 'color' => 'orange', 'type' => 'count'],
                    ['label' => 'Bu Ayki Hacim', 'value' => $stats['monthly_amount'], 'icon' => 'trending_up', 'color' => 'purple', 'type' => 'currency']
                ] as $stat)
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $stat['color'] }}-500/20 to-{{ $stat['color'] }}-500/5 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl hover:border-{{ $stat['color'] }}-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-400">
                                <span class="material-symbols-outlined text-[24px]">{{ $stat['icon'] }}</span>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white mb-1">
                            @if($stat['type'] == 'currency')
                                ₺{{ number_format($stat['value'], 0, ',', '.') }}
                            @else
                                {{ number_format($stat['value']) }}
                            @endif
                        </div>
                        <div class="text-xs text-gray-600 dark:text-slate-500 font-bold uppercase tracking-wider">{{ $stat['label'] }}</div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- Filters & Search -->
            <x-card class="p-4 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('accounting.invoices.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Fatura no veya müşteri adı ile ara..." 
                               style="padding-left: 20px !important;"
                               class="w-full pr-4 py-3 bg-white/80 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:border-primary/50 focus:ring-0 transition-all">
                    </div>
                    <div class="w-full md:w-64 relative" x-data="{ 
                        open: false,
                        status: '{{ request('status', '') }}',
                        statusLabel: '{{ request('status') == 'paid' ? 'Ödendi' : (request('status') == 'sent' ? 'Gönderildi' : (request('status') == 'overdue' ? 'Gecikmiş' : 'Tüm Durumlar')) }}',
                        select(val, label) {
                            this.status = val;
                            this.statusLabel = label;
                            this.open = false;
                            $nextTick(() => { $el.closest('form').submit(); });
                        }
                    }">
                        <input type="hidden" name="status" :value="status">
                        <button type="button" @click="open = !open" 
                                style="padding-left: 20px !important;"
                                class="w-full pr-4 py-3 bg-white/80 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white text-left focus:border-primary/50 focus:ring-0 transition-all flex items-center justify-between">
                            <span x-text="statusLabel" class="text-sm font-medium"></span>
                            <span class="material-symbols-outlined text-gray-500 dark:text-slate-500 transition-transform" :class="{'rotate-180': open}">expand_more</span>
                        </button>
                        
                        <!-- Custom Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             @click.away="open = false"
                             class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-slate-900/50 border border-gray-200 dark:border-white/10 rounded-xl shadow-2xl overflow-hidden z-50 backdrop-blur-xl">
                            <div class="py-1">
                                <button type="button" @click="select('', 'Tüm Durumlar')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors">Tüm Durumlar</button>
                                <button type="button" @click="select('sent', 'Gönderildi')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-t border-gray-200 dark:border-white/5">Gönderildi</button>
                                <button type="button" @click="select('paid', 'Ödendi')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-t border-gray-200 dark:border-white/5">Ödendi</button>
                                <button type="button" @click="select('overdue', 'Gecikmiş')" style="padding-left: 20px !important;" class="w-full pr-4 py-2.5 text-left text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-t border-gray-200 dark:border-white/5">Gecikmiş</button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-card>

            <!-- Invoices Table -->
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center w-36">Fatura No</th>
                                @if(auth()->user()->hasModuleAccess('ServiceManagement'))
                                    <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">Plaka</th>
                                @endif
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Müşteri / Kurum</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">Tarih</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">KDV Hariç</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">Toplam</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">Durum</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right w-24">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($invoices as $invoice)
                            <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors group">
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 bg-primary/10 rounded-lg text-xs font-mono font-black text-primary group-hover:bg-primary/20 transition-colors">
                                        {{ $invoice->invoice_number }}
                                    </span>
                                </td>
                                @if(auth()->user()->hasModuleAccess('ServiceManagement'))
                                    <td class="p-4 text-center">
                                        @if($invoice->plate_number)
                                            <div class="inline-flex items-stretch h-7 bg-white dark:bg-slate-900 border-2 border-gray-900 dark:border-white rounded-md overflow-hidden shadow-sm">
                                                <div class="w-3 bg-blue-700 flex flex-col items-center justify-end pb-0.5 shrink-0">
                                                    <span class="text-[5px] font-black text-white leading-none">TR</span>
                                                </div>
                                                <div class="px-2 py-0.5 text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider flex items-center">
                                                    {{ $invoice->plate_number }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">N/A</span>
                                        @endif
                                    </td>
                                @endif
                                <td class="p-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">
                                        {{ $invoice->contact->name ?? 'Müşteri Bilgisi Yok' }}
                                    </div>
                                    <div class="text-[10px] text-gray-600 dark:text-slate-500 font-bold uppercase">{{ $invoice->contact->tax_number ?? 'Vergi No Yok' }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ optional($invoice->issue_date)->format('d.m.Y') }}
                                    </div>
                                    <div class="text-[10px] text-gray-600 dark:text-slate-600 font-bold uppercase">vade: {{ optional($invoice->due_date)->format('d.m.Y') ?? 'N/A' }}</div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-medium text-gray-600 dark:text-slate-400">
                                        ₺{{ number_format($invoice->total_amount - $invoice->tax_amount, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-base font-black text-gray-900 dark:text-white">
                                        ₺{{ number_format($invoice->total_amount, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    @php
                                        $statusConfig = [
                                            'paid' => ['label' => 'ÖDENDİ', 'class' => 'bg-green-500/10 text-green-400 border-green-500/20'],
                                            'sent' => ['label' => 'GÖNDERİLDİ', 'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'],
                                            'overdue' => ['label' => 'GECİKMİŞ', 'class' => 'bg-red-500/10 text-red-400 border-red-500/20'],
                                            'draft' => ['label' => 'TASLAK', 'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/20'],
                                        ];
                                        $currStatus = $statusConfig[$invoice->status] ?? ['label' => strtoupper($invoice->status), 'class' => 'bg-slate-500/10 text-slate-400 border-slate-500/20'];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $currStatus['class'] }}">
                                        {{ $currStatus['label'] }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($invoice->invoice_scenario == 'EARSIV')
                                        <div x-data="{ loading: false }" class="inline-flex">
                                            <form id="gib-form-list-{{ $invoice->id }}" action="{{ route('accounting.invoices.send-to-gib', $invoice->id) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                            <button 
                                                @click="
                                                    if(confirm('Fatura GİB Portalı\'na taslak olarak gönderilecek. Onaylıyor musunuz?')) { 
                                                        loading = true; 
                                                        document.getElementById('gib-form-list-{{ $invoice->id }}').submit(); 
                                                    }
                                                " 
                                                :disabled="loading"
                                                class="p-2 rounded-lg bg-orange-500/10 text-orange-400 hover:bg-orange-500/20 transition-all active:scale-90 disabled:opacity-50" 
                                                title="GİB'e Gönder">
                                                <span class="material-symbols-outlined text-[18px]" x-show="!loading">cloud_upload</span>
                                                <span class="material-symbols-outlined text-[18px] animate-spin" x-show="loading" style="display: none;">sync</span>
                                            </button>
                                        </div>
                                        @endif
                                        <a href="{{ route('accounting.invoices.pdf', $invoice) }}" class="p-2 rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 transition-all active:scale-90" title="PDF İndir">
                                            <span class="material-symbols-outlined text-[18px]">download_for_offline</span>
                                        </a>
                                        <button onclick="window.print()" class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition-all active:scale-90" title="Yazdır">
                                            <span class="material-symbols-outlined text-[18px]">print</span>
                                        </button>
                                        <a href="{{ route('accounting.invoices.show', $invoice) }}" class="p-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-white/10 transition-all active:scale-90" title="Detay">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-gray-600 dark:text-slate-500 italic">
                                    Herhangi bir fatura kaydı bulunamadı.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($invoices->hasPages())
                <div class="p-6 border-t border-gray-200 dark:border-white/5 bg-white/[0.01]">
                    {{ $invoices->links() }}
                </div>
                @endif
            </x-card>
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
