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
                        Kiralama Düzenle
                    </h2>
                    <p class="text-gray-500 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-primary">edit_square</span>
                        #{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }} Nolu Kaydı Güncelle
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 min-h-screen transition-colors duration-300" x-data="{
        daily_price: {{ $rental->daily_price }},
        start_date: '{{ $rental->start_date }}',
        end_date: '{{ $rental->end_date }}',
        get total_days() {
            if (!this.start_date || !this.end_date) return 0;
            const start = new Date(this.start_date);
            const end = new Date(this.end_date);
            const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            return diff > 0 ? diff : 0;
        },
        get total_amount() {
            return (this.total_days * this.daily_price).toLocaleString('tr-TR', { minimumFractionDigits: 2 });
        }
    }">
        <div class="container mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Column: Form -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="p-8 bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 relative overflow-hidden shadow-sm dark:shadow-2xl rounded-3xl">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 right-0 p-8 opacity-[0.02] dark:opacity-[0.05] pointer-events-none">
                            <span class="material-symbols-outlined text-[200px] text-gray-900 dark:text-white rotate-12">contract</span>
                        </div>
                        
                        <form action="{{ route('sales.rentals.update', $rental->id) }}" method="POST" class="space-y-10 relative">
                            @csrf
                            @method('PUT')
                            
                            <!-- Section: Basic Info -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-4 pb-4 border-b border-gray-100 dark:border-white/5">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center border border-blue-100 dark:border-blue-500/20">
                                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-[24px]">group</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">Müşteri ve Ekipman</h3>
                                        <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">Sözleşmenin taraflarını belirleyin</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider ml-1">Müşteri</label>
                                        <div class="relative group">
                                            <select name="contact_id" 
                                                    class="w-full bg-gray-50 dark:bg-[#0f172a] text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 rounded-xl py-4 px-5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none appearance-none font-medium hover:bg-white dark:hover:bg-[#1e293b]" 
                                                    required>
                                                <option value="" class="text-gray-500">Seçiniz...</option>
                                                @foreach($contacts as $contact)
                                                    <option value="{{ $contact->id }}" {{ $rental->contact_id == $contact->id ? 'selected' : '' }}>{{ $contact->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                <span class="material-symbols-outlined text-gray-400 dark:text-slate-500 group-hover:text-blue-500 transition-colors">expand_more</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider ml-1">Ürün / Ekipman</label>
                                        <div class="relative group">
                                            <select name="product_id" 
                                                    class="w-full bg-gray-50 dark:bg-[#0f172a] text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 rounded-xl py-4 px-5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none appearance-none font-medium hover:bg-white dark:hover:bg-[#1e293b]">
                                                <option value="" class="text-gray-500">Seçiniz (Opsiyonel)...</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" {{ $rental->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                <span class="material-symbols-outlined text-gray-400 dark:text-slate-500 group-hover:text-blue-500 transition-colors">expand_more</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Details -->
                            <div class="space-y-6">
                                <div class="flex items-center gap-4 pb-4 border-b border-gray-100 dark:border-white/5">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center border border-emerald-100 dark:border-emerald-500/20">
                                        <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[24px]">calendar_month</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">Süre ve Maliyet</h3>
                                        <p class="text-xs text-gray-500 dark:text-white font-medium">Kiralama detaylarını yapılandırın</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider ml-1">Günlük Bedel</label>
                                        <div class="relative group">
                                            <input type="number" step="0.01" name="daily_price" x-model.number="daily_price"
                                                   class="w-full bg-gray-50 dark:bg-[#0f172a] text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 rounded-xl py-4 pl-5 pr-12 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none font-bold text-lg hover:bg-white dark:hover:bg-[#1e293b]" 
                                                   placeholder="0.00" required>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                <span class="text-emerald-600 dark:text-emerald-400 font-black">₺</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider ml-1">Başlangıç</label>
                                        <input type="date" name="start_date" x-model="start_date"
                                               class="w-full bg-gray-50 dark:bg-[#0f172a] text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 rounded-xl py-4 px-5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none font-medium hover:bg-white dark:hover:bg-[#1e293b]" 
                                               required>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider ml-1">Bitiş</label>
                                        <input type="date" name="end_date" x-model="end_date"
                                               class="w-full bg-gray-50 dark:bg-[#0f172a] text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 rounded-xl py-4 px-5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none font-medium hover:bg-white dark:hover:bg-[#1e293b]"
                                               required>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider ml-1">Durum</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="status" value="active" class="peer sr-only" {{ $rental->status == 'active' ? 'checked' : '' }}>
                                            <div class="p-4 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-[#0f172a] peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-500/20 peer-checked:border-emerald-500 peer-checked:text-emerald-700 dark:peer-checked:text-emerald-400 transition-all text-center">
                                                <div class="font-bold text-sm">Aktif</div>
                                            </div>
                                        </label>
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="status" value="completed" class="peer sr-only" {{ $rental->status == 'completed' ? 'checked' : '' }}>
                                            <div class="p-4 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-[#0f172a] peer-checked:bg-blue-50 dark:peer-checked:bg-blue-500/20 peer-checked:border-blue-500 peer-checked:text-blue-700 dark:peer-checked:text-blue-400 transition-all text-center">
                                                <div class="font-bold text-sm">Tamamlandı</div>
                                            </div>
                                        </label>
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="status" value="overdue" class="peer sr-only" {{ $rental->status == 'overdue' ? 'checked' : '' }}>
                                            <div class="p-4 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-[#0f172a] peer-checked:bg-orange-50 dark:peer-checked:bg-orange-500/20 peer-checked:border-orange-500 peer-checked:text-orange-700 dark:peer-checked:text-orange-400 transition-all text-center">
                                                <div class="font-bold text-sm">Gecikmiş</div>
                                            </div>
                                        </label>
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="status" value="cancelled" class="peer sr-only" {{ $rental->status == 'cancelled' ? 'checked' : '' }}>
                                            <div class="p-4 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-[#0f172a] peer-checked:bg-red-50 dark:peer-checked:bg-red-500/20 peer-checked:border-red-500 peer-checked:text-red-700 dark:peer-checked:text-red-400 transition-all text-center">
                                                <div class="font-bold text-sm">İptal</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Notes -->
                            <div class="space-y-2 pt-4">
                                <label class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider ml-1">Notlar</label>
                                <textarea name="notes" rows="3" 
                                          class="w-full bg-gray-50 dark:bg-[#0f172a] text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 rounded-xl py-4 px-5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none font-medium hover:bg-white dark:hover:bg-[#1e293b]" 
                                          placeholder="Varsa ek açıklamalar...">{{ $rental->notes }}</textarea>
                            </div>

                            <!-- Actions -->
                            <div class="pt-8 flex items-center justify-end gap-4 border-t border-gray-100 dark:border-white/5">
                                <a href="{{ route('sales.rentals.index') }}" 
                                   class="px-8 py-4 rounded-xl border border-gray-200 dark:border-white/10 text-gray-600 dark:text-slate-400 font-bold text-sm uppercase tracking-wider hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    Vazgeç
                                </a>
                                <button type="submit" 
                                        class="px-10 py-4 rounded-xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold text-sm uppercase tracking-wider hover:bg-gray-800 dark:hover:bg-gray-100 shadow-xl shadow-gray-900/10 dark:shadow-white/5 transition-all transform hover:-translate-y-1">
                                    Güncelle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Summary -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="p-6 bg-white dark:bg-[#1e293b]/50 border border-gray-200 dark:border-white/5 sticky top-8 shadow-sm dark:shadow-2xl rounded-3xl">
                        <h3 class="text-xs font-black text-gray-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-6">Özet Tablo</h3>
                        
                        <div class="space-y-4">
                            <!-- Total Price Display -->
                            <div class="p-6 rounded-2xl bg-gray-50 dark:bg-[#0f172a] border border-gray-100 dark:border-white/5 text-center relative overflow-hidden group">
                                <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-transparent to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="text-4xl font-black text-gray-900 dark:text-white mb-1 tracking-tight">
                                    <span class="text-lg text-emerald-500 align-top">₺</span><span x-text="total_amount">0,00</span>
                                </div>
                                <div class="text-[10px] text-gray-500 dark:text-slate-400 font-bold uppercase tracking-widest">Tahmini Toplam Tutar</div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl bg-white dark:bg-[#0f172a] border border-gray-100 dark:border-white/5 shadow-sm dark:shadow-none">
                                    <div class="text-xl font-bold text-gray-900 dark:text-white mb-1" x-text="total_days">0</div>
                                    <div class="text-[10px] text-gray-400 dark:text-slate-500 font-bold uppercase">Gün</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-white dark:bg-[#0f172a] border border-gray-100 dark:border-white/5 shadow-sm dark:shadow-none">
                                    <div class="text-xl font-bold text-gray-900 dark:text-white mb-1">₺<span x-text="daily_price.toLocaleString('tr-TR')">0</span></div>
                                    <div class="text-[10px] text-gray-400 dark:text-slate-500 font-bold uppercase">Günlük</div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-100 dark:border-white/5 space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 dark:text-white font-medium">Kira Başlangıcı</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="start_date || '-'">-</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 dark:text-white font-medium">Kira Bitişi</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="end_date || '-'">-</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 dark:text-white font-medium">Hizmet Bedeli</span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">Dahil</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-500/10">
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm mt-0.5">info</span>
                                <p class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed font-medium">
                                    Fiyat hesaplaması seçilen tarih aralığındaki gün sayısı üzerinden otomatik yapılmaktadır.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
