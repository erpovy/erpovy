<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Teklif Yönetimi
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">description</span>
                        Satış Teklifleri ve Proformalar
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('sales.quotes.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add_circle</span>
                            Yeni Teklif Oluştur
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
                    ['label' => 'Toplam Teklif', 'value' => $stats['total_count'], 'icon' => 'format_list_bulleted', 'color' => 'blue', 'hex' => '#60a5fa', 'type' => 'count'],
                    ['label' => 'Toplam Tutar', 'value' => $stats['total_amount'], 'icon' => 'payments', 'color' => 'emerald', 'hex' => '#34d399', 'type' => 'currency'],
                    ['label' => 'Bekleyenler', 'value' => $stats['pending_count'], 'icon' => 'hourglass_empty', 'color' => 'amber', 'hex' => '#fbbf24', 'type' => 'count'],
                    ['label' => 'Kabul Edilen', 'value' => $stats['accepted_count'], 'icon' => 'check_circle', 'color' => 'indigo', 'hex' => '#a5b4fc', 'type' => 'count']
                ] as $stat)
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $stat['color'] }}-500/20 to-{{ $stat['color'] }}-500/5 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-white/20 bg-white/[0.08] backdrop-blur-2xl hover:border-{{ $stat['color'] }}-500/50 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 rounded-2xl bg-{{ $stat['color'] }}-500/10 border border-{{ $stat['color'] }}-500/20">
                                <span class="material-symbols-outlined text-[32px]" style="color: {{ $stat['hex'] }} !important;">{{ $stat['icon'] }}</span>
                            </div>
                        </div>
                        <div class="text-3xl font-black mb-1 tracking-tighter" style="color: #ffffff !important;">
                            @if($stat['type'] == 'currency')
                                ₺{{ number_format($stat['value'], 0, ',', '.') }}
                            @else
                                {{ number_format($stat['value']) }}
                            @endif
                        </div>
                        <div class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: #94a3b8 !important;">{{ $stat['label'] }}</div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- Filters & Search -->
            <x-card class="p-4 border-white/10 bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('sales.quotes.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Teklif no veya müşteri adı ile ara..." 
                               style="padding-left: 20px !important;"
                               class="w-full pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:border-primary/50 focus:ring-0 transition-all">
                    </div>
                    <button type="submit" class="px-8 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-white font-black text-sm uppercase tracking-widest transition-all">
                        Filtrele
                    </button>
                </form>
            </x-card>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <x-card class="p-5 border-white/20 bg-white/[0.08] backdrop-blur-xl group hover:border-blue-500/50 transition-all">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-2" style="color: #94a3b8 !important;">Toplam Teklif</p>
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-black italic tracking-tighter" style="color: #ffffff !important;">{{ $quotes->count() }}</span>
                        <span class="material-symbols-outlined text-4xl group-hover:scale-110 transition-all" style="color: #60a5fa !important;">description</span>
                    </div>
                </x-card>
                <x-card class="p-5 border-white/20 bg-white/[0.08] backdrop-blur-xl group hover:border-amber-500/50 transition-all">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-2" style="color: #94a3b8 !important;">Bekleyenler</p>
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-black italic tracking-tighter" style="color: #fbbf24 !important;">{{ $quotes->where('status', 'sent')->count() }}</span>
                        <span class="material-symbols-outlined text-4xl group-hover:scale-110 transition-all" style="color: #fbbf24 !important;">pending_actions</span>
                    </div>
                </x-card>
                <x-card class="p-5 border-white/20 bg-white/[0.08] backdrop-blur-xl group hover:border-emerald-500/50 transition-all">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-2" style="color: #94a3b8 !important;">Onaylananlar</p>
                    <div class="flex items-center justify-between">
                        <span class="text-3xl font-black italic tracking-tighter" style="color: #34d399 !important;">{{ $quotes->where('status', 'accepted')->count() }}</span>
                        <span class="material-symbols-outlined text-4xl group-hover:scale-110 transition-all" style="color: #34d399 !important;">task_alt</span>
                    </div>
                </x-card>
                <x-card class="p-5 border-white/20 bg-white/[0.08] backdrop-blur-xl group hover:border-indigo-500/50 transition-all">
                    <p class="text-[10px] font-black uppercase tracking-widest mb-2" style="color: #94a3b8 !important;">Toplam Tutar</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-black italic tracking-tighter" style="color: #a5b4fc !important;">₺{{ number_format($quotes->sum('total_amount'), 2, ',', '.') }}</span>
                        <span class="material-symbols-outlined text-4xl group-hover:scale-110 transition-all" style="color: #a5b4fc !important;">payments</span>
                    </div>
                </x-card>
            </div>

            <!-- Quotes Table -->
            <x-card class="p-0 border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-white/5 bg-white/[0.02] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-primary/10 text-primary">
                            <span class="material-symbols-outlined">receipt_long</span>
                        </div>
                        <div>
                            <h3 class="font-black text-white uppercase tracking-widest text-sm">Teklif Listesi</h3>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight mt-0.5">Sistemdeki tüm teklif kayıtları</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02] border-b border-white/5">
                                <th class="p-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center w-36">Teklif No</th>
                                <th class="p-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Müşteri / Kurum</th>
                                <th class="p-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Tarih</th>
                                <th class="p-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Gç. Tarihi</th>
                                <th class="p-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Toplam</th>
                                <th class="p-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Durum</th>
                                <th class="p-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right w-24">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($quotes as $quote)
                            <tr class="hover:bg-white/[0.01] transition-colors group">
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 bg-primary/10 rounded-lg text-xs font-mono font-black text-primary group-hover:bg-primary/20 transition-colors">
                                        {{ $quote->quote_number }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-bold text-white group-hover:text-primary transition-colors">
                                        {{ $quote->contact->name ?? 'Müşteri Bilgisi Yok' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $quote->contact->tax_number ?? 'Vergi No Yok' }}</div>
                                </td>
                                <td class="p-4 text-center text-sm font-bold text-slate-300">
                                    {{ $quote->date->format('d.m.Y') }}
                                </td>
                                <td class="p-4 text-center text-sm font-medium text-slate-400">
                                    {{ $quote->expiry_date ? $quote->expiry_date->format('d.m.Y') : '---' }}
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-base font-black text-white">
                                        ₺{{ number_format($quote->total_amount, 2, ',', '.') }}
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    @php
                                        $statusConfig = [
                                            'accepted' => ['label' => 'KABUL EDİLDİ', 'class' => 'bg-green-500/10 text-green-400 border-green-500/20'],
                                            'sent' => ['label' => 'GÖNDERİLDİ', 'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/20'],
                                            'rejected' => ['label' => 'REDDEDİLDİ', 'class' => 'bg-red-500/10 text-red-400 border-red-500/20'],
                                            'expired' => ['label' => 'SÜRESİ DOLDU', 'class' => 'bg-white/5 text-slate-400 border-white/10'],
                                            'draft' => ['label' => 'TASLAK', 'class' => 'bg-white/5 text-slate-400 border-white/10'],
                                        ];
                                        $currStatus = $statusConfig[$quote->status] ?? ['label' => strtoupper($quote->status), 'class' => 'bg-white/5 text-slate-400 border-white/10'];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $currStatus['class'] }}">
                                        {{ $currStatus['label'] }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('sales.quotes.show', $quote) }}" class="p-2 rounded-lg bg-white/5 text-slate-400 hover:bg-primary/20 hover:text-primary transition-all shadow-xl border border-white/5" title="Görüntüle">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                        <a href="{{ route('sales.quotes.edit', $quote) }}" class="p-2 rounded-lg bg-white/5 text-slate-400 hover:bg-blue-500/20 hover:text-blue-400 transition-all shadow-xl border border-white/5" title="Düzenle">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('sales.quotes.destroy', $quote) }}" method="POST" class="inline" onsubmit="return confirm('Bu teklifi silmek istediğinize emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-white/5 text-slate-400 hover:bg-red-500/20 hover:text-red-400 transition-all shadow-xl border border-white/5" title="Sil">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-slate-500 italic">
                                    Henüz teklif kaydı bulunmamaktadır.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($quotes->hasPages())
                <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                    {{ $quotes->links() }}
                </div>
                @endif
            </x-card>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const target = document.getElementById('live-clock');
            if (target) target.textContent = now.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</x-app-layout>
