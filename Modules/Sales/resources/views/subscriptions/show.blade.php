<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div class="flex items-center gap-6">
                    <a href="{{ route('sales.subscriptions.index') }}" class="flex items-center justify-center w-12 h-12 rounded-2xl bg-white/5 border border-white/10 text-slate-400 hover:text-white hover:bg-white/10 hover:border-primary/30 transition-all group/back">
                        <span class="material-symbols-outlined text-[24px] group-hover/back:-translate-x-1 transition-transform">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="font-black text-3xl text-white tracking-tight mb-1">{{ $subscription->name }}</h2>
                        <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">verified</span>
                            Abonelik Detaylarını Görüntülüyorsunuz
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto max-w-6xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Details -->
                <div class="lg:col-span-2 space-y-6">
                    <x-card class="p-8 !bg-[#0f172a]/40 border-white/5 backdrop-blur-3xl relative overflow-hidden shadow-2xl">
                        <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                            <span class="material-symbols-outlined text-[160px]">contact_support</span>
                        </div>

                        <div class="relative space-y-8">
                            <div class="flex items-center gap-4 border-b border-white/5 pb-6">
                                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center border border-primary/20">
                                    <span class="material-symbols-outlined text-primary text-[24px]">info</span>
                                </div>
                                <h3 class="text-xl font-black text-white uppercase tracking-tight">Abonelik Detayları</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                                <div class="space-y-6">
                                    <div>
                                        <p class="text-[11px] text-slate-500 font-black uppercase tracking-[0.2em] mb-2">Abonelik Bedeli</p>
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-4xl font-black text-white">₺{{ number_format($subscription->price, 2, ',', '.') }}</p>
                                            <p class="text-sm text-slate-500 font-bold uppercase tracking-widest">/ {{ $subscription->billing_interval }}</p>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-[11px] text-slate-500 font-black uppercase tracking-[0.2em] mb-2">Başlangıç Tarihi</p>
                                        <p class="text-2xl font-black text-white tracking-tight underline decoration-primary/30 underline-offset-8">
                                            {{ $subscription->start_date->format('d.m.Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <p class="text-[11px] text-slate-500 font-black uppercase tracking-[0.2em] mb-2">Durum</p>
                                        <div class="inline-flex">
                                            @php
                                                $statusClasses = [
                                                    'active'    => 'bg-emerald-600 border-emerald-400 text-white',
                                                    'suspended' => 'bg-amber-600 border-amber-400 text-white',
                                                    'cancelled' => 'bg-red-600 border-red-400 text-white',
                                                ];
                                                $statusLabels = [
                                                    'active'    => 'AKTİF',
                                                    'suspended' => 'ASKIDA',
                                                    'cancelled' => 'İPTAL',
                                                ];
                                            @endphp
                                            <span class="px-5 py-2 rounded-xl text-xs font-black tracking-widest border flex items-center gap-2 {{ $statusClasses[$subscription->status] ?? 'bg-slate-800 text-slate-400 border-white/10' }}">
                                                @if($subscription->status === 'suspended')
                                                    <span class="material-symbols-outlined text-[18px]">pause_circle</span>
                                                @elseif($subscription->status === 'active')
                                                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                                @elseif($subscription->status === 'cancelled')
                                                    <span class="material-symbols-outlined text-[18px]">cancel</span>
                                                @endif
                                                {{ $statusLabels[$subscription->status] ?? $subscription->status }}
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-[11px] text-slate-500 font-black uppercase tracking-[0.2em] mb-2">Sonraki Fatura</p>
                                        <p class="text-2xl font-black text-emerald-500 tracking-tight">
                                            {{ optional($subscription->next_billing_date)->format('d.m.Y') ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-10 border-t border-white/5">
                                <p class="text-[11px] text-slate-500 font-black uppercase tracking-[0.2em] mb-3">Notlar</p>
                                <div class="p-6 rounded-2xl bg-[#1e293b]/50 border border-white/5">
                                    <p class="text-slate-400 text-sm leading-relaxed {{ !$subscription->notes ? 'italic' : '' }}">
                                        {{ $subscription->notes ?: 'Açıklama girilmemiş.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Right Column: Sidebar Cards -->
                <div class="space-y-6">
                    <!-- Customer Card -->
                    <x-card class="p-8 !bg-[#0f172a]/40 border-white/5 backdrop-blur-3xl relative overflow-hidden shadow-2xl group">
                        <div class="absolute -top-12 -right-12 w-24 h-24 bg-primary/10 rounded-full blur-2xl group-hover:bg-primary/20 transition-all duration-700"></div>
                        
                        <div class="relative space-y-6">
                            <h4 class="text-[11px] font-black text-slate-500 uppercase tracking-[0.3em] mb-4">Müşteri Kartı</h4>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-primary shadow-inner">
                                    <span class="material-symbols-outlined text-[32px]">person</span>
                                </div>
                                <div>
                                    <p class="text-xl font-black text-white tracking-tight">{{ $subscription->contact->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-slate-500 font-bold tracking-widest">{{ $subscription->contact->tax_number ?? 'Vergi no yok' }}</p>
                                </div>
                            </div>
                            <a href="#" class="block w-full text-center py-4 rounded-2xl bg-white/5 border border-white/10 text-white text-xs font-black uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all shadow-xl">
                                Müşteri Detayına Git
                            </a>
                        </div>
                    </x-card>

                    <!-- Quick Actions -->
                    <x-card class="p-8 !bg-[#0f172a]/40 border-white/5 backdrop-blur-3xl relative overflow-hidden shadow-2xl">
                        <div class="relative space-y-6">
                            <h4 class="text-[11px] font-black text-slate-500 uppercase tracking-[0.3em] mb-4">Hızlı Aksiyonlar</h4>
                            <div class="grid grid-cols-1 gap-4">
                                <a href="{{ route('sales.subscriptions.edit', $subscription->id) }}" 
                                   class="w-full flex items-center gap-4 p-5 rounded-2xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all group shadow-sm">
                                    <div class="p-2 rounded-lg bg-white/5 text-slate-500 group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-[0.15em]">Düzenle</span>
                                </a>

                                <form action="{{ route('sales.subscriptions.createInvoice', $subscription->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center gap-4 p-5 rounded-2xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all group shadow-sm">
                                        <div class="p-2 rounded-lg bg-white/5 text-slate-500 group-hover:text-emerald-500 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">receipt</span>
                                        </div>
                                        <span class="text-xs font-black uppercase tracking-[0.15em]">Manuel Fatura Kes</span>
                                    </button>
                                </form>

                                <form action="{{ route('sales.subscriptions.toggleStatus', $subscription->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full flex items-center gap-4 p-5 rounded-2xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all group shadow-sm">
                                        <div class="p-2 rounded-lg bg-white/5 text-slate-500 group-hover:text-amber-500 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">{{ $subscription->status === 'active' ? 'pause' : 'play_arrow' }}</span>
                                        </div>
                                        <span class="text-xs font-black uppercase tracking-[0.15em]">
                                            {{ $subscription->status === 'active' ? 'Aboneliği Durdur' : 'Aboneliği Başlat' }}
                                        </span>
                                    </button>
                                </form>

                                <form action="{{ route('sales.subscriptions.destroy', $subscription->id) }}" method="POST" onsubmit="return confirm('Emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full flex items-center gap-4 p-5 rounded-2xl bg-red-500/5 border border-red-500/10 text-red-400/80 hover:text-red-400 hover:bg-red-500/10 transition-all group shadow-sm">
                                        <div class="p-2 rounded-lg bg-red-500/5 text-red-500/50 group-hover:text-red-500 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </div>
                                        <span class="text-xs font-black uppercase tracking-[0.15em]">Aboneliği Sil</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
