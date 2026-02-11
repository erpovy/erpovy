<x-app-layout>
    <x-slot name="header">
        Yeni İş Emri Oluştur
    </x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-[32px]">precision_manufacturing</span>
                    Yeni İş Emri
                </h1>
                <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">Üretim sürecini başlatmak için iş emri detaylarını girin.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('manufacturing.index') }}" 
                   class="px-4 py-2 rounded-xl border border-gray-300 dark:border-white/10 text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 transition-all font-bold text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">list</span>
                    İş Emirleri Listesi
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8">
            <x-card class="p-8 relative overflow-hidden border-2 border-gray-100 dark:border-white/5 shadow-glass">
                <!-- Decorative Blur -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/10 rounded-full blur-3xl -z-10"></div>
                
                <form action="{{ route('manufacturing.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-8">
                        <!-- Product & Personnel Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                                    Üretilecek Ürün
                                </label>
                                <div class="relative">
                                    <select name="product_id" class="w-full pl-4 pr-10 py-3 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer font-medium transition-all" required>
                                        <option value="" disabled selected>Üretilecek ürünü seçiniz...</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" class="bg-white dark:bg-slate-900">{{ $product->code }} - {{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 dark:text-slate-500 text-[20px] pointer-events-none">expand_more</span>
                                </div>
                                @error('product_id')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1 font-medium">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">person</span>
                                    Sorumlu Personel
                                </label>
                                <div class="relative">
                                    <select name="employee_id" class="w-full pl-4 pr-10 py-3 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 appearance-none cursor-pointer font-medium transition-all">
                                        <option value="" selected>Personel Seçiniz (Opsiyonel)</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" class="bg-white dark:bg-slate-900">{{ $employee->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 dark:text-slate-500 text-[20px] pointer-events-none">expand_more</span>
                                </div>
                                @error('employee_id')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1 font-medium">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Quantity & Dates Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">format_list_numbered</span>
                                    Miktar
                                </label>
                                <input type="number" name="quantity" min="1" placeholder="0" 
                                       class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-mono font-bold" required>
                                @error('quantity')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1 font-medium">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">play_circle</span>
                                    Başlangıç Tarihi
                                </label>
                                <input type="date" name="start_date" value="{{ date('Y-m-d') }}" 
                                       class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium">
                                @error('start_date')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1 font-medium">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">event_available</span>
                                    Teslim Tarihi
                                </label>
                                <input type="date" name="due_date" 
                                       class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium">
                                @error('due_date')
                                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1 font-medium">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes Area -->
                        <div class="pt-4">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">notes</span>
                                Notlar ve Özel Talimatlar
                            </label>
                            <textarea name="notes" rows="4" placeholder="Üretim süreci ile ilgili eklemek istediğiniz notlar..." 
                                      class="w-full px-4 py-4 rounded-2xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm font-medium placeholder-gray-400 dark:placeholder-slate-500"></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-8 border-t border-gray-100 dark:border-white/5">
                            <a href="{{ route('manufacturing.index') }}" 
                               class="px-8 py-3 rounded-xl border border-gray-300 dark:border-white/10 text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-white/5 transition-all font-bold text-sm">
                                Vazgeç
                            </a>
                            <button type="submit" 
                                    class="flex items-center gap-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-10 py-3 rounded-xl hover:shadow-[0_20px_40px_rgba(37,99,235,0.3)] hover:scale-[1.02] active:scale-[0.98] transition-all font-bold shadow-xl shadow-blue-500/20">
                                <span class="material-symbols-outlined text-[22px]">rocket_launch</span>
                                İş Emrini Başlat
                            </button>
                        </div>
                    </div>
                </form>
            </x-card>

            <!-- Quick Tips / Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 rounded-2xl bg-blue-50 dark:bg-primary/5 border border-blue-100 dark:border-primary/20 flex gap-4">
                    <span class="material-symbols-outlined text-primary text-[24px] shrink-0">info</span>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Maliyet Otomasyonu</h4>
                        <p class="text-xs text-gray-600 dark:text-slate-400 leading-relaxed">İş emri tamamlandığında, ürün reçetesine göre kullanılan hammadde maliyetleri otomatik olarak hesaplanır ve stoklardan düşülür.</p>
                    </div>
                </div>
                <div class="p-6 rounded-2xl bg-amber-50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/20 flex gap-4">
                    <span class="material-symbols-outlined text-amber-600 text-[24px] shrink-0">warning</span>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Teslim Tarihi Hatırlatıcı</h4>
                        <p class="text-xs text-gray-600 dark:text-slate-400 leading-relaxed">Teslim tarihi belirtmeniz durumunda, sistem üretim planlamasında bu emri önceliklendirir ve gecikme uyarıları oluşturur.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .shadow-glass {
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
</x-app-layout>
