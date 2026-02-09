<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('crm.contacts.index') }}" class="p-2 rounded-lg bg-white/5 text-slate-400 hover:bg-white/10 transition-all">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <div>
                    <h2 class="font-black text-2xl text-white tracking-tight">{{ $contact->name }}</h2>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                        {{ $contact->type == 'customer' ? 'MÜŞTERİ' : 'TEDARİKÇİ' }} PROFİLİ
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('crm.contacts.edit', $contact->id) }}" class="px-6 py-2 rounded-xl bg-white/5 text-white border border-white/10 font-bold text-sm hover:bg-white/10 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Düzenle
                </a>
                <div class="px-4 py-2 rounded-xl {{ $contact->current_balance < 0 ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-green-500/10 text-green-400 border-green-500/20' }} text-xs font-black uppercase tracking-widest border flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">payments</span>
                    BAKİYE: {{ number_format($contact->current_balance, 2, ',', '.') }} ₺
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto max-w-6xl px-6 space-y-8">
            
            <!-- Bilgi Kartları Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Sol Kolon: İletişim & Adres -->
                <div class="lg:col-span-2 space-y-8">
                    <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl relative overflow-hidden group">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-all duration-700"></div>
                        
                        <div class="relative">
                            <h3 class="text-xl font-black text-white flex items-center gap-3 mb-8">
                                <div class="p-2 rounded-lg bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined">contact_mail</span>
                                </div>
                                Genel Bilgiler
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-2" style="color: #94a3b8 !important;">E-Posta Adresi</p>
                                    <p class="text-white font-bold text-lg" style="color: #ffffff !important;">{{ $contact->email ?: '---' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-2" style="color: #94a3b8 !important;">Telefon</p>
                                    <p class="text-white font-bold text-lg" style="color: #ffffff !important;">{{ $contact->phone ?: '---' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-2" style="color: #94a3b8 !important;">Vergi Dairesi / No</p>
                                    <p class="text-white font-bold text-lg" style="color: #ffffff !important;">
                                        {{ $contact->tax_office ?: '---' }} / {{ $contact->tax_number ?: '---' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-2" style="color: #94a3b8 !important;">Kayıt Tarihi</p>
                                    <p class="text-white font-bold text-lg" style="color: #ffffff !important;">{{ $contact->created_at->format('d.m.Y') }}</p>
                                </div>
                            </div>

                            <div class="mt-10 pt-8 border-t border-white/5">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-4" style="color: #94a3b8 !important;">Adres Bilgisi</p>
                                <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line font-medium">
                                    {{ $contact->address ?: 'Adres bilgisi girilmemiş.' }}
                                </p>
                            </div>
                        </div>
                    </x-card>

                    <!-- Son İşlemler (Placeholder) -->
                    <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-xl font-black text-white flex items-center gap-3 mb-8">
                            <div class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400">
                                <span class="material-symbols-outlined">history</span>
                            </div>
                            Son Hareketler
                        </h3>
                        <div class="p-12 text-center border-2 border-dashed border-white/5 rounded-3xl">
                            <span class="material-symbols-outlined text-4xl text-slate-600 mb-4">analytics</span>
                            <p class="text-slate-500 font-bold uppercase text-[10px] tracking-widest">Henüz işlem bulunmuyor</p>
                        </div>
                    </x-card>
                </div>

                <!-- Sağ Kolon: İstatistikler & Özet -->
                <div class="space-y-6">
                    <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Finansal Özet</h4>
                        <div class="space-y-6">
                            <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Toplam Bakiye</p>
                                <p class="text-2xl font-black {{ $contact->current_balance < 0 ? 'text-red-400' : 'text-green-400' }}">
                                    {{ number_format($contact->current_balance, 2, ',', '.') }} ₺
                                </p>
                            </div>
                        </div>
                    </x-card>

                    <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Hızlı İşlemler</h4>
                        <div class="grid grid-cols-1 gap-3">
                            <a href="#" class="w-full flex items-center justify-between p-4 rounded-2xl bg-white/5 text-white hover:bg-white/10 border border-white/10 transition-all group">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-xl text-primary">add_circle</span>
                                    <span class="text-[11px] font-black uppercase tracking-widest">Tahsilat / Ödeme</span>
                                </div>
                                <span class="material-symbols-outlined text-sm opacity-0 group-hover:translate-x-1 group-hover:opacity-100 transition-all">arrow_forward</span>
                            </a>
                            <a href="#" class="w-full flex items-center justify-between p-4 rounded-2xl bg-white/5 text-white hover:bg-white/10 border border-white/10 transition-all group">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-xl text-blue-400">description</span>
                                    <span class="text-[11px] font-black uppercase tracking-widest">Yeni Teklif</span>
                                </div>
                                <span class="material-symbols-outlined text-sm opacity-0 group-hover:translate-x-1 group-hover:opacity-100 transition-all">arrow_forward</span>
                            </a>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
