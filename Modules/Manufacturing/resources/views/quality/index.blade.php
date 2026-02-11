<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight">
                {{ __('Kalite Kontrol (Quality)') }}
            </h2>
            <div class="text-gray-600 dark:text-slate-400 text-sm font-medium">
                {{ now()->translatedFormat('d F Y, l') }}
            </div>
        </div>
    </x-slot>

    <div class="py-10" x-data="{ openModal: false }">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-12 space-y-8">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Total Checks -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-blue-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-blue-500/10 text-blue-500 ring-1 ring-blue-500/20">
                                <span class="material-symbols-outlined text-[24px]">fact_check</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Toplam Kontrol</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $stats['total'] }}</div>
                        <div class="text-blue-400 text-sm mt-4 flex items-center font-bold bg-blue-500/10 w-fit px-3 py-1.5 rounded-lg border border-blue-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">analytics</span>
                            Tüm İşlemler
                        </div>
                    </div>
                </x-card>

                <!-- Pass Rate -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-green-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-green-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-green-500/10 text-green-500 ring-1 ring-green-500/20">
                                <span class="material-symbols-outlined text-[24px]">verified</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Başarı Oranı</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">%{{ $stats['pass_rate'] }}</div>
                        <div class="text-green-400 text-sm mt-4 flex items-center font-bold bg-green-500/10 w-fit px-3 py-1.5 rounded-lg border border-green-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">thumb_up</span>
                            {{ $stats['passed'] }} Başarılı
                        </div>
                    </div>
                </x-card>

                <!-- Failed Checks -->
                <x-card class="p-8 relative overflow-hidden group border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-2xl transition-all duration-500 hover:border-red-500/30 dark:hover:bg-white/10 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-red-500/10 rounded-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-red-500/10 text-red-500 ring-1 ring-red-500/20">
                                <span class="material-symbols-outlined text-[24px]">gpp_bad</span>
                            </div>
                            <div class="text-gray-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest">Reddedilen</div>
                        </div>
                        <div class="text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">{{ $stats['failed'] }}</div>
                        <div class="text-red-400 text-sm mt-4 flex items-center font-bold bg-red-500/10 w-fit px-3 py-1.5 rounded-lg border border-red-500/10">
                            <span class="material-symbols-outlined text-[18px] mr-1.5">warning</span>
                            Aksiyon Gerekli
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- List Section -->
             <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden rounded-[2.5rem] shadow-2xl">
                <div class="p-8 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-gray-50 dark:bg-white/[0.02]">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-[24px]">assignment_turned_in</span>
                        </div>
                        Kalite Kontrol Kayıtları
                    </h3>
                    <button @click="openModal = true" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Yeni Kontrol
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-100 dark:bg-white/5 text-xs uppercase text-gray-700 dark:text-slate-400 font-bold tracking-wider">
                            <tr>
                                <th class="px-8 py-6">Referans No</th>
                                <th class="px-8 py-6">Tarih</th>
                                <th class="px-8 py-6">Ürün</th>
                                <th class="px-8 py-6">Kontrol Tipi</th>
                                <th class="px-8 py-6">Miktar</th>
                                <th class="px-8 py-6">Durum</th>
                                <th class="px-8 py-6 text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($checks as $check)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-8 py-6 font-bold text-gray-900 dark:text-white">{{ $check->reference_number }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-2 text-gray-700 dark:text-slate-300 font-bold">
                                            <span class="material-symbols-outlined text-[18px] text-gray-500 dark:text-slate-500">calendar_today</span>
                                            {{ $check->check_date->format('d.m.Y') }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-gray-900 dark:text-white font-bold">{{ $check->product->name ?? 'Silinmiş Ürün' }}</span>
                                            <span class="text-xs text-gray-500 dark:text-slate-500 font-bold">{{ $check->product->code ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @php
                                            $types = [
                                                'incoming' => 'Giriş Kontrol',
                                                'in_process' => 'Süreç Kontrol',
                                                'final' => 'Son Kontrol',
                                            ];
                                        @endphp
                                        <span class="text-gray-700 dark:text-slate-300 font-medium">{{ $types[$check->type] ?? $check->type }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-gray-700 dark:text-slate-300 font-bold">{{ number_format($check->checked_quantity, 0) }} Kontrol</span>
                                             @if($check->rejected_quantity > 0)
                                                <span class="text-red-400 text-xs font-bold mt-0.5">{{ number_format($check->rejected_quantity, 0) }} Red</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @php
                                            $statusClasses = [
                                                'pass' => 'bg-green-500/10 text-green-500 border-green-500/20',
                                                'fail' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                                'conditional' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                            ];
                                            $statusLabels = [
                                                'pass' => 'Geçti',
                                                'fail' => 'Kaldı',
                                                'conditional' => 'Şartlı',
                                            ];
                                        @endphp
                                        <div class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] uppercase font-black tracking-widest border {{ $statusClasses[$check->status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20' }}">
                                            {{ $statusLabels[$check->status] ?? $check->status }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button class="p-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-8 py-16 text-center">
                                        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="material-symbols-outlined text-4xl text-slate-600">fact_check</span>
                                        </div>
                                        <p class="text-gray-500 dark:text-slate-500 font-medium text-lg">Henüz kalite kontrol kaydı bulunmuyor.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 border-t border-gray-200 dark:border-white/5">
                    {{ $checks->links() }}
                </div>
            </x-card>
        </div>

        <!-- Create Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="openModal = false"></div>
            
            <div class="relative bg-white dark:bg-[#1e1e2d] rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-gray-200 dark:border-white/10 transform transition-all"
                 x-show="openModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                
                <div class="px-8 py-6 border-b border-gray-200 dark:border-white/10 flex justify-between items-center bg-gray-50 dark:bg-white/[0.02]">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white">Yeni Kalite Kontrol Kaydı</h3>
                    <button @click="openModal = false" class="text-gray-500 dark:text-slate-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form action="{{ route('manufacturing.quality.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product -->
                        <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Ürün</label>
                            <select name="product_id" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                                <option value="" class="bg-[#1e1e2d]">Ürün Seçiniz...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" class="bg-[#1e1e2d]">{{ $product->code }} - {{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Check Date -->
                        <div>
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Kontrol Tarihi</label>
                            <input type="date" name="check_date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                        </div>

                        <!-- Type -->
                        <div>
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Kontrol Tipi</label>
                            <select name="type" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                                <option value="incoming" class="bg-[#1e1e2d]">Giriş Kontrol</option>
                                <option value="in_process" class="bg-[#1e1e2d]">Süreç Kontrol</option>
                                <option value="final" class="bg-[#1e1e2d]">Son Kontrol</option>
                            </select>
                        </div>

                        <!-- Quantities -->
                        <div>
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Kontrol Edilen Miktar</label>
                            <input type="number" name="checked_quantity" step="0.01" min="0" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" required>
                        </div>
                        <div>
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Reddedilen Miktar</label>
                            <input type="number" name="rejected_quantity" step="0.01" min="0" value="0" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4">
                        </div>
                        
                         <!-- Status -->
                        <div class="md:col-span-2">
                              <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Karar</label>
                             <div class="flex gap-4">
                                 <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-white/[0.02] hover:bg-gray-100 dark:hover:bg-white/5 transition-colors flex-1 group">
                                     <input type="radio" name="status" value="pass" class="text-green-500 focus:ring-green-500 bg-black/20 border-white/20" checked>
                                     <span class="font-bold text-green-400 group-hover:text-green-300">Geçti</span>
                                 </label>
                                 <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-white/[0.02] hover:bg-gray-100 dark:hover:bg-white/5 transition-colors flex-1 group">
                                     <input type="radio" name="status" value="fail" class="text-red-500 focus:ring-red-500 bg-black/20 border-white/20">
                                     <span class="font-bold text-red-400 group-hover:text-red-300">Kaldı (Red)</span>
                                 </label>
                                 <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-white/[0.02] hover:bg-gray-100 dark:hover:bg-white/5 transition-colors flex-1 group">
                                     <input type="radio" name="status" value="conditional" class="text-yellow-500 focus:ring-yellow-500 bg-black/20 border-white/20">
                                     <span class="font-bold text-yellow-500 group-hover:text-yellow-400">Şartlı Kabûl</span>
                                 </label>
                             </div>
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                             <label class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Notlar / Hata Açıklaması</label>
                            <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:border-primary focus:ring-primary shadow-sm py-3 px-4" placeholder="Varsa hata detayları veya notlar..."></textarea>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end gap-4 border-t border-gray-200 dark:border-white/10">
                        <button type="button" @click="openModal = false" class="px-6 py-3 rounded-xl text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 font-bold transition-colors">İptal</button>
                        <button type="submit" class="px-8 py-3 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold shadow-lg shadow-primary/20 transition-all transform hover:-translate-y-1">
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
