<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        {{ $asset->name }}
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="font-mono text-primary font-bold">{{ $asset->code }}</span>
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        Demirbaş Yönetimi
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('fixedassets.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-bold text-sm transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                        Geri Dön
                    </a>
                    <a href="{{ route('fixedassets.edit', $asset->id) }}" class="px-6 py-2 rounded-xl bg-primary text-gray-900 dark:text-white font-black text-sm uppercase tracking-widest shadow-[0_0_20px_rgba(var(--color-primary),0.3)] hover:scale-105 transition-all">
                        Düzenle
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ activeTab: 'overview' }">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Tabs Navigation -->
            <div class="flex gap-4 border-b border-gray-200 dark:border-white/10 overflow-x-auto pb-1">
                <button @click="activeTab = 'overview'" 
                        :class="activeTab === 'overview' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200'"
                        class="px-4 py-2 text-sm font-bold uppercase tracking-widest border-b-2 transition-all whitespace-nowrap">
                    Genel Bakış
                </button>
                <button @click="activeTab = 'assignment'" 
                        :class="activeTab === 'assignment' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200'"
                        class="px-4 py-2 text-sm font-bold uppercase tracking-widest border-b-2 transition-all whitespace-nowrap">
                    Zimmet Yönetimi
                </button>
                <button @click="activeTab = 'maintenance'" 
                        :class="activeTab === 'maintenance' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200'"
                        class="px-4 py-2 text-sm font-bold uppercase tracking-widest border-b-2 transition-all whitespace-nowrap">
                    Bakım Geçmişi
                </button>
                <button @click="activeTab = 'depreciation'" 
                        :class="activeTab === 'depreciation' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200'"
                        class="px-4 py-2 text-sm font-bold uppercase tracking-widest border-b-2 transition-all whitespace-nowrap">
                    Amortisman
                </button>
            </div>

            <!-- TAB: Overview -->
            <div x-show="activeTab === 'overview'" x-transition class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Details Card -->
                <div class="lg:col-span-2 space-y-8">
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined">info</span>
                            Temel Bilgiler
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Kategori</label>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $asset->category->name ?? 'Genel' }}</div>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Seri Numarası</label>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $asset->serial_number ?? '-' }}</div>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Durum</label>
                                <div class="mt-1">
                                    @php
                                        $statusColors = [
                                            'active' => 'green',
                                            'retired' => 'orange',
                                            'maintenance' => 'blue',
                                            'lost' => 'red',
                                        ];
                                        $statusLabels = [
                                            'active' => 'Aktif',
                                            'retired' => 'Emekli',
                                            'maintenance' => 'Bakımda',
                                            'lost' => 'Kayıp',
                                        ];
                                        $color = $statusColors[$asset->status] ?? 'slate';
                                        $label = $statusLabels[$asset->status] ?? $asset->status;
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-{{ $color }}-500/10 text-{{ $color }}-400 border border-{{ $color }}-500/20">
                                        {{ $label }}
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Eklenme Tarihi</label>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $asset->created_at->format('d.m.Y') }}</div>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Alınış Tarihi</label>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ optional($asset->purchase_date)->format('d.m.Y') ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Değer</label>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($asset->purchase_value, 2, ',', '.') }} ₺</div>
                            </div>
                        </div>

                        @if($asset->description)
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-white/5">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Açıklama</label>
                            <div class="text-sm text-gray-600 dark:text-slate-400 mt-1">{{ $asset->description }}</div>
                        </div>
                        @endif
                    </x-card>
                </div>

                <!-- Current Status Summary -->
                <div class="lg:col-span-1 space-y-6">
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                         <h3 class="text-sm font-black text-gray-900 dark:text-white mb-4 uppercase tracking-widest">Özet</h3>
                         
                         <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-white/5">
                                <span class="text-xs font-bold text-gray-500">Mevcut Sahip</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $asset->currentHolder->name ?? 'Zimmetlenmemiş' }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-white/5">
                                <span class="text-xs font-bold text-gray-500">Son Bakım</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $asset->maintenances()->latest('maintenance_date')->first()->maintenance_date->format('d.m.Y') ?? '-' }}
                                </span>
                            </div>
                             <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-white/5">
                                <span class="text-xs font-bold text-gray-500">Kalan Ömür</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $asset->useful_life_years ? $asset->useful_life_years . ' Yıl' : '-' }}</span>
                            </div>
                         </div>
                    </x-card>
                </div>
            </div>

            <!-- TAB: Assignment (Zimmet) -->
            <div x-show="activeTab === 'assignment'" x-transition class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                 <div class="lg:col-span-1 space-y-8">
                    <!-- Current Holder Card -->
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined">person</span>
                            Mevcut Zimmet
                        </h3>

                        @if($asset->currentHolder)
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-lg">
                                    {{ substr($asset->currentHolder->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $asset->currentHolder->name }}</div>
                                    <div class="text-xs text-slate-500">Zimmet Tarihi: {{ optional($asset->currentAssignment->assigned_at)->format('d.m.Y') }}</div>
                                </div>
                            </div>
                            
                            <form action="{{ route('fixedassets.return', $asset->id) }}" method="POST" class="mt-6">
                                @csrf
                                <button type="submit" class="w-full py-3 bg-red-500/10 text-red-500 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-500/20 transition-all">
                                    Zimmeti İade Al
                                </button>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <span class="material-symbols-outlined text-4xl text-slate-600 mb-2">no_accounts</span>
                                <p class="text-sm text-slate-500">Bu demirbaş şu anda kimseye zimmetli değil.</p>
                            </div>
                            
                            <div class="mt-6" x-data="{ open: true }">
                                <div x-show="open" class="mt-4 p-4 rounded-xl bg-black/20 border border-white/5">
                                    <h4 class="text-xs font-black text-white uppercase tracking-widest mb-4">Yeni Zimmet Ata</h4>
                                    <form action="{{ route('fixedassets.assign', $asset->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 mb-1">Personel Seçin</label>
                                            <select name="employee_id" required class="w-full bg-slate-900 border border-white/10 rounded-lg text-white text-sm px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                                                <option value="">Seçiniz...</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->name }} {{ $employee->surname ?? '' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 mb-1">Zimmet Tarihi</label>
                                            <input type="date" name="assigned_at" value="{{ date('Y-m-d') }}" required 
                                                   class="w-full bg-slate-900 border border-white/10 rounded-lg text-white text-sm px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 mb-1">Notlar</label>
                                            <input type="text" name="notes" placeholder="İsteğe bağlı not..." 
                                                   class="w-full bg-slate-900 border border-white/10 rounded-lg text-white text-sm px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                                        </div>
                                        <x-primary-button type="submit" class="w-full justify-center">
                                            Zimmetle
                                        </x-primary-button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </x-card>
                </div>

                <div class="lg:col-span-2">
                    <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-white/5">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined">history</span>
                                Zimmet Geçmişi
                            </h3>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Personel</th>
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Veriliş Tarihi</th>
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">İade Tarihi</th>
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Notlar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                                    @forelse($asset->assignments()->latest()->get() as $assignment)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                        <td class="p-4">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $assignment->employee->name ?? 'Silinmiş Personel' }}</div>
                                        </td>
                                        <td class="p-4">
                                            <div class="text-sm text-gray-600 dark:text-slate-400 font-mono">{{ $assignment->assigned_at->format('d.m.Y') }}</div>
                                        </td>
                                        <td class="p-4">
                                            @if($assignment->returned_at)
                                                <div class="text-sm text-gray-600 dark:text-slate-400 font-mono">{{ $assignment->returned_at->format('d.m.Y') }}</div>
                                            @else
                                                <span class="px-2 py-1 rounded text-[10px] font-black uppercase bg-green-500/10 text-green-400">Aktif Zimmet</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            <div class="text-sm text-gray-600 dark:text-slate-500">{{ $assignment->notes ?? '-' }}</div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="p-12 text-center text-gray-600 dark:text-slate-500 italic">
                                            Kayıt bulunamadı.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- TAB: Maintenance -->
            <div x-show="activeTab === 'maintenance'" x-transition class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined">build</span>
                            Bakım Kaydı Ekle
                        </h3>

                        <form action="{{ route('fixedassets.maintenance.store', $asset->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1">Bakım Tarihi</label>
                                <input type="date" name="maintenance_date" value="{{ date('Y-m-d') }}" required 
                                       class="w-full bg-slate-900 border border-white/10 rounded-lg text-white text-sm px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1">İşlem Tipi</label>
                                <select name="type" required class="w-full bg-slate-900 border border-white/10 rounded-lg text-white text-sm px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                                    <option value="Routine">Rutin Bakım</option>
                                    <option value="Repair">Onarım</option>
                                    <option value="Upgrade">Yükseltme</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1">Maliyet (₺)</label>
                                <input type="number" step="0.01" name="cost" value="0.00" 
                                       class="w-full bg-slate-900 border border-white/10 rounded-lg text-white text-sm px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1">Yapan Kişi / Firma</label>
                                <input type="text" name="performed_by" placeholder="Örn: Yetkili Servis" 
                                       class="w-full bg-slate-900 border border-white/10 rounded-lg text-white text-sm px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                            </div>
                             <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1">Bir Sonraki Bakım</label>
                                <input type="date" name="next_maintenance_date" 
                                       class="w-full bg-slate-900 border border-white/10 rounded-lg text-white text-sm px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-1">Açıklama</label>
                                <textarea name="description" rows="3" class="w-full bg-slate-900 border border-white/10 rounded-lg text-white text-sm px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
                            </div>
                            <x-primary-button type="submit" class="w-full justify-center">
                                Kaydet
                            </x-primary-button>
                        </form>
                    </x-card>
                </div>

                <div class="lg:col-span-2">
                    <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-white/5">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined">history_toggle_off</span>
                                Bakım Geçmişi
                            </h3>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Tarih</th>
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Tip</th>
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Maliyet</th>
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Yapan</th>
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Açıklama</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                                    @forelse($asset->maintenances()->latest('maintenance_date')->get() as $maintenance)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                        <td class="p-4">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white font-mono">{{ $maintenance->maintenance_date->format('d.m.Y') }}</div>
                                        </td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 rounded text-[10px] font-black uppercase bg-blue-500/10 text-blue-400">{{ $maintenance->type }}</span>
                                        </td>
                                        <td class="p-4">
                                            <div class="text-sm text-gray-900 dark:text-white font-mono">{{ number_format($maintenance->cost, 2) }} ₺</div>
                                        </td>
                                         <td class="p-4">
                                            <div class="text-sm text-gray-600 dark:text-slate-400">{{ $maintenance->performed_by ?? '-' }}</div>
                                        </td>
                                        <td class="p-4">
                                            <div class="text-sm text-gray-600 dark:text-slate-500">{{ $maintenance->description ?? '-' }}</div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="p-12 text-center text-gray-600 dark:text-slate-500 italic">
                                            Henüz bakım kaydı bulunmuyor.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- TAB: Depreciation -->
            <div x-show="activeTab === 'depreciation'" x-transition class="space-y-8">
                 <x-card class="p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">trending_down</span>
                        Amortisman Planı (Yıllık Azalış)
                    </h3>

                    @if($asset->useful_life_years > 0)
                        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5">
                                <span class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Amortisman Yöntemi</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $asset->depreciation_method === 'straight_line' ? 'Eşit Oranlı (Normal)' : $asset->depreciation_method }}</span>
                            </div>
                             <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5">
                                <span class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Faydalı Ömür</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $asset->useful_life_years }} Yıl</span>
                            </div>
                             <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5">
                                <span class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1">Başlangıç Değeri</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($asset->purchase_value, 2) }} ₺</span>
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-white/5">
                             <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Yıl</th>
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">Düşülecek Tutar</th>
                                        <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">Kalan Değer (Defter Değeri)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                                    @foreach($asset->calculateDepreciation() as $entry)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                                        <td class="p-4">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $entry['year'] }}. Yıl</div>
                                        </td>
                                        <td class="p-4 text-right">
                                            <div class="text-sm text-red-400 font-mono">-{{ number_format($entry['depreciation_amount'], 2) }} ₺</div>
                                        </td>
                                        <td class="p-4 text-right">
                                            <div class="text-sm text-gray-900 dark:text-white font-mono font-bold">{{ number_format($entry['book_value'], 2) }} ₺</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/5 border-dashed">
                             <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">calculate</span>
                            <p class="text-gray-600 dark:text-slate-400 font-medium">Amortisman hesaplaması için lütfen demirbaş düzenleme sayfasından "Faydalı Ömür" ve "Değer" bilgisini giriniz.</p>
                            <a href="{{ route('fixedassets.edit', $asset->id) }}" class="inline-block mt-4 px-4 py-2 bg-primary text-gray-900 font-bold rounded-lg text-sm">Düzenle</a>
                        </div>
                    @endif
                 </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
