<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Yeni İade Kaydı ↩️
                    </h2>
                    <p class="text-slate-400 text-sm font-medium">
                        Yeni bir müşteri satış iadesi işlemi oluşturun.
                    </p>
                </div>
                <a href="{{ route('sales.returns.index') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-slate-800 text-white font-black text-sm uppercase tracking-widest transition-all hover:bg-slate-700">
                    <span class="relative flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                        Geri Dön
                    </span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto max-w-4xl px-6 lg:px-8">
            <x-card class="p-0 border-white/10 bg-white/5 overflow-hidden">
                <form action="{{ route('sales.returns.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-8 space-y-8">
                        <!-- Source Info -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-black text-white flex items-center gap-2 border-b border-white/10 pb-2">
                                <span class="material-symbols-outlined text-primary">receipt_long</span>
                                Kaynak Bilgileri
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">İlgili Fatura</label>
                                    <select name="invoice_id" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-primary/50 focus:ring-0 transition-all font-medium option:bg-slate-900">
                                        <option value="">Fatura Seçiniz...</option>
                                        @foreach($invoices as $invoice)
                                            <option value="{{ $invoice->id }}">
                                                {{ $invoice->invoice_number }} - {{ $invoice->contact->name ?? 'Müşteri Yok' }} (₺{{ number_format($invoice->total_amount, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">İade Tarihi</label>
                                    <input type="date" name="return_date" value="{{ date('Y-m-d') }}" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-primary/50 focus:ring-0 transition-all font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- Return Details -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-black text-white flex items-center gap-2 border-b border-white/10 pb-2">
                                <span class="material-symbols-outlined text-red-400">payments</span>
                                İade Detayları
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">İade Tutarı (₺)</label>
                                    <input type="number" name="total_amount" required step="0.01" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-primary/50 focus:ring-0 transition-all font-medium" placeholder="0.00">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">İade Durumu</label>
                                    <select name="status" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-primary/50 focus:ring-0 transition-all font-medium option:bg-slate-900">
                                        <option value="Pending">Bekliyor</option>
                                        <option value="Approved">Onaylandı</option>
                                        <option value="Refunded">İadesi Yapıldı</option>
                                        <option value="Rejected">Reddedildi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">İade Nedeni</label>
                                <input type="text" name="reason" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-primary/50 focus:ring-0 transition-all font-medium" placeholder="Örn: Hatalı Ürün, Müşteri Vazgeçti vb.">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Notlar</label>
                                <textarea name="notes" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-primary/50 focus:ring-0 transition-all font-medium"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-white/5 border-t border-white/5 flex items-center justify-end gap-4">
                        <a href="{{ route('sales.returns.index') }}" class="px-6 py-3 rounded-xl border border-white/10 text-slate-400 font-bold hover:bg-white/5 transition-all">İptal</a>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-white font-black uppercase tracking-widest hover:bg-primary-600 hover:scale-105 active:scale-95 transition-all shadow-lg shadow-primary/20">
                            İade Kaydını Oluştur
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
