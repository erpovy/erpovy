<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-purple-500/5 via-pink-500/5 to-purple-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <a href="{{ route('accounting.portfolio.index') }}" class="p-2 rounded-lg bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                            <span class="material-symbols-outlined">arrow_back</span>
                        </a>
                        <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight">
                            Yaklaşan Vadeler
                        </h2>
                    </div>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium">Önümüzdeki 60 gün içindeki ödemeler ve tahsilatlar</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Çekler -->
            <x-card class="border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-purple-400">receipt_long</span>
                        Yaklaşan Çekler
                    </h3>
                </div>
                
                @if($upcomingCheques->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 dark:bg-white/5 border-b border-gray-200 dark:border-white/10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Tip</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Çek No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Cari Hesap</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Tutar</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Vade</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Kalan Gün</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                                @foreach($upcomingCheques as $cheque)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-3">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $cheque->type === 'received' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                                {{ $cheque->type_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-white font-medium">{{ $cheque->cheque_number }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-700 dark:text-slate-300">{{ $cheque->contact?->name ?? $cheque->drawer }}</td>
                                        <td class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($cheque->amount, 2) }} {{ $cheque->currency }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-700 dark:text-slate-300">{{ $cheque->due_date->format('d.m.Y') }}</td>
                                        <td class="px-6 py-3">
                                            <span class="text-xs font-bold px-2 py-1 rounded bg-blue-500/20 text-blue-400">
                                                {{ $cheque->days_until_due }} gün
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <a href="{{ route('accounting.cheques.show', $cheque) }}" class="text-sm text-purple-400 hover:text-purple-300">Görüntüle</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-600 dark:text-slate-500">
                        Yaklaşan ödeme/tahsilat çeki bulunmuyor.
                    </div>
                @endif
            </x-card>

            <!-- Senetler -->
            <x-card class="border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-cyan-400">description</span>
                        Yaklaşan Senetler
                    </h3>
                </div>
                
                @if($upcomingNotes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 dark:bg-white/5 border-b border-gray-200 dark:border-white/10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Tip</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Senet No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Cari Hesap</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Tutar</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Vade</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">Kalan Gün</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:text-slate-300 uppercase">İşlem</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                                @foreach($upcomingNotes as $note)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-3">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $note->type === 'received' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                                {{ $note->type_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-white font-medium">{{ $note->note_number }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-700 dark:text-slate-300">{{ $note->contact?->name ?? $note->drawer }}</td>
                                        <td class="px-6 py-3 text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($note->amount, 2) }} {{ $note->currency }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-700 dark:text-slate-300">{{ $note->due_date->format('d.m.Y') }}</td>
                                        <td class="px-6 py-3">
                                            <span class="text-xs font-bold px-2 py-1 rounded bg-blue-500/20 text-blue-400">
                                                {{ $note->days_until_due }} gün
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <a href="{{ route('accounting.promissory-notes.show', $note) }}" class="text-sm text-cyan-400 hover:text-cyan-300">Görüntüle</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-600 dark:text-slate-500">
                        Yaklaşan ödeme/tahsilat senedi bulunmuyor.
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
