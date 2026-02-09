<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 via-emerald-500/5 to-green-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Çek Yönetimi
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">receipt</span>
                        Alınan ve Verilen Çeklerin Yönetimi
                    </p>
                </div>
                <a href="{{ route('accounting.cheques.create') }}" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white rounded-xl font-semibold shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                    Yeni Çek
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8">
            
            <!-- Filtreler -->
            <x-card class="p-6 mb-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Tip</label>
                        <select name="type" class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white focus:ring-2 focus:ring-green-500">
                            <option value="">Tümü</option>
                            <option value="received" {{ request('type') == 'received' ? 'selected' : '' }}>Alınan</option>
                            <option value="issued" {{ request('type') == 'issued' ? 'selected' : '' }}>Verilen</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Durum</label>
                        <select name="status" class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white focus:ring-2 focus:ring-green-500">
                            <option value="">Tümü</option>
                            <option value="portfolio" {{ request('status') == 'portfolio' ? 'selected' : '' }}>Portföyde</option>
                            <option value="deposited" {{ request('status') == 'deposited' ? 'selected' : '' }}>Yatırıldı</option>
                            <option value="cashed" {{ request('status') == 'cashed' ? 'selected' : '' }}>Tahsil Edildi</option>
                            <option value="bounced" {{ request('status') == 'bounced' ? 'selected' : '' }}>Karşılıksız</option>
                            <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Ciro Edildi</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Vade Başlangıç</label>
                        <input type="date" name="due_date_from" value="{{ request('due_date_from') }}" class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Vade Bitiş</label>
                        <input type="date" name="due_date_to" value="{{ request('due_date_to') }}" class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div class="md:col-span-4 flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition-all">
                            Filtrele
                        </button>
                        <a href="{{ route('accounting.cheques.index') }}" class="px-6 py-2 bg-white/5 hover:bg-white/10 text-white rounded-lg font-medium transition-all">
                            Temizle
                        </a>
                    </div>
                </form>
            </x-card>

            <!-- Çek Listesi -->
            <x-card class="border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                @if($cheques->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-white/5 border-b border-white/10">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Çek No</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Tip</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Banka</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Keşideci</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Tutar</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Vade</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">Durum</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-slate-300 uppercase tracking-wider">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($cheques as $cheque)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('accounting.cheques.show', $cheque) }}" class="text-sm font-medium text-green-400 hover:text-green-300">
                                                {{ $cheque->cheque_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $cheque->type === 'received' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                                {{ $cheque->type_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-300">{{ $cheque->bank_name }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-300">{{ $cheque->contact?->name ?? $cheque->drawer }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-white">{{ number_format($cheque->amount, 2) }} {{ $cheque->currency }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-300">
                                            {{ $cheque->due_date->format('d.m.Y') }}
                                            @if($cheque->is_overdue)
                                                <span class="ml-2 text-xs text-red-400">(Vadesi geçti)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                                {{ $cheque->status === 'portfolio' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                                {{ $cheque->status === 'deposited' ? 'bg-purple-500/20 text-purple-400' : '' }}
                                                {{ $cheque->status === 'cashed' ? 'bg-green-500/20 text-green-400' : '' }}
                                                {{ $cheque->status === 'bounced' ? 'bg-red-500/20 text-red-400' : '' }}
                                                {{ $cheque->status === 'transferred' ? 'bg-orange-500/20 text-orange-400' : '' }}
                                                {{ $cheque->status === 'cancelled' ? 'bg-gray-500/20 text-gray-400' : '' }}">
                                                {{ $cheque->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('accounting.cheques.show', $cheque) }}" class="text-green-400 hover:text-green-300 mr-3">
                                                Görüntüle
                                            </a>
                                            @if($cheque->status === 'portfolio')
                                                <a href="{{ route('accounting.cheques.edit', $cheque) }}" class="text-blue-400 hover:text-blue-300">
                                                    Düzenle
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-white/10">
                        {{ $cheques->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <span class="material-symbols-outlined text-6xl text-slate-600 mb-4">receipt</span>
                        <h3 class="text-xl font-semibold text-slate-400 mb-2">Henüz çek bulunmuyor</h3>
                        <p class="text-slate-500 mb-6">İlk çekinizi oluşturmak için aşağıdaki butona tıklayın.</p>
                        <a href="{{ route('accounting.cheques.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-500 text-white rounded-xl font-semibold transition-all">
                            <span class="material-symbols-outlined">add</span>
                            İlk Çeki Oluştur
                        </a>
                    </div>
                @endif
            </x-card>

        </div>
    </div>
</x-app-layout>
