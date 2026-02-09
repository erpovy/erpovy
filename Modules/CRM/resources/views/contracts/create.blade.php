<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Yeni Sözleşme 📝
                    </h2>
                    <p class="text-slate-400 text-sm font-medium">
                        Yeni bir müşteri sözleşmesi veya anlaşması oluşturun.
                    </p>
                </div>
                <a href="{{ route('crm.contracts.index') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-slate-800 text-white font-black text-sm uppercase tracking-widest transition-all hover:bg-slate-700">
                    <span class="relative flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                        Geri Dön
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-4xl px-6 lg:px-8">
            <x-card class="p-0 border-white/10 bg-white/5 overflow-hidden">
                <form action="{{ route('crm.contracts.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-8 space-y-8">
                        <!-- Basic Info Section -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-black text-white flex items-center gap-2 border-b border-white/10 pb-2">
                                <span class="material-symbols-outlined text-primary">info</span>
                                Temel Bilgiler
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Sözleşme Konusu</label>
                                    <input type="text" name="subject" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-primary/50 focus:ring-0 transition-all font-medium" placeholder="Örn: Yıllık Bakım Anlaşması">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Müşteri Seçimi</label>
                                    <select name="contact_id" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-primary/50 focus:ring-0 transition-all font-medium option:bg-slate-900">
                                        <option value="">Seçiniz...</option>
                                        @foreach($contacts as $contact)
                                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">İlgili Fırsat (Opsiyonel)</label>
                                    <select name="deal_id" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-primary/50 focus:ring-0 transition-all font-medium option:bg-slate-900">
                                        <option value="">Bağımsız Sözleşme</option>
                                        @foreach($deals as $deal)
                                            <option value="{{ $deal->id }}">{{ $deal->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Terms & Values -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-black text-white flex items-center gap-2 border-b border-white/10 pb-2">
                                <span class="material-symbols-outlined text-green-400">payments</span>
                                Değer ve Süre
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Sözleşme Değeri (₺)</label>
                                    <input type="number" name="value" required step="0.01" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-primary/50 focus:ring-0 transition-all font-medium" placeholder="0.00">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Başlangıç Tarihi</label>
                                    <input type="date" name="start_date" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-primary/50 focus:ring-0 transition-all font-medium">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Bitiş Tarihi</label>
                                    <input type="date" name="end_date" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-primary/50 focus:ring-0 transition-all font-medium">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Durum</label>
                                <select name="status" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-primary/50 focus:ring-0 transition-all font-medium option:bg-slate-900">
                                    <option value="Draft">Taslak</option>
                                    <option value="Active">Aktif</option>
                                    <option value="Sent">Gönderildi</option>
                                </select>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-black text-white flex items-center gap-2 border-b border-white/10 pb-2">
                                <span class="material-symbols-outlined text-purple-400">description</span>
                                Detaylar
                            </h3>

                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Açıklama / Notlar</label>
                                <textarea name="description" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-primary/50 focus:ring-0 transition-all font-medium"></textarea>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Sözleşme İçeriği (HTML/Metin)</label>
                                <textarea name="content" rows="10" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-primary/50 focus:ring-0 transition-all font-mono text-sm"></textarea>
                                <p class="text-xs text-slate-500">HTML formatında sözleşme metni girebilirsiniz.</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-white/5 border-t border-white/5 flex items-center justify-end gap-4">
                        <a href="{{ route('crm.contracts.index') }}" class="px-6 py-3 rounded-xl border border-white/10 text-slate-400 font-bold hover:bg-white/5 transition-all">İptal</a>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-white font-black uppercase tracking-widest hover:bg-primary-600 hover:scale-105 active:scale-95 transition-all shadow-lg shadow-primary/20">
                            Sözleşmeyi Oluştur
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
