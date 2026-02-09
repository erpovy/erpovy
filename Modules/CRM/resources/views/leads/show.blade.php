<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-2">
                <a href="{{ route('crm.leads.index') }}" class="text-gray-400 hover:text-white transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                {{ $lead->full_name }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('crm.leads.edit', $lead->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined">edit</span>
                    Düzenle
                </a>
                <a href="tel:{{ $lead->phone }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined">call</span>
                    Ara
                </a>
                <a href="mailto:{{ $lead->email }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2">
                    <span class="material-symbols-outlined">mail</span>
                    E-posta
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sol Kolon: Profil ve Temel Bilgiler -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Profil Kartı -->
                    <div class="relative overflow-hidden rounded-2xl bg-[#1e293b]/80 backdrop-blur-xl border border-white/5 shadow-2xl">
                        <!-- Kapak Arkaplanı -->
                        <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                        
                        <!-- Avatar ve İsim -->
                        <div class="px-6 pb-6 text-center -mt-12 relative z-10">
                            <div class="w-24 h-24 mx-auto rounded-full bg-[#0f172a] p-1.5 ring-4 ring-[#0f172a]">
                                <div class="w-full h-full rounded-full bg-gradient-to-br from-[#5c67ff] to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-inner">
                                    {{ substr($lead->first_name, 0, 1) }}
                                </div>
                            </div>
                            <h3 class="mt-3 text-xl font-bold text-white">{{ $lead->full_name }}</h3>
                            <p class="text-slate-300 text-sm font-medium">{{ $lead->title ?? $lead->company_name }}</p>
                            
                            <!-- Hızlı İletişim Butonları -->
                            <div class="flex justify-center gap-3 mt-6">
                                <a href="tel:{{ $lead->phone }}" class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg group" style="background-color: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.2); box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.2); color: #4ade80;">
                                    <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform">call</span>
                                </a>
                                <a href="mailto:{{ $lead->email }}" class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg group" style="background-color: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.2); box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.2); color: #60a5fa;">
                                    <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform">mail</span>
                                </a>
                            </div>
                        </div>

                        <!-- İletişim Listesi -->
                        <div class="px-6 pb-8 space-y-5">
                            <div class="h-px w-full bg-gradient-to-r from-transparent via-white/10 to-transparent mb-6"></div>
                            
                            <div class="flex items-center gap-4 p-3 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors group">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-none transition-all shadow-lg group-hover:scale-105" style="background-color: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.2); box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.2); color: #60a5fa;">
                                    <span class="material-symbols-outlined text-[24px]">mail</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">E-posta</p>
                                    <p class="text-sm text-white truncate font-medium">{{ $lead->email ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-3 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors group">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-none transition-all shadow-lg group-hover:scale-105" style="background-color: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.2); box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.2); color: #4ade80;">
                                    <span class="material-symbols-outlined text-[24px]">call</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Telefon</p>
                                    <p class="text-sm text-white truncate font-medium">{{ $lead->phone ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 p-3 rounded-2xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors group">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-none transition-all shadow-lg group-hover:scale-105" style="background-color: rgba(168, 85, 247, 0.2); border: 1px solid rgba(168, 85, 247, 0.2); box-shadow: 0 10px 15px -3px rgba(168, 85, 247, 0.2); color: #c084fc;">
                                    <span class="material-symbols-outlined text-[24px]">location_on</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Adres</p>
                                    <p class="text-sm text-white font-medium">{{ $lead->address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CRM Durum Kartı -->
                    <div class="rounded-2xl bg-[#1e293b]/80 backdrop-blur-xl border border-white/5 shadow-2xl p-6">
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary shadow-[0_0_8px_theme(colors.primary.500)]"></span>
                            CRM Durumu
                        </h4>
                        
                        <div class="space-y-6 relative">
                            <!-- Dikey Çizgi (Arkaplanda) -->
                            <div class="absolute left-[23px] top-2 bottom-2 w-0.5 bg-white/5"></div>
                            
                            <!-- Mevcut Durum -->
                            <div class="relative flex gap-5 group">
                                <div class="relative z-10 flex-none w-12 h-12 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg group-hover:scale-105" style="background-color: rgba(59, 130, 246, 0.2); border: 1px solid rgba(59, 130, 246, 0.2); box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.2); color: #60a5fa;">
                                        <span class="material-symbols-outlined text-[24px]">bookmark</span>
                                    </div>
                                </div>
                                <div class="flex-1 py-1">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mb-1.5">Mevcut Durum</p>
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 shadow-sm shadow-blue-500/20">
                                        {{ $lead->status }}
                                    </span>
                                </div>
                            </div>

                            <!-- Kaynak -->
                            <div class="relative flex gap-5 group">
                                <div class="relative z-10 flex-none w-12 h-12 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg group-hover:scale-105" style="background-color: rgba(99, 102, 241, 0.2); border: 1px solid rgba(99, 102, 241, 0.2); box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.2); color: #818cf8;">
                                        <span class="material-symbols-outlined text-[24px]">hub</span>
                                    </div>
                                </div>
                                <div class="flex-1 py-1">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Kaynak</p>
                                    <span class="text-white font-medium text-lg">{{ $lead->source ?? '-' }}</span>
                                </div>
                            </div>

                            <!-- Sıcaklık Skoru -->
                            <div class="relative flex gap-5 group">
                                <div class="relative z-10 flex-none w-12 h-12 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg group-hover:scale-105" style="background-color: rgba(234, 179, 8, 0.2); border: 1px solid rgba(234, 179, 8, 0.2); box-shadow: 0 10px 15px -3px rgba(234, 179, 8, 0.2); color: #facc15;">
                                        <span class="material-symbols-outlined text-[24px]">local_fire_department</span>
                                    </div>
                                </div>
                                <div class="flex-1 py-1">
                                    <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Sıcaklık Skoru</p>
                                    <span class="text-2xl font-bold text-yellow-500 tracking-tight">{{ $lead->score }}<span class="text-sm align-top ml-0.5">%</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sağ Kolon: Detaylar ve Geçmiş -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Üst İstatistikler -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-4 rounded-2xl bg-[#1e293b]/50 border border-white/5 flex items-center gap-4 hover:bg-white/5 transition-colors group">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg group-hover:scale-105" style="background-color: rgba(249, 115, 22, 0.2); border: 1px solid rgba(249, 115, 22, 0.2); box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.2); color: #fb923c;">
                                <span class="material-symbols-outlined text-[24px]">calendar_month</span>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Oluşturulma</p>
                                <p class="text-white font-bold text-lg leading-tight">{{ $lead->created_at->format('d.m.Y') }}</p>
                            </div>
                        </div>
                        <div class="p-4 rounded-2xl bg-[#1e293b]/50 border border-white/5 flex items-center gap-4 hover:bg-white/5 transition-colors group">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg group-hover:scale-105" style="background-color: rgba(236, 72, 153, 0.2); border: 1px solid rgba(236, 72, 153, 0.2); box-shadow: 0 10px 15px -3px rgba(236, 72, 153, 0.2); color: #f472b6;">
                                <span class="material-symbols-outlined text-[24px]">update</span>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Son İşlem</p>
                                <p class="text-white font-bold text-lg leading-tight">{{ $lead->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="p-4 rounded-2xl bg-[#1e293b]/50 border border-white/5 flex items-center gap-4 hover:bg-white/5 transition-colors group">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg group-hover:scale-105" style="background-color: rgba(6, 182, 212, 0.2); border: 1px solid rgba(6, 182, 212, 0.2); box-shadow: 0 10px 15px -3px rgba(6, 182, 212, 0.2); color: #22d3ee;">
                                <span class="material-symbols-outlined text-[24px]">badge</span>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Temsilci</p>
                                <p class="text-white font-bold text-lg leading-tight">{{ $lead->assignedUser->name ?? 'Atanmadı' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notlar Kartı -->
                    <div class="rounded-2xl bg-[#1e293b]/80 backdrop-blur-xl border border-white/5 shadow-2xl overflow-hidden">
                        <div class="p-6 border-b border-white/5 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">description</span>
                                Notlar & Açıklamalar
                            </h3>
                            <span class="text-xs text-slate-500 italic">Sadece yöneticiler görebilir</span>
                        </div>
                        <div class="p-8">
                            <div class="relative">
                                @if($lead->notes)
                                    <div class="flex gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                                        <div class="flex-none">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-white/10">
                                                {{ substr($lead->assignedUser->name ?? 'S', 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-1 space-y-2">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="text-white font-bold text-sm">{{ $lead->assignedUser->name ?? 'Sistem Hesabı' }}</h4>
                                                    <p class="text-indigo-300 text-xs">Yönetici / Temsilci</p>
                                                </div>
                                                <span class="text-xs text-slate-500 font-mono">{{ $lead->updated_at->format('d.m.Y H:i') }}</span>
                                            </div>
                                            <div class="text-white font-medium text-base leading-relaxed bg-black/30 p-4 rounded-xl border border-white/10 shadow-inner">
                                                {!! nl2br(e($lead->notes)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center py-12 text-slate-500">
                                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-20">note_add</span>
                                        <p>Henüz bir not eklenmemiş.</p>
                                        <a href="{{ route('crm.leads.edit', $lead->id) }}" class="mt-4 text-sm text-primary hover:underline">Düzenle ve Not Ekle</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
