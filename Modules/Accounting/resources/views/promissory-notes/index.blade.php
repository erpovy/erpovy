<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-cyan-500/5 to-blue-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Senetler
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">description</span>
                        Portföydeki tüm senetlerinizi yönetin
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('accounting.promissory-notes.create', ['type' => 'received']) }}" class="px-5 py-2.5 bg-green-600 hover:bg-green-500 text-white rounded-xl font-semibold shadow-lg shadow-green-500/30 transition-all flex items-center gap-2 group">
                        <span class="material-symbols-outlined group-hover:rotate-90 transition-transform">add</span>
                        Alınan Senet
                    </a>
                    <a href="{{ route('accounting.promissory-notes.create', ['type' => 'issued']) }}" class="px-5 py-2.5 bg-red-600 hover:bg-red-500 text-white rounded-xl font-semibold shadow-lg shadow-red-500/30 transition-all flex items-center gap-2 group">
                        <span class="material-symbols-outlined group-hover:rotate-90 transition-transform">remove</span>
                        Verilen Senet
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8">
            
            <x-card class="border-white/10 bg-white/5 backdrop-blur-2xl mb-8">
                <form action="{{ route('accounting.promissory-notes.index') }}" method="GET" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Senet Tipi</label>
                            <select name="type" class="w-full bg-white/5 border border-white/10 rounded-lg text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Tümü</option>
                                <option value="received" {{ request('type') == 'received' ? 'selected' : '' }}>Alınan Senetler</option>
                                <option value="issued" {{ request('type') == 'issued' ? 'selected' : '' }}>Verilen Senetler</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Durum</label>
                            <select name="status" class="w-full bg-white/5 border border-white/10 rounded-lg text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Tümü</option>
                                <option value="portfolio" {{ request('status') == 'portfolio' ? 'selected' : '' }}>Portföyde</option>
                                <option value="deposited" {{ request('status') == 'deposited' ? 'selected' : '' }}>Bankaya Yatırıldı/Ödendi</option>
                                <option value="cashed" {{ request('status') == 'cashed' ? 'selected' : '' }}>Tahsil Edildi</option>
                                <option value="bounced" {{ request('status') == 'bounced' ? 'selected' : '' }}>Protestolu</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Vade Başlangıç</label>
                            <input type="date" name="due_date_from" value="{{ request('due_date_from') }}" class="w-full bg-white/5 border border-white/10 rounded-lg text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Vade Bitiş</label>
                            <input type="date" name="due_date_to" value="{{ request('due_date_to') }}" class="w-full bg-white/5 border border-white/10 rounded-lg text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/30">
                            Filtrele
                        </button>
                    </div>
                </form>
            </x-card>

            <x-card class="border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                @if($notes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5 border-b border-white/10">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Tip</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Senet No</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Borçlu/Alacaklı</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Tutar</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Vade Tarihi</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Durum</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-300 uppercase tracking-wider">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($notes as $note)
                                    <tr class="hover:bg-white/5 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $note->type === 'received' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                                {{ $note->type_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-semibold text-white group-hover:text-blue-400 transition-colors">{{ $note->note_number }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-sm text-white font-medium">{{ $note->contact?->name ?? $note->drawer }}</span>
                                                <span class="text-xs text-slate-500">Ciro: {{ $note->endorser ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-bold text-white tracking-wide">{{ number_format($note->amount, 2) }} {{ $note->currency }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm text-slate-300">{{ $note->due_date->format('d.m.Y') }}</span>
                                                @if($note->is_overdue)
                                                    <span class="flex h-2 w-2 rounded-full bg-red-500" title="Gecikmiş"></span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs font-medium px-2 py-1 rounded-md 
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
                                            <a href="{{ route('accounting.promissory-notes.show', $note) }}" class="text-slate-400 hover:text-white transition-colors bg-white/5 hover:bg-white/10 px-3 py-1.5 rounded-lg border border-white/5">
                                                Detay
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="bg-white/5 p-4 rounded-full mb-4">
                            <span class="material-symbols-outlined text-4xl text-slate-500">description</span>
                        </div>
                        <h3 class="text-lg font-medium text-white mb-1">Kayıt Bulunamadı</h3>
                        <p class="text-slate-400 text-sm max-w-sm mx-auto mb-6">Aradığınız kriterlere uygun senet kaydı bulunmamaktadır. Yeni bir senet ekleyerek başlayabilirsiniz.</p>
                        <div class="flex gap-4">
                            <a href="{{ route('accounting.promissory-notes.create', ['type' => 'received']) }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-sm font-medium transition-colors">
                                Yeni Alınan Senet
                            </a>
                        </div>
                    </div>
                @endif
            </x-card>

            <div class="mt-6">
                {{ $notes->links() }}
            </div>
            
        </div>
    </div>
</x-app-layout>
