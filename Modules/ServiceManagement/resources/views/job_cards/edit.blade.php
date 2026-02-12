<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-cyan-500/5 to-sky-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div class="flex items-center gap-4">
                    <a href="{{ route('servicemanagement.job-cards.index') }}" class="p-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 hover:bg-blue-500 hover:text-white transition-all">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    </a>
                    <div>
                        <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1 flex items-center gap-3">
                            {{ $jobCard->job_number }}
                            <span class="px-3 py-1 text-xs rounded-full bg-slate-500/10 text-slate-500 border border-slate-500/20">
                                {{ $jobCard->status }}
                            </span>
                        </h2>
                        <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">directions_car</span>
                            {{ $jobCard->vehicle->plate_number }} - {{ $jobCard->vehicle->brand }} {{ $jobCard->vehicle->model }}
                            <span class="text-slate-300">|</span>
                            <span class="material-symbols-outlined text-[16px]">person</span>
                            {{ $jobCard->customer ? $jobCard->customer->name : 'Dahili' }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <button onclick="window.print()" class="group flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-bold text-xs uppercase tracking-widest transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                        <span class="material-symbols-outlined text-[18px]">print</span>
                        YAZDIR
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT COLUMN: Job Details -->
                <div class="lg:col-span-1 space-y-8">
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-500">info</span>
                            İŞ BİLGİLERİ
                        </h3>

                        <form action="{{ route('servicemanagement.job-cards.update', $jobCard->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">DURUM</label>
                                    <select name="status" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">
                                        @foreach(['pending', 'diagnosing', 'waiting_parts', 'in_progress', 'completed', 'invoiced', 'cancelled'] as $status)
                                            <option value="{{ $status }}" {{ $jobCard->status == $status ? 'selected' : '' }}>
                                                {{ strtoupper(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">ÖNCELİK</label>
                                    <select name="priority" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">
                                        @foreach(['low', 'normal', 'high', 'urgent'] as $priority)
                                            <option value="{{ $priority }}" {{ $jobCard->priority == $priority ? 'selected' : '' }}>
                                                {{ strtoupper($priority) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">TEŞHİS / RAPOR</label>
                                    <textarea name="diagnosis" rows="8" placeholder="Tekniker teşhisi ve yapılan işlemler..."
                                        class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">{{ $jobCard->diagnosis }}</textarea>
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest pl-1">DAHİLİ NOTLAR</label>
                                    <textarea name="internal_notes" rows="4" placeholder="Sadece şirket içi görünen notlar..."
                                        class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all dark:text-white">{{ $jobCard->internal_notes }}</textarea>
                                </div>

                                <button type="submit" class="w-full py-3 bg-blue-500 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-blue-500/20">
                                    GÜNCELLE
                                </button>
                            </div>
                        </form>
                    </x-card>

                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-rose-500">report_problem</span>
                            MÜŞTERİ ŞİKAYETİ
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 bg-gray-50 dark:bg-white/5 p-4 rounded-xl italic">
                            "{{ $jobCard->customer_complaint }}"
                        </p>
                    </x-card>
                </div>

                <!-- RIGHT COLUMN: Service Items -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Items List -->
                    <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-white/5 flex items-center justify-between">
                            <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest flex items-center gap-2">
                                <span class="material-symbols-outlined text-emerald-500">shopping_cart</span>
                                PARÇA VE İŞÇİLİK
                            </h3>
                            <div class="text-right">
                                <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">TOPLAM TUTAR</span>
                                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono">{{ number_format($jobCard->total_amount, 2, ',', '.') }} TL</span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-200 dark:border-white/5 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Tür</th>
                                        <th class="px-6 py-4">Açıklama</th>
                                        <th class="px-6 py-4 text-center">Miktar</th>
                                        <th class="px-6 py-4 text-right">Birim Fiyat</th>
                                        <th class="px-6 py-4 text-right">Toplam</th>
                                        <th class="px-6 py-4 text-right">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                                    @foreach($jobCard->items as $item)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/5 transition-colors">
                                            <td class="px-6 py-4">
                                                @if($item->type == 'part')
                                                    <span class="px-2 py-1 text-[10px] font-black uppercase tracking-tight rounded bg-blue-500/10 text-blue-500">PARÇA</span>
                                                @else
                                                    <span class="px-2 py-1 text-[10px] font-black uppercase tracking-tight rounded bg-orange-500/10 text-orange-500">İŞÇİLİK</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-sm font-bold text-gray-900 dark:text-white block">{{ $item->name }}</span>
                                                @if($item->product)
                                                    <span class="text-[10px] text-slate-500 block">{{ $item->product->code }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center font-mono text-sm">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-mono text-sm">
                                                {{ number_format($item->unit_price, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-mono text-sm font-bold text-gray-900 dark:text-white">
                                                {{ number_format($item->total_price, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <form action="{{ route('servicemanagement.job-cards.remove-item', [$jobCard->id, $item->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:bg-rose-500/10 p-1 rounded transition-colors">
                                                        <span class="material-symbols-outlined text-[18px]">close</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-card>

                    <!-- Add Item Form -->
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h4 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-widest mb-4">Yeni Kalem Ekle</h4>
                        <form action="{{ route('servicemanagement.job-cards.add-item', $jobCard->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            @csrf
                            
                            <div class="md:col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase">TÜR</label>
                                <select name="type" class="w-full px-3 py-2 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg text-sm outline-none focus:border-blue-500 dark:text-white">
                                    <option value="part">Parça</option>
                                    <option value="labor">İşçilik</option>
                                    <option value="service" selected>Hizmet</option>
                                </select>
                            </div>

                            <div class="md:col-span-4 space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase">AÇIKLAMA / ÜRÜN</label>
                                <div class="relative">
                                    <input type="text" name="name" placeholder="Örn: Yağ Değişimi" 
                                        class="w-full px-3 py-2 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg text-sm outline-none focus:border-blue-500 dark:text-white">
                                    <!-- Here we could add a product search dropdown later -->
                                </div>
                            </div>

                            <div class="md:col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase">MİKTAR</label>
                                <input type="number" name="quantity" value="1" step="0.01"
                                    class="w-full px-3 py-2 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg text-sm outline-none focus:border-blue-500 dark:text-white">
                            </div>

                            <div class="md:col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase">BİRİM FİYAT</label>
                                <input type="number" name="unit_price" value="0" step="0.01"
                                    class="w-full px-3 py-2 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-lg text-sm outline-none focus:border-blue-500 dark:text-white">
                            </div>

                            <div class="md:col-span-2">
                                <button type="submit" class="w-full py-2 bg-emerald-500 text-white font-bold text-xs uppercase rounded-lg hover:bg-emerald-600 transition-colors">
                                    EKLE
                                </button>
                            </div>
                        </form>
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
