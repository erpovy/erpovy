<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/5 via-purple-500/5 to-indigo-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Çek/Senet Portföyü
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">account_balance_wallet</span>
                        Çek ve Senet Portföy Yönetimi
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Özet Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Alınan Çekler -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:bg-white/10 transition-all group cursor-pointer" onclick="window.location='{{ route('accounting.portfolio.received-cheques') }}'">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-green-500/20 to-emerald-500/20 text-green-400">
                            <span class="material-symbols-outlined text-[32px]">receipt</span>
                        </div>
                        <span class="text-xs text-slate-500">{{ $stats['received_cheques_count'] }} adet</span>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400 mb-1">Alınan Çekler</h3>
                    <p class="text-2xl font-black text-green-400">{{ number_format($stats['received_cheques_amount'], 2) }}₺</p>
                </x-card>

                <!-- Verilen Çekler -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:bg-white/10 transition-all group cursor-pointer" onclick="window.location='{{ route('accounting.portfolio.issued-cheques') }}'">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-red-500/20 to-orange-500/20 text-red-400">
                            <span class="material-symbols-outlined text-[32px]">receipt_long</span>
                        </div>
                        <span class="text-xs text-slate-500">{{ $stats['issued_cheques_count'] }} adet</span>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400 mb-1">Verilen Çekler</h3>
                    <p class="text-2xl font-black text-red-400">{{ number_format($stats['issued_cheques_amount'], 2) }}₺</p>
                </x-card>

                <!-- Alınan Senetler -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:bg-white/10 transition-all group cursor-pointer" onclick="window.location='{{ route('accounting.portfolio.received-notes') }}'">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 text-blue-400">
                            <span class="material-symbols-outlined text-[32px]">description</span>
                        </div>
                        <span class="text-xs text-slate-500">{{ $stats['received_notes_count'] }} adet</span>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400 mb-1">Alınan Senetler</h3>
                    <p class="text-2xl font-black text-blue-400">{{ number_format($stats['received_notes_amount'], 2) }}₺</p>
                </x-card>

                <!-- Verilen Senetler -->
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:bg-white/10 transition-all group cursor-pointer" onclick="window.location='{{ route('accounting.portfolio.issued-notes') }}'">
                    <div class="flex items-start justify-between mb-4">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-orange-500/20 to-amber-500/20 text-orange-400">
                            <span class="material-symbols-outlined text-[32px]">note</span>
                        </div>
                        <span class="text-xs text-slate-500">{{ $stats['issued_notes_count'] }} adet</span>
                    </div>
                    <h3 class="text-sm font-medium text-slate-400 mb-1">Verilen Senetler</h3>
                    <p class="text-2xl font-black text-orange-400">{{ number_format($stats['issued_notes_amount'], 2) }}₺</p>
                </x-card>
            </div>

            <!-- Hızlı Erişim -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('accounting.cheques.create') }}" class="block">
                    <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:bg-white/10 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-xl bg-gradient-to-br from-green-500/20 to-emerald-500/20">
                                <span class="material-symbols-outlined text-green-400 text-[28px]">add_circle</span>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">Yeni Çek</h3>
                                <p class="text-sm text-slate-400">Çek ekle</p>
                            </div>
                        </div>
                    </x-card>
                </a>

                <a href="{{ route('accounting.promissory-notes.create') }}" class="block">
                    <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:bg-white/10 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20">
                                <span class="material-symbols-outlined text-blue-400 text-[28px]">add_circle</span>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">Yeni Senet</h3>
                                <p class="text-sm text-slate-400">Senet ekle</p>
                            </div>
                        </div>
                    </x-card>
                </a>

                <a href="{{ route('accounting.portfolio.upcoming') }}" class="block">
                    <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:bg-white/10 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20">
                                <span class="material-symbols-outlined text-purple-400 text-[28px]">event</span>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">Yaklaşan Vadeler</h3>
                                <p class="text-sm text-slate-400">Vade takvimi</p>
                            </div>
                        </div>
                    </x-card>
                </a>
            </div>

            @if($overdueCheques->count() > 0 || $overdueNotes->count() > 0)
                <!-- Vadesi Geçenler Uyarısı -->
                <x-card class="p-6 border-red-500/30 bg-gradient-to-br from-red-500/10 to-orange-500/10 backdrop-blur-2xl">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-red-400 text-[28px]">warning</span>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-white mb-2">⚠️ Vadesi Geçmiş Çek/Senetler</h3>
                            <p class="text-sm text-slate-300 mb-4">Aşağıdaki çek ve senetlerin vadesi geçmiştir. Lütfen gerekli işlemleri yapınız.</p>
                            
                            @if($overdueCheques->count() > 0)
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-red-300 mb-2">Çekler ({{ $overdueCheques->count() }} adet)</h4>
                                    <div class="space-y-2">
                                        @foreach($overdueCheques as $cheque)
                                            <div class="flex items-center justify-between p-3 rounded-lg bg-white/5">
                                                <div>
                                                    <p class="text-sm text-white font-medium">{{ $cheque->cheque_number }} - {{ $cheque->bank_name }}</p>
                                                    <p class="text-xs text-slate-400">{{ $cheque->contact?->name ?? $cheque->drawer }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-bold text-red-400">{{ number_format($cheque->amount, 2) }}₺</p>
                                                    <p class="text-xs text-slate-400">{{ $cheque->due_date->format('d.m.Y') }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($overdueNotes->count() > 0)
                                <div>
                                    <h4 class="text-sm font-semibold text-red-300 mb-2">Senetler ({{ $overdueNotes->count() }} adet)</h4>
                                    <div class="space-y-2">
                                        @foreach($overdueNotes as $note)
                                            <div class="flex items-center justify-between p-3 rounded-lg bg-white/5">
                                                <div>
                                                    <p class="text-sm text-white font-medium">{{ $note->note_number }}</p>
                                                    <p class="text-xs text-slate-400">{{ $note->contact?->name ?? $note->drawer }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-bold text-red-400">{{ number_format($note->amount, 2) }}₺</p>
                                                    <p class="text-xs text-slate-400">{{ $note->due_date->format('d.m.Y') }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endif

            <!-- Yaklaşan Vadeler (30 gün) -->
            @if($upcomingCheques->count() > 0 || $upcomingNotes->count() > 0)
                <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-400">event_upcoming</span>
                            Yaklaşan Vadeler (30 Gün)
                        </h3>
                        <a href="{{ route('accounting.portfolio.upcoming') }}" class="text-sm text-purple-400 hover:text-purple-300">
                            Tümünü Gör →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($upcomingCheques->count() > 0)
                            <div>
                                <h4 class="text-sm font-semibold text-slate-300 mb-3">Çekler</h4>
                                <div class="space-y-2">
                                    @foreach($upcomingCheques->take(5) as $cheque)
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-white/5 hover:bg-white/10 transition-all">
                                            <div class="flex-1">
                                                <p class="text-sm text-white font-medium">{{ $cheque->cheque_number }}</p>
                                                <p class="text-xs text-slate-400">{{ $cheque->contact?->name ?? $cheque->drawer }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-bold text-green-400">{{ number_format($cheque->amount, 2) }}₺</p>
                                                <p class="text-xs text-slate-400">{{ $cheque->due_date->format('d.m.Y') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($upcomingNotes->count() > 0)
                            <div>
                                <h4 class="text-sm font-semibold text-slate-300 mb-3">Senetler</h4>
                                <div class="space-y-2">
                                    @foreach($upcomingNotes->take(5) as $note)
                                        <div class="flex items-center justify-between p-3 rounded-lg bg-white/5 hover:bg-white/10 transition-all">
                                            <div class="flex-1">
                                                <p class="text-sm text-white font-medium">{{ $note->note_number }}</p>
                                                <p class="text-xs text-slate-400">{{ $note->contact?->name ?? $note->drawer }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-bold text-blue-400">{{ number_format($note->amount, 2) }}₺</p>
                                                <p class="text-xs text-slate-400">{{ $note->due_date->format('d.m.Y') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </x-card>
            @endif

        </div>
    </div>
</x-app-layout>
