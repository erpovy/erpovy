<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Satış İadeleri
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">keyboard_return</span>
                        Müşteri İade ve İptal Kayıtları
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('sales.returns.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                            Yeni İade Kaydı
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['label' => 'Toplam İade', 'value' => $stats['total_returns'], 'icon' => 'assignment_return', 'color' => 'blue', 'type' => 'count'],
                    ['label' => 'Bekleyen İadeler', 'value' => $stats['pending_returns'], 'icon' => 'pending', 'color' => 'orange', 'type' => 'count'],
                    ['label' => 'Onaylananlar', 'value' => $stats['approved_returns'], 'icon' => 'check_circle', 'color' => 'green', 'type' => 'count'],
                    ['label' => 'Toplam İade Tutarı', 'value' => $stats['total_refunded'], 'icon' => 'payments', 'color' => 'purple', 'type' => 'currency']
                ] as $stat)
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-400">
                            <span class="material-symbols-outlined text-[24px]">{{ $stat['icon'] }}</span>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white mb-1">
                        @if($stat['type'] == 'currency')
                            ₺{{ number_format($stat['value'], 2, ',', '.') }}
                        @else
                            {{ number_format($stat['value']) }}
                        @endif
                    </div>
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">{{ $stat['label'] }}</div>
                </x-card>
                @endforeach
            </div>

            <!-- Filters -->
            <x-card class="p-4 border-white/10 bg-white/5 backdrop-blur-2xl relative z-[100] overflow-visible">
                <form action="{{ route('sales.returns.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="İade no, fatura no veya müşteri adı..." 
                               style="padding-left: 20px !important;"
                               class="w-full pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:border-primary/50 focus:ring-0 transition-all">
                    </div>
                    
                    <div class="w-full md:w-64 relative z-50" x-data="{ 
                        open: false,
                        status: '{{ request('status', '') }}',
                        statusLabel: '{{ match(request('status')) {
                            'Pending' => 'Bekliyor',
                            'Approved' => 'Onaylandı',
                            'Rejected' => 'Reddedildi',
                            'Refunded' => 'İadesi Yapıldı',
                            default => 'Tüm Durumlar'
                        } }}',
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
                                class="w-full pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white text-left focus:border-primary/50 focus:ring-0 transition-all flex items-center justify-between">
                            <span x-text="statusLabel" class="text-sm font-medium"></span>
                            <span class="material-symbols-outlined text-slate-500 transition-transform" :class="{'rotate-180': open}">expand_more</span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false"
                             class="absolute top-full left-0 w-full mt-2 bg-slate-900/50 border border-white/10 rounded-xl shadow-2xl overflow-hidden z-[110] backdrop-blur-xl">
                            <div class="py-1">
                                <button type="button" @click="select('', 'Tüm Durumlar')" style="padding-left: 20px !important;" class="w-full pr-4 py-2 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors">Tüm Durumlar</button>
                                <button type="button" @click="select('Pending', 'Bekliyor')" style="padding-left: 20px !important;" class="w-full pr-4 py-2 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors border-t border-white/5">Bekliyor</button>
                                <button type="button" @click="select('Approved', 'Onaylandı')" style="padding-left: 20px !important;" class="w-full pr-4 py-2 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors border-t border-white/5">Onaylandı</button>
                                <button type="button" @click="select('Refunded', 'İadesi Yapıldı')" style="padding-left: 20px !important;" class="w-full pr-4 py-2 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors border-t border-white/5">İadesi Yapıldı</button>
                                <button type="button" @click="select('Rejected', 'Reddedildi')" style="padding-left: 20px !important;" class="w-full pr-4 py-2 text-left text-sm font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors border-t border-white/5">Reddedildi</button>
                            </div>
                        </div>
                    </div>
                </form>
            </x-card>

            <!-- Table -->
            <x-card class="p-0 border-white/10 bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02] border-b border-white/5">
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">İade No</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Müşteri / Fatura</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Tarih</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Tutar</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Durum</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right w-24">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            @forelse($returns as $return)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="p-4">
                                    <span class="font-black text-white group-hover:text-primary transition-colors">{{ $return->return_number }}</span>
                                </td>
                                <td class="p-4 text-slate-300 font-medium">
                                    <div>{{ $return->invoice->contact->name ?? 'Bilinmiyor' }}</div>
                                    <div class="text-[10px] text-slate-500">Fatura: {{ $return->invoice->invoice_number ?? '-' }}</div>
                                </td>
                                <td class="p-4 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">
                                    {{ $return->return_date->format('d.m.Y') }}
                                </td>
                                <td class="p-4 text-right font-black text-white">
                                    ₺{{ number_format($return->total_amount, 2, ',', '.') }}
                                </td>
                                <td class="p-4 flex justify-center">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border 
                                        {{ match($return->status) {
                                            'Approved' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                            'Pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                            'Refunded' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                                            'Rejected' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            default => 'bg-white/10 text-white border-white/20'
                                        } }}">
                                        {{ match($return->status) {
                                            'Approved' => 'Onaylandı',
                                            'Pending' => 'Bekliyor',
                                            'Refunded' => 'İadesi Yapıldı',
                                            'Rejected' => 'Reddedildi',
                                            default => $return->status
                                        } }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('sales.returns.show', $return) }}" class="p-1 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[20px]">visibility</span></a>
                                        <a href="{{ route('sales.returns.edit', $return) }}" class="p-1 hover:text-blue-400 transition-colors"><span class="material-symbols-outlined text-[20px]">edit</span></a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-500 font-medium italic">Kayıtlı iade bulunamadı.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($returns->hasPages())
                <div class="p-4 border-t border-white/5 bg-white/[0.02]">
                    {{ $returns->links() }}
                </div>
                @endif
            </x-card>

        </div>
    </div>
</x-app-layout>
