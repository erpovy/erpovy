<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Müşteri ve Tedarikçiler
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">groups</span>
                        Ticari Rehber ve İletişim Yönetimi
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('crm.contacts.create', ['type' => $type]) }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">person_add</span>
                            Yeni {{ $type == 'customer' ? 'Müşteri' : 'Tedarikçi' }} Ekle
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
                    ['label' => 'Toplam Kayıt', 'value' => $stats['total'], 'icon' => 'contacts', 'color' => 'blue', 'type' => 'count'],
                    ['label' => 'Müşteriler', 'value' => $stats['customers'], 'icon' => 'person', 'color' => 'green', 'type' => 'count'],
                    ['label' => 'Tedarikçiler', 'value' => $stats['vendors'], 'icon' => 'store', 'color' => 'orange', 'type' => 'count'],
                    ['label' => 'Genel Bakiye', 'value' => $stats['total_balance'], 'icon' => 'payments', 'color' => 'purple', 'type' => 'currency']
                ] as $stat)
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $stat['color'] }}-500/20 to-{{ $stat['color'] }}-500/5 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-white/10 bg-white/5 backdrop-blur-2xl hover:border-{{ $stat['color'] }}-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-400">
                                <span class="material-symbols-outlined text-[24px]">{{ $stat['icon'] }}</span>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-white mb-1">
                            @if($stat['type'] == 'currency')
                                ₺{{ number_format($stat['value'], 0, ',', '.') }}
                            @else
                                {{ number_format($stat['value']) }}
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">{{ $stat['label'] }}</div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- Tabs, Filters & Search -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                <!-- Type Tabs -->
                <div class="lg:col-span-4">
                    <div class="flex bg-white/5 p-1.5 rounded-2xl border border-white/10 backdrop-blur-xl">
                        <a href="{{ route('crm.contacts.index', ['type' => 'customer']) }}" 
                           class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-black uppercase tracking-wider transition-all {{ $type == 'customer' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-500 hover:text-white hover:bg-white/5' }}">
                            <span class="material-symbols-outlined text-[18px]">person</span>
                            Müşteriler
                        </a>
                        <a href="{{ route('crm.contacts.index', ['type' => 'vendor']) }}" 
                           class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-black uppercase tracking-wider transition-all {{ $type == 'vendor' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-500 hover:text-white hover:bg-white/5' }}">
                            <span class="material-symbols-outlined text-[18px]">store</span>
                            Tedarikçiler
                        </a>
                    </div>
                </div>

                <!-- Search Form -->
                <div class="lg:col-span-8">
                    <form action="{{ route('crm.contacts.index') }}" method="GET" class="flex gap-4">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div class="flex-1 relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="İsim, e-posta veya telefon ile ara..." 
                                   style="padding-left: 20px !important;"
                                   class="w-full pr-4 py-3 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-500 focus:border-primary/50 focus:ring-0 transition-all">
                        </div>
                        <button type="submit" class="px-6 py-3 bg-white/10 hover:bg-primary/20 border border-white/10 rounded-2xl text-white font-black text-sm uppercase tracking-widest transition-all">
                            FİLTRELE
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contacts Table -->
            <x-card class="p-0 border-white/10 bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02] border-b border-white/5">
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Kişi / Firma Bilgileri</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">İletişim</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Vergi Detayları</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Bakiye</th>
                                <th class="p-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right w-24">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($contacts as $contact)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="p-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center text-xl font-black text-white border border-white/10 group-hover:border-primary/50 group-hover:scale-110 transition-all duration-300">
                                            {{ substr($contact->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-white group-hover:text-primary transition-colors leading-tight">{{ $contact->name }}</div>
                                            <div class="text-[10px] text-slate-500 font-bold uppercase mt-0.5">{{ $contact->type == 'customer' ? 'Müşteri' : 'Tedarikçi' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-xs text-slate-300">
                                            <span class="material-symbols-outlined text-[14px] text-slate-500">mail</span>
                                            {{ $contact->email ?: 'E-posta belirtilmedi' }}
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-slate-300 font-mono">
                                            <span class="material-symbols-outlined text-[14px] text-slate-500">phone</span>
                                            {{ $contact->phone ?: 'Telefon belirtilmedi' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <div class="text-[10px] font-black text-slate-400 bg-white/5 px-2 py-0.5 rounded border border-white/5">
                                            {{ $contact->tax_office ?: 'Daire Belirtilmedi' }}
                                        </div>
                                        <div class="text-xs text-slate-500 font-mono mt-1">{{ $contact->tax_number ?: '---' }}</div>
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black {{ $contact->current_balance < 0 ? 'text-red-400' : 'text-green-400' }}">
                                        {{ number_format($contact->current_balance, 2, ',', '.') }} ₺
                                    </div>
                                    <div class="text-[9px] text-slate-600 font-bold uppercase">Güncel Bakiye</div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('crm.contacts.edit', $contact->id) }}" class="p-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 transition-all active:scale-90" title="Düzenle">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <a href="{{ route('crm.contacts.show', $contact->id) }}" class="p-2 rounded-lg bg-white/5 text-slate-400 hover:bg-white/10 transition-all active:scale-90" title="Detay">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-slate-500 italic">
                                    Herhangi bir kayıt bulunamadı.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($contacts->hasPages())
                <div class="p-6 border-t border-white/5 bg-white/[0.01]">
                    {{ $contacts->links() }}
                </div>
                @endif
            </x-card>
        </div>
    </div>

    <!-- Live Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const target = document.getElementById('live-clock');
            if (target) target.textContent = `${hours}:${minutes}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</x-app-layout>
