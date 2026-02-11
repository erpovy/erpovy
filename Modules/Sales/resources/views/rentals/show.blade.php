<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-100 via-gray-50 to-gray-100 dark:from-primary/5 dark:via-purple-500/5 dark:to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center gap-6 py-4">
                <a href="{{ route('sales.rentals.index') }}" 
                   class="flex items-center justify-center w-12 h-12 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/10 hover:border-gray-300 dark:hover:border-primary/30 transition-all group/back shadow-sm dark:shadow-none">
                    <span class="material-symbols-outlined text-[24px] group-hover/back:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1 font-display">
                        Kiralama Detayı
                    </h2>
                    <p class="text-gray-500 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-primary">visibility</span>
                        #{{ $rental->id }} Nolu Sözleşme Bilgileri
                    </p>
                </div>
                <div class="ml-auto flex items-center gap-3">
                    <form action="{{ route('sales.rentals.destroy', $rental->id) }}" method="POST" onsubmit="return confirm('Bu kaydı silmek istediğinize emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center gap-2 px-5 py-3 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold text-xs uppercase tracking-widest hover:bg-red-100 dark:hover:bg-red-500/20 transition-all">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            <span class="hidden sm:inline">Sil</span>
                        </button>
                    </form>
                    <a href="{{ route('sales.rentals.edit', $rental->id) }}" class="flex items-center gap-2 px-6 py-3 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold text-xs uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-200 shadow-xl shadow-gray-900/10 dark:shadow-white/5 transition-all transform hover:-translate-y-1">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        <span class="hidden sm:inline">Düzenle</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 min-h-screen transition-colors duration-300">
        <div class="container mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Column: Main Info -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="p-8 bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 relative overflow-hidden shadow-sm dark:shadow-2xl rounded-3xl">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 right-0 p-8 opacity-[0.02] dark:opacity-[0.05] pointer-events-none">
                            <span class="material-symbols-outlined text-[200px] text-gray-900 dark:text-white rotate-12">contract</span>
                        </div>
                        
                        <div class="space-y-8 relative">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center border border-gray-200 dark:border-white/10">
                                        <span class="material-symbols-outlined text-gray-500 dark:text-white text-[32px]">description</span>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-1">Sözleşme No</div>
                                        <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">RNT-{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</h1>
                                    </div>
                                </div>
                                @php
                                    $statusClasses = [
                                        'active' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/20',
                                        'completed' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-500/20 dark:text-blue-400 dark:border-blue-500/20',
                                        'overdue' => 'bg-orange-100 text-orange-700 border-orange-200 dark:bg-orange-500/20 dark:text-orange-400 dark:border-orange-500/20',
                                        'cancelled' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-500/20 dark:text-red-400 dark:border-red-500/20',
                                    ];
                                    $statusLabels = [
                                        'active' => 'Aktif',
                                        'completed' => 'Tamamlandı',
                                        'overdue' => 'Gecikmiş',
                                        'cancelled' => 'İptal Edildi',
                                    ];
                                    $currentStatus = $rental->status ?? 'active';
                                @endphp
                                <div class="px-5 py-2 rounded-full border {{ $statusClasses[$currentStatus] ?? $statusClasses['active'] }} font-bold text-xs uppercase tracking-widest shadow-sm">
                                    {{ $statusLabels[$currentStatus] ?? 'Bilinmiyor' }}
                                </div>
                            </div>

                            <hr class="border-gray-100 dark:border-white/5">

                            <!-- Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Müşteri</label>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">
                                            {{ strtoupper(substr($rental->contact->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $rental->contact->name ?? 'Silinmiş Müşteri' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-slate-400">{{ $rental->contact->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Ürün / Ekipman</label>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-400">
                                            <span class="material-symbols-outlined text-sm">inventory_2</span>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $rental->product->name ?? 'Belirtilmemiş' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-slate-400">Stok Kodu: {{ $rental->product->code ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Date & Financials -->
                            <div class="p-6 rounded-2xl bg-gray-50 dark:bg-[#0f172a] border border-gray-100 dark:border-white/5">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-1">Başlangıç</div>
                                        <div class="flex items-center gap-2 text-gray-900 dark:text-white font-bold">
                                            <span class="material-symbols-outlined text-gray-400 text-sm">event</span>
                                            {{ \Carbon\Carbon::parse($rental->start_date)->format('d.m.Y') }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-1">Bitiş</div>
                                        <div class="flex items-center gap-2 text-gray-900 dark:text-white font-bold">
                                            <span class="material-symbols-outlined text-gray-400 text-sm">event_busy</span>
                                            {{ $rental->end_date ? \Carbon\Carbon::parse($rental->end_date)->format('d.m.Y') : 'Devam Ediyor' }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-1">Günlük Bedel</div>
                                        <div class="text-xl font-black text-gray-900 dark:text-white">
                                            ₺{{ number_format($rental->daily_price, 2, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($rental->notes)
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Notlar</label>
                                <div class="p-4 rounded-xl bg-gray-50 dark:bg-[#0f172a] border border-gray-100 dark:border-white/5 text-gray-700 dark:text-slate-300 text-sm leading-relaxed">
                                    {{ $rental->notes }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Stats & Actions -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="p-6 bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 sticky top-8 shadow-sm dark:shadow-2xl rounded-3xl">
                        <h3 class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-6">Finansal Özet</h3>
                        
                        @php
                            $startDate = \Carbon\Carbon::parse($rental->start_date);
                            $endDate = $rental->end_date ? \Carbon\Carbon::parse($rental->end_date) : \Carbon\Carbon::now();
                            $days = $startDate->diffInDays($endDate);
                            $days = $days > 0 ? $days : 1; // En az 1 gün
                            $totalAmount = $days * $rental->daily_price;
                        @endphp

                        <div class="space-y-4">
                            <!-- Total Price Display -->
                            <div class="p-6 rounded-2xl bg-gray-50 dark:bg-[#0f172a] border border-gray-100 dark:border-white/5 text-center relative overflow-hidden group">
                                <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-transparent to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="text-4xl font-black text-gray-900 dark:text-white mb-1 tracking-tight">
                                    <span class="text-lg text-blue-500 align-top">₺</span>{{ number_format($totalAmount, 2, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-gray-500 dark:text-slate-400 font-bold uppercase tracking-widest">Tahmini Toplam Tutar</div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl bg-white dark:bg-[#0f172a] border border-gray-100 dark:border-white/5 shadow-sm dark:shadow-none text-center">
                                    <div class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $days }}</div>
                                    <div class="text-[10px] text-gray-400 dark:text-slate-500 font-bold uppercase">Gün</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-white dark:bg-[#0f172a] border border-gray-100 dark:border-white/5 shadow-sm dark:shadow-none text-center">
                                    <div class="text-xl font-bold text-gray-900 dark:text-white mb-1">₺{{ number_format($rental->daily_price, 0) }}</div>
                                    <div class="text-[10px] text-gray-400 dark:text-slate-500 font-bold uppercase">Günlük</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-white/5">
                            <h4 class="text-[10px] font-black text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-4">Hızlı İşlemler</h4>
                            <div class="space-y-3">
                                <button class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group text-left">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-sm">print</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300">Sözleşmeyi Yazdır</span>
                                </button>
                                <button class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group text-left">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-sm">mail</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300">E-posta Gönder</span>
                                </button>
                                <button class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group text-left">
                                    <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-sm">receipt_long</span>
                                    </div>
                                    <span class="text-sm font-bold text-gray-700 dark:text-slate-300">Fatura Oluştur</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
