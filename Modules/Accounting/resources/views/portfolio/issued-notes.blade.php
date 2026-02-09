<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-orange-500/5 via-amber-500/5 to-orange-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <a href="{{ route('accounting.portfolio.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-colors">
                            <span class="material-symbols-outlined">arrow_back</span>
                        </a>
                        <h2 class="font-black text-3xl text-white tracking-tight">
                            Verilen Senetler
                        </h2>
                    </div>
                </div>
                <a href="{{ route('accounting.promissory-notes.create', ['type' => 'issued']) }}" class="px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-500 hover:to-amber-500 text-white rounded-xl font-semibold shadow-lg shadow-orange-500/30 hover:shadow-orange-500/50 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                    Yeni Verilen Senet
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8">
            <x-card class="border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                @if($notes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5 border-b border-white/10">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Senet No</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Alacaklı</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Tutar</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Vade</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Durum</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-300 uppercase tracking-wider">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($notes as $note)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('accounting.promissory-notes.show', $note) }}" class="text-sm font-medium text-orange-400 hover:text-orange-300">
                                                {{ $note->note_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-300">{{ $note->contact?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-white">{{ number_format($note->amount, 2) }} {{ $note->currency }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-300">
                                            {{ $note->due_date->format('d.m.Y') }}
                                            @if($note->is_overdue)
                                                <span class="ml-2 text-xs text-red-400">(Vadesi geçti)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                {{ $note->status === 'portfolio' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                                {{ $note->status === 'deposited' ? 'bg-purple-500/20 text-purple-400' : '' }}
                                                {{ $note->status === 'cashed' ? 'bg-green-500/20 text-green-400' : '' }}
                                                {{ $note->status === 'protested' ? 'bg-red-500/20 text-red-400' : '' }}
                                                {{ $note->status === 'transferred' ? 'bg-orange-500/20 text-orange-400' : '' }}
                                                {{ $note->status === 'cancelled' ? 'bg-gray-500/20 text-gray-400' : '' }}">
                                                {{ $note->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('accounting.promissory-notes.show', $note) }}" class="text-orange-400 hover:text-orange-300">
                                                Görüntüle
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-white/10">
                        {{ $notes->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <span class="material-symbols-outlined text-6xl text-slate-600 mb-4">note</span>
                        <h3 class="text-xl font-semibold text-slate-400 mb-2">Verilen senet bulunmuyor</h3>
                        <p class="text-slate-500 mb-6">Portföyünüzde henüz verilen senet bulunmamaktadır.</p>
                        <a href="{{ route('accounting.promissory-notes.create', ['type' => 'issued']) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-orange-600 hover:bg-orange-500 text-white rounded-xl font-semibold transition-all">
                            <span class="material-symbols-outlined">add</span>
                            Yeni Senet Ekle
                        </a>
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
