<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-purple-500/5 via-pink-500/5 to-purple-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Mizan
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">balance</span>
                        Tüm Hesapların Borç/Alacak Toplamları
                    </p>
                </div>
                <a href="{{ route('accounting.reports.index') }}" class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white text-sm font-medium transition-all">
                    <span class="material-symbols-outlined text-[18px] align-middle">arrow_back</span>
                    Raporlara Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Özet Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Toplam Borç -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-red-500/20 to-orange-500/20 text-red-400">
                            <span class="material-symbols-outlined text-[32px]">remove_circle</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400 mb-1">Toplam Borç</h3>
                    <p class="text-3xl font-black text-red-400">{{ number_format($total_debit, 2) }}₺</p>
                </x-card>

                <!-- Toplam Alacak -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-green-500/20 to-emerald-500/20 text-green-400">
                            <span class="material-symbols-outlined text-[32px]">add_circle</span>
                        </div>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400 mb-1">Toplam Alacak</h3>
                    <p class="text-3xl font-black text-green-400">{{ number_format($total_credit, 2) }}₺</p>
                </x-card>
            </div>

            <!-- Mizan Tablosu -->
            <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-400">table_chart</span>
                        Hesap Hareketleri
                    </h3>
                    <span class="text-sm text-slate-400">{{ $start_date }} - {{ $end_date }}</span>
                </div>

                @if($accounts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-400">Kod</th>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-slate-400">Hesap Adı</th>
                                    <th class="text-center py-3 px-4 text-sm font-semibold text-slate-400">Tip</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-slate-400">Borç</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-slate-400">Alacak</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-slate-400">Bakiye</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
                                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                        <td class="py-3 px-4 text-sm text-slate-300 font-mono">{{ $account['code'] }}</td>
                                        <td class="py-3 px-4 text-sm text-white">{{ $account['name'] }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium
                                                @if($account['type'] == 'asset') bg-green-500/20 text-green-400
                                                @elseif($account['type'] == 'liability') bg-orange-500/20 text-orange-400
                                                @elseif($account['type'] == 'equity') bg-blue-500/20 text-blue-400
                                                @elseif($account['type'] == 'revenue') bg-cyan-500/20 text-cyan-400
                                                @elseif($account['type'] == 'expense') bg-red-500/20 text-red-400
                                                @else bg-slate-500/20 text-slate-400
                                                @endif">
                                                @if($account['type'] == 'asset') Aktif
                                                @elseif($account['type'] == 'liability') Pasif
                                                @elseif($account['type'] == 'equity') Özkaynak
                                                @elseif($account['type'] == 'revenue') Gelir
                                                @elseif($account['type'] == 'expense') Gider
                                                @else {{ $account['type'] }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-red-400 font-semibold text-right">{{ number_format($account['debit'], 2) }}₺</td>
                                        <td class="py-3 px-4 text-sm text-green-400 font-semibold text-right">{{ number_format($account['credit'], 2) }}₺</td>
                                        <td class="py-3 px-4 text-sm font-bold text-right
                                            @if($account['balance'] > 0) text-blue-400
                                            @elseif($account['balance'] < 0) text-orange-400
                                            @else text-slate-400
                                            @endif">
                                            {{ number_format(abs($account['balance']), 2) }}₺
                                            @if($account['balance'] > 0)
                                                <span class="text-xs ml-1">(B)</span>
                                            @elseif($account['balance'] < 0)
                                                <span class="text-xs ml-1">(A)</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-purple-500/10 border-t-2 border-purple-500/30">
                                    <td colspan="3" class="py-3 px-4 text-sm font-bold text-white">TOPLAM</td>
                                    <td class="py-3 px-4 text-lg font-black text-red-400 text-right">{{ number_format($total_debit, 2) }}₺</td>
                                    <td class="py-3 px-4 text-lg font-black text-green-400 text-right">{{ number_format($total_credit, 2) }}₺</td>
                                    <td class="py-3 px-4 text-lg font-black text-slate-400 text-right">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400">
                        <span class="material-symbols-outlined text-[48px] opacity-50">inbox</span>
                        <p class="mt-2">Bu dönemde hesap hareketi bulunamadı.</p>
                    </div>
                @endif
            </x-card>

            <!-- Mizan Dengesi -->
            <x-card class="p-6 border-white/10 bg-gradient-to-br from-{{ $total_debit == $total_credit ? 'green' : 'red' }}-500/10 to-{{ $total_debit == $total_credit ? 'emerald' : 'orange' }}-500/10 backdrop-blur-2xl border-{{ $total_debit == $total_credit ? 'green' : 'red' }}-500/30">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-slate-300 mb-2">Toplam Borç</h3>
                        <p class="text-4xl font-black text-red-400">{{ number_format($total_debit, 2) }}₺</p>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-slate-300 mb-2">Toplam Alacak</h3>
                        <p class="text-4xl font-black text-green-400">{{ number_format($total_credit, 2) }}₺</p>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    @if($total_debit == $total_credit)
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-500/20 border border-green-500/30">
                            <span class="material-symbols-outlined text-green-400">check_circle</span>
                            <span class="text-green-400 font-semibold">Mizan Dengede</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-500/20 border border-red-500/30">
                            <span class="material-symbols-outlined text-red-400">error</span>
                            <span class="text-red-400 font-semibold">Mizan Dengesiz! Fark: {{ number_format(abs($total_debit - $total_credit), 2) }}₺</span>
                        </div>
                    @endif
                    <p class="text-sm text-slate-400 mt-2">{{ $start_date }} - {{ $end_date }} dönemi</p>
                </div>
            </x-card>

            <!-- Açıklama -->
            <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-blue-400 text-[24px]">info</span>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-white mb-2">Mizan Hakkında</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Mizan, belirli bir dönemde tüm hesapların borç ve alacak hareketlerini gösteren bir rapordur. 
                            Bakiye sütununda <span class="text-blue-400 font-semibold">(B)</span> borç bakiyesini, 
                            <span class="text-orange-400 font-semibold">(A)</span> alacak bakiyesini ifade eder. 
                            Toplam borç ve alacak tutarları eşit olmalıdır.
                        </p>
                    </div>
                </div>
            </x-card>

        </div>
    </div>
</x-app-layout>
