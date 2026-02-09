@php
    $settings = auth()->user()->company->settings['crm_settings'] ?? [];
    $sourcesString = $settings['lead_source_options'] ?? 'Web Sitesi, Referans, Sosyal Medya, Doğrudan Satış';
    $sources = array_map('trim', explode(',', $sourcesString));
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-2">
                <span class="material-symbols-outlined">person_search</span>
                {{ __('Potansiyel Müşteriler') }}
            </h2>
            <div class="flex gap-3">
                <form action="{{ route('crm.leads.index') }}" method="GET">
                    <div class="flex items-center w-64 rounded-xl border border-white/10 px-4 py-2 bg-[#1a1f2e] shadow-sm focus-within:ring-2 focus-within:ring-primary/50 focus-within:border-primary transition-all" style="background-color: #1a1f2e;">
                        <span class="material-symbols-outlined text-gray-400 text-[20px] mr-2">search</span>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Ara..." 
                               class="w-full bg-transparent border-none outline-none text-white text-sm placeholder-gray-500 focus:ring-0 p-0"
                               autocomplete="off"
                        >
                    </div>
                </form>
                <a href="{{ route('crm.leads.create') }}" class="bg-primary hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg shadow-primary/20 transition-all flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-lg">person_add</span>
                    Yeni Ekle
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <!-- ... (Start of table remains same) ... -->
                        <thead>
                            <tr class="border-b border-white/10 text-xs text-gray-400 uppercase tracking-wider">
                                <th class="p-4 font-semibold">Ad Soyad / Firma</th>
                                <th class="p-4 font-semibold">İletişim</th>
                                <th class="p-4 font-semibold">Firma İsmi</th>
                                <th class="p-4 font-semibold">Puan</th>
                                <th class="p-4 font-semibold">Atanan</th>
                                <th class="p-4 font-semibold text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            @forelse($leads as $lead)
                            <tr class="group hover:bg-white/5 transition-colors border-b border-white/5 last:border-0">
                                <td class="p-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#5c67ff] to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-primary/20 ring-2 ring-white/10">
                                            {{ substr($lead->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white group-hover:text-primary transition-colors text-[15px]">{{ $lead->full_name }}</div>
                                            <div class="text-xs font-medium text-slate-400 mt-0.5">{{ $lead->title ?? $lead->company_name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-col gap-1.5">
                                        @if($lead->email)
                                        <div class="flex items-center gap-2 text-slate-300 group-hover:text-white transition-colors">
                                            <span class="material-symbols-outlined text-[16px] text-slate-500">mail</span>
                                            <span class="text-xs font-medium">{{ $lead->email }}</span>
                                        </div>
                                        @endif
                                        @if($lead->phone)
                                        <div class="flex items-center gap-2 text-slate-300 group-hover:text-white transition-colors">
                                            <span class="material-symbols-outlined text-[16px] text-slate-500">call</span>
                                            <span class="text-xs font-medium">{{ $lead->phone }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="text-white font-medium">{{ $lead->company_name ?? '-' }}</span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-bold text-yellow-500 bg-yellow-500/10 px-2 py-1 rounded-lg border border-yellow-500/20">{{ $lead->score }}%</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    @if($lead->assignedUser)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-800 flex items-center justify-center text-[10px] text-slate-300 font-bold border border-white/10">
                                            {{ substr($lead->assignedUser->name, 0, 1) }}
                                        </div>
                                        <span class="text-slate-400 text-xs font-medium">{{ $lead->assignedUser->name }}</span>
                                    </div>
                                    @else
                                    <span class="text-slate-600 text-xs font-medium">-</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="tel:{{ $lead->phone }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:text-green-400 hover:bg-green-400/10 transition-all" title="Ara">
                                            <span class="material-symbols-outlined text-[20px]">call</span>
                                        </a>
                                        <a href="mailto:{{ $lead->email }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:text-blue-400 hover:bg-blue-400/10 transition-all" title="E-posta Gönder">
                                            <span class="material-symbols-outlined text-lg">mail</span>
                                        </a>
                                        <a href="{{ route('crm.leads.show', $lead->id) }}" class="p-2 hover:bg-white/10 rounded-lg text-gray-400 hover:text-white transition-colors" title="Detay">
                                            <span class="material-symbols-outlined text-lg">chevron_right</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    Henüz kayıtlı potansiyel müşteri yok.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($leads->hasPages())
                <div class="p-4 border-t border-white/10">
                    {{ $leads->links() }}
                </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
