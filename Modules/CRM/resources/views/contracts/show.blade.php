<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Sözleşme Detayı 📄
                    </h2>
                    <p class="text-slate-400 text-sm font-medium">
                        #{{ $contract->id }} - {{ $contract->subject }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('crm.contracts.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 text-white font-bold text-xs uppercase tracking-wider hover:bg-slate-700 transition-all">
                        Geri Dön
                    </a>
                    <a href="{{ route('crm.contracts.edit', $contract) }}" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-blue-500 transition-all shadow-lg shadow-blue-600/20">
                        Düzenle
                    </a>
                    <form action="{{ route('crm.contracts.destroy', $contract) }}" method="POST" onsubmit="return confirm('Bu sözleşmeyi silmek istediğinize emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-red-500 transition-all shadow-lg shadow-red-600/20">
                            Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto max-w-5xl px-6 lg:px-8 space-y-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Contract Header Card -->
                    <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-2xl font-black text-white mb-2">{{ $contract->subject }}</h3>
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest border 
                                        {{ match($contract->status) {
                                            'Active' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                            'Draft' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                                            'Expired' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            default => 'bg-blue-500/10 text-blue-400 border-blue-500/20'
                                        } }}">
                                        {{ match($contract->status) {
                                            'Active' => 'Aktif',
                                            'Draft' => 'Taslak',
                                            'Expired' => 'Süresi Dolmuş',
                                            'Terminated' => 'İptal',
                                            default => $contract->status
                                        } }}
                                    </span>
                                    <span class="text-slate-400 font-medium flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                        {{ \Carbon\Carbon::parse($contract->start_date)->format('d.m.Y') }} - 
                                        {{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d.m.Y') : 'Süresiz' }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-slate-400 font-bold uppercase tracking-wider mb-1">Sözleşme Değeri</div>
                                <div class="text-3xl font-black text-white">₺{{ number_format($contract->value, 2, ',', '.') }}</div>
                            </div>
                        </div>
                    </x-card>

                    <!-- Content Body -->
                    <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl min-h-[400px]">
                        <h4 class="text-lg font-black text-white mb-6 flex items-center gap-2 border-b border-white/10 pb-4">
                            <span class="material-symbols-outlined text-slate-400">description</span>
                            Sözleşme İçeriği
                        </h4>
                        @if($contract->content)
                            <div class="prose prose-invert max-w-none text-slate-300">
                                {!! nl2br(e($contract->content)) !!}
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-12 text-slate-500">
                                <span class="material-symbols-outlined text-4xl mb-2">description</span>
                                <p>Bu sözleşme için henüz içerik girilmemiş.</p>
                            </div>
                        @endif
                    </x-card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Card -->
                    <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-wider mb-4 border-b border-white/10 pb-2">Müşteri Bilgileri</h4>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-black text-lg shadow-lg shadow-blue-500/20">
                                {{ substr($contract->contact->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <div class="font-bold text-white">{{ $contract->contact->name ?? 'Bilinmiyor' }}</div>
                                <div class="text-xs text-slate-500">{{ $contract->contact->email ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center text-slate-400">
                                <span>Telefon:</span>
                                <span class="text-white">{{ $contract->contact->phone ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-400">
                                <span>Vergi No:</span>
                                <span class="text-white">{{ $contract->contact->tax_number ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <a href="{{ route('crm.contacts.show', $contract->contact_id) }}" class="block w-full text-center py-2 bg-white/5 hover:bg-white/10 rounded-lg text-primary text-xs font-black uppercase tracking-widest transition-all">
                                Müşteri Kartına Git
                            </a>
                        </div>
                    </x-card>

                    <!-- Related Deal -->
                    @if($contract->deal)
                    <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-wider mb-4 border-b border-white/10 pb-2">İlgili Fırsat</h4>
                        <div class="mb-2">
                             <div class="font-bold text-white">{{ $contract->deal->title }}</div>
                             <div class="text-xs text-slate-500">Değer: ₺{{ number_format($contract->deal->amount, 2) }}</div>
                        </div>
                        <a href="#" class="text-primary text-xs font-bold hover:underline">Fırsata Git →</a>
                    </x-card>
                    @endif

                    <!-- Actions -->
                    <x-card class="p-6 border-white/10 bg-gradient-to-br from-slate-800 to-slate-900">
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-wider mb-4">Hızlı İşlemler</h4>
                        <div class="space-y-3">
                            <button class="w-full flex items-center justify-between px-4 py-3 bg-white/5 hover:bg-white/10 rounded-xl text-white transition-all group">
                                <span class="text-sm font-bold">PDF İndir</span>
                                <span class="material-symbols-outlined text-slate-400 group-hover:text-white transition-colors">download</span>
                            </button>
                            <button class="w-full flex items-center justify-between px-4 py-3 bg-white/5 hover:bg-white/10 rounded-xl text-white transition-all group">
                                <span class="text-sm font-bold">Yazdır</span>
                                <span class="material-symbols-outlined text-slate-400 group-hover:text-white transition-colors">print</span>
                            </button>
                            <button class="w-full flex items-center justify-between px-4 py-3 bg-white/5 hover:bg-white/10 rounded-xl text-white transition-all group">
                                <span class="text-sm font-bold">E-posta Gönder</span>
                                <span class="material-symbols-outlined text-slate-400 group-hover:text-white transition-colors">mail</span>
                            </button>
                        </div>
                    </x-card>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
