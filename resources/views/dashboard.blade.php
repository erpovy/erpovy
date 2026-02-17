<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        {{ __('Hoş Geldiniz') }}, {{ auth()->user()->name }}! 👋
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                        {{ now()->translatedFormat('d F Y, l') }}
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>
                
                
                <!-- Weather Widget -->

                
                <!-- Quick Mini Stats -->
                <div class="hidden lg:flex items-center gap-3">
                    @if(auth()->user()->hasModuleAccess('accounting.dashboard'))
                    <div class="px-4 py-2 rounded-xl bg-white/5 border border-gray-200 dark:border-white/10 backdrop-blur-md flex flex-col items-end">
                        <span class="text-[10px] text-gray-600 dark:text-slate-500 uppercase tracking-wider font-bold">Bu Ay Ciro</span>
                        <span class="text-lg font-black text-primary leading-none">+{{ number_format($monthlyRevenue, 0) }}₺</span>
                    </div>
                    @endif
                    @if(auth()->user()->hasModuleAccess('accounting.invoices'))
                    <div class="px-4 py-2 rounded-xl bg-white/5 border border-gray-200 dark:border-white/10 backdrop-blur-md flex flex-col items-end">
                        <span class="text-[10px] text-gray-600 dark:text-slate-500 uppercase tracking-wider font-bold">Bekleyen Fatura</span>
                        <span class="text-lg font-black text-yellow-400 leading-none">{{ $pendingInvoicesCount }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Row 1: Stat Cards (4 Cols) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat 1 -->
                @if(auth()->user()->hasModuleAccess('accounting.dashboard'))
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-blue-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-primary/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined text-[20px]">payments</span>
                                </div>
                                <div class="text-green-400 text-xs font-bold bg-green-900/30 px-2 py-1 rounded-lg border border-green-500/30 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">trending_up</span>+15%
                                </div>
                            </div>
                            <div class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-1">₺{{ number_format($monthlyRevenue, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Aylık Toplam Gelir</div>
                        </div>
                        <div class="h-8 flex items-end gap-1 mt-4 opacity-50 group-hover:opacity-100 transition-opacity">
                            <div class="flex-1 bg-primary/50 rounded-t-sm" style="height: 40%"></div>
                            <div class="flex-1 bg-primary/50 rounded-t-sm" style="height: 60%"></div>
                            <div class="flex-1 bg-primary/50 rounded-t-sm" style="height: 45%"></div>
                            <div class="flex-1 bg-primary/50 rounded-t-sm" style="height: 75%"></div>
                            <div class="flex-1 bg-primary/50 rounded-t-sm" style="height: 55%"></div>
                            <div class="flex-1 bg-primary/50 rounded-t-sm" style="height: 90%"></div>
                            <div class="flex-1 bg-primary rounded-t-sm" style="height: 100%"></div>
                        </div>
                    </x-card>
                </div>
                @endif

                <!-- Stat 2 -->
                @if(auth()->user()->hasModuleAccess('accounting.invoices'))
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-yellow-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-yellow-500/10 text-yellow-500">
                                    <span class="material-symbols-outlined text-[20px]">hourglass_empty</span>
                                </div>
                                <div class="text-yellow-400 text-xs font-bold bg-yellow-900/30 px-2 py-1 rounded-lg border border-yellow-500/30 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">pending</span>Acil
                                </div>
                            </div>
                            <div class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ $pendingInvoicesCount }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Bekleyen Tahsilat Adedi</div>
                        </div>
                        <div class="h-8 flex items-end gap-1 mt-4 opacity-50 group-hover:opacity-100 transition-opacity">
                            <div class="flex-1 bg-yellow-500/50 rounded-t-sm" style="height: 50%"></div>
                            <div class="flex-1 bg-yellow-500/50 rounded-t-sm" style="height: 30%"></div>
                            <div class="flex-1 bg-yellow-500/50 rounded-t-sm" style="height: 70%"></div>
                            <div class="flex-1 bg-yellow-500 rounded-t-sm" style="height: 40%"></div>
                        </div>
                    </x-card>
                </div>
                @endif

                <!-- Stat 3 -->
                @if(auth()->user()->hasModuleAccess('inventory.products'))
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/20 to-pink-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-red-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-red-500/10 text-red-500">
                                    <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                                </div>
                                <div class="text-red-400 text-xs font-bold bg-red-900/30 px-2 py-1 rounded-lg border border-red-500/30 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">warning</span>Kritik
                                </div>
                            </div>
                            <div class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ $lowStockProducts }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">Stoktaki Kritik Ürün</div>
                        </div>
                        <div class="h-8 flex items-end gap-1 mt-4 opacity-50 group-hover:opacity-100 transition-opacity">
                            <div class="flex-1 bg-red-500/50 rounded-t-sm" style="height: 30%"></div>
                            <div class="flex-1 bg-red-500/50 rounded-t-sm" style="height: 20%"></div>
                            <div class="flex-1 bg-red-500/50 rounded-t-sm" style="height: 40%"></div>
                            <div class="flex-1 bg-red-500 rounded-t-sm" style="height: 25%"></div>
                        </div>
                    </x-card>
                </div>
                @endif

                <!-- Stat 4 -->
                @if(auth()->user()->hasModuleAccess('crm.contacts'))
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-fuchsia-500/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-purple-500/30 transition-all duration-300 group-hover:-translate-y-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-2 rounded-xl bg-purple-500/10 text-purple-500">
                                    <span class="material-symbols-outlined text-[20px]">group</span>
                                </div>
                                <div class="text-purple-400 text-xs font-bold bg-purple-900/30 px-2 py-1 rounded-lg border border-purple-500/30 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">trending_up</span>+8%
                                </div>
                            </div>
                            <div class="text-4xl font-black text-gray-900 dark:text-white tracking-tight mb-1">{{ $totalContacts }}</div>
                            <div class="text-xs text-gray-600 dark:text-slate-500 font-medium">CRM Rehber Kaydı</div>
                        </div>
                        <div class="h-8 flex items-end gap-1 mt-4 opacity-50 group-hover:opacity-100 transition-opacity">
                            <div class="flex-1 bg-purple-500/50 rounded-t-sm" style="height: 50%"></div>
                            <div class="flex-1 bg-purple-500/50 rounded-t-sm" style="height: 60%"></div>
                            <div class="flex-1 bg-purple-500/50 rounded-t-sm" style="height: 70%"></div>
                            <div class="flex-1 bg-purple-500 rounded-t-sm" style="height: 80%"></div>
                        </div>
                    </x-card>
                </div>
                @endif
            </div>

            <!-- Row 2: Charts (2 Cols/3 Spans) & Timeline (1 Col/3 Spans) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Revenue Chart (Spans 2) -->
                @if(auth()->user()->hasModuleAccess('accounting.dashboard'))
                <div class="lg:col-span-2">
                    <x-card class="h-80 p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl flex flex-col">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">trending_up</span>
                                    Gelir Trendi
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-slate-500">Son 6 aylık performans analizi</p>
                            </div>
                            <div class="px-3 py-1 bg-white/5 rounded-lg border border-gray-200 dark:border-white/10 text-xs font-bold text-gray-600 dark:text-slate-400">
                                6 Aylık
                            </div>
                        </div>
                        
                        <!-- Chart Area -->
                        <div class="flex-1 flex items-end justify-between gap-6 px-4">
                            @foreach($chartData as $data)
                                <div class="flex-1 group cursor-pointer flex flex-col justify-end h-full">
                                    <div class="relative w-full rounded-t-xl transition-all duration-500 group-hover:opacity-100 opacity-80
                                        {{ $loop->last ? 'bg-gradient-to-t from-green-500 to-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.3)]' : 'bg-gradient-to-t from-primary/40 to-primary/80 group-hover:from-primary/60 group-hover:to-primary' }}" 
                                        style="height: {{ $data['percentage'] }}%">
                                        
                                        <!-- Tooltip -->
                                        <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-gray-900 dark:text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap border border-gray-200 dark:border-white/10 pointer-events-none z-10">
                                            ₺{{ number_format($data['revenue'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="text-center mt-3 text-xs font-bold {{ $loop->last ? 'text-green-400' : 'text-slate-500 group-hover:text-primary transition-colors' }}">
                                        {{ $data['month'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                </div>
                @endif

                <!-- Timeline (Spans 1) -->
                @if(auth()->user()->hasModuleAccess('activities'))
                <div class="lg:col-span-1">
                    <x-card class="h-80 p-8 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl flex flex-col">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-400">history</span>
                                Son Aktiviteler
                            </h3>
                        </div>

                        <div class="flex-1 overflow-y-auto pr-2 space-y-1 custom-scrollbar">
                            @forelse($activities as $activity)
                            <a href="{{ $activity['link'] ?? '#' }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-white/5 transition-colors group cursor-pointer block">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-{{ $activity['color'] }}-500/10 text-{{ $activity['color'] }}-400 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-[18px]">{{ $activity['icon'] }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm text-gray-900 dark:text-white font-medium truncate">{{ $activity['text'] }}</div>
                                    <div class="text-xs text-gray-600 dark:text-slate-500">{{ $activity['time'] }}</div>
                                </div>
                                <span class="material-symbols-outlined text-slate-600 text-[16px] opacity-0 group-hover:opacity-100 transition-opacity">chevron_right</span>
                            </a>
                            @empty
                            <div class="flex flex-col items-center justify-center h-full text-gray-600 dark:text-slate-500 py-8">
                                <span class="material-symbols-outlined text-4xl mb-2 opacity-50">history</span>
                                <div class="text-sm">Henüz aktivite bulunmuyor.</div>
                            </div>
                            @endforelse
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-white/5">
                            <a href="{{ route('activities.index') }}" class="block text-center w-full text-xs text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:text-white font-bold uppercase tracking-wider py-2 transition-colors">
                                Tümünü Gör
                            </a>
                        </div>
                    </x-card>
                </div>
                @endif
            </div>

            <!-- Row 3: Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach([
                    ['route' => route('accounting.invoices.create'), 'icon' => 'receipt_long', 'label' => 'Fatura Kes', 'desc' => 'Yeni satış faturası oluştur', 'color' => 'blue', 'module' => 'accounting.invoices'],
                    ['route' => route('crm.contacts.create'), 'icon' => 'person_add', 'label' => 'Müşteri Ekle', 'desc' => 'Yeni kişi veya firma kartı', 'color' => 'purple', 'module' => 'crm.contacts'],
                    ['route' => route('inventory.products.create'), 'icon' => 'add_box', 'label' => 'Stok Gir', 'desc' => 'Envantere yeni ürün girişi', 'color' => 'orange', 'module' => 'inventory.products'],
                    ['route' => route('accounting.cash-bank-transactions.create', ['type' => 'collection']), 'icon' => 'payments', 'label' => 'Ödeme Al', 'desc' => 'Hızlı tahsilat işlemi', 'color' => 'green', 'module' => 'accounting.cash_bank']
                ] as $action)
                    @if(auth()->user()->hasModuleAccess($action['module']))
                    <a href="{{ $action['route'] }}" class="group relative overflow-hidden rounded-2xl p-1 {{ isset($action['disabled']) ? 'cursor-not-allowed opacity-80' : '' }}">
                        <!-- Background Glass -->
                        <div class="absolute inset-0 bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl backdrop-blur-xl transition-all duration-300 {{ !isset($action['disabled']) ? 'group-hover:bg-white/10 group-hover:border-'.$action['color'].'-500/30 group-hover:shadow-[0_0_30px_rgba(0,0,0,0.3)]' : '' }}"></div>
                        
                        <!-- Content -->
                        <div class="relative p-6 flex flex-col items-start gap-4 h-full">
                            <div class="flex items-start justify-between w-full">
                                <!-- Icon Container -->
                                <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-{{ $action['color'] }}-500/20 to-{{ $action['color'] }}-500/5 text-{{ $action['color'] }}-400 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-{{ $action['color'] }}-500/10">
                                    <span class="material-symbols-outlined text-[28px]">{{ $action['icon'] }}</span>
                                </div>

                                <!-- Arrow or Badge -->
                                @if(isset($action['disabled']))
                                    <span class="px-2 py-1 rounded-md bg-white/5 border border-gray-200 dark:border-white/10 text-[10px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider">
                                        YAKINDA
                                    </span>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-gray-600 dark:text-slate-500 group-hover:bg-{{ $action['color'] }}-500 group-hover:text-gray-900 dark:text-white transition-all duration-300 -mr-2 -mt-2">
                                        <span class="material-symbols-outlined text-sm">arrow_outward</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Text -->
                            <div>
                                <h3 class="font-black text-gray-900 dark:text-white text-lg tracking-tight group-hover:text-{{ $action['color'] }}-400 transition-colors">
                                    {{ $action['label'] }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-slate-400 mt-1 font-medium leading-snug">
                                    {{ $action['desc'] }}
                                </p>
                            </div>
                            
                            <!-- Glow Effect -->
                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-{{ $action['color'] }}-500/20 blur-3xl rounded-full opacity-0 {{ !isset($action['disabled']) ? 'group-hover:opacity-50' : '' }} transition-opacity duration-500 pointer-events-none"></div>
                        </div>
                    </a>
                    @endif
                @endforeach
            </div>

            <!-- Row 4: Lists (3 Cols) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Invoices -->
                @if(auth()->user()->hasModuleAccess('accounting.invoices'))
                <x-card class="h-full p-0 border-gray-200 dark:border-white/10 bg-white/5 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-white/[0.02]">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">description</span>
                            Son Faturalar
                        </h3>
                        <a href="{{ route('accounting.invoices.index') }}" class="text-xs text-primary font-bold uppercase tracking-wider hover:text-gray-900 dark:text-white transition-colors">Tümünü</a>
                    </div>
                    <div class="flex-1 overflow-auto divide-y divide-white/5 max-h-[400px] custom-scrollbar">
                        @forelse($recentInvoices as $invoice)
                        <div class="p-4 flex items-center justify-between hover:bg-white/5 transition-colors group cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center border border-gray-200 dark:border-white/10 text-primary group-hover:border-primary/30 transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">receipt</span>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">{{ $invoice->invoice_number }}</div>
                                    <div class="text-[11px] text-gray-600 dark:text-slate-500 font-bold uppercase tracking-wider">
                                        {{ Str::limit($invoice->contact->name ?? 'Müşteri Yok', 20) }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-black text-gray-900 dark:text-white">₺{{ number_format($invoice->total_amount, 2, ',', '.') }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-md border mt-1 inline-block
                                    {{ $invoice->is_paid_status ? 'bg-green-900/30 text-green-400 border-green-500/30' : 'bg-yellow-900/30 text-yellow-400 border-yellow-500/30' }}">
                                    {{ $invoice->is_paid_status ? 'Ödendi' : 'Bekliyor' }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center text-gray-600 dark:text-slate-500">Kayıt yok.</div>
                        @endforelse
                    </div>
                </x-card>
                @endif

                <!-- Recent Contacts -->
                @if(auth()->user()->hasModuleAccess('crm.contacts'))
                <x-card class="h-full p-0 border-gray-200 dark:border-white/10 bg-white/5 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-white/[0.02]">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-400">group</span>
                            Son Kişiler
                        </h3>
                        <a href="{{ route('crm.contacts.index') }}" class="text-xs text-purple-400 font-bold uppercase tracking-wider hover:text-gray-900 dark:text-white transition-colors">Rehber</a>
                    </div>
                    <div class="flex-1 overflow-auto divide-y divide-white/5 max-h-[400px] custom-scrollbar">
                        @forelse($recentContacts as $contact)
                        <div class="p-4 flex items-center justify-between hover:bg-white/5 transition-colors group cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500/20 to-blue-500/20 flex items-center justify-center text-purple-400 font-black text-sm ring-1 ring-white/10 group-hover:ring-purple-500/30 transition-all">
                                    {{ substr($contact->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-purple-400 transition-colors">{{ $contact->name }}</div>
                                    <div class="text-[11px] text-gray-600 dark:text-slate-500 font-bold flex items-center gap-1">
                                        {{ $contact->email }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-[10px] text-gray-600 dark:text-slate-400 font-bold uppercase flex items-center justify-end gap-1 mb-1">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                    {{ $contact->created_at->diffForHumans(null, true, true) }}
                                </div>
                                <div class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-md border inline-block bg-slate-800/50 text-gray-600 dark:text-slate-400 border-white/5">
                                    {{ Str::limit($contact->company_name ?? 'Bireysel', 15) }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center text-gray-600 dark:text-slate-500">Kayıt yok.</div>
                        @endforelse
                    </div>
                </x-card>
                @endif

                <!-- Critical Stocks -->
                @if(auth()->user()->hasModuleAccess('inventory.products'))
                <x-card class="h-full p-0 border-gray-200 dark:border-white/10 bg-white/5 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-gray-200 dark:border-white/10 flex items-center justify-between bg-white/[0.02]">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-red-500">warning</span>
                            Kritik Stoklar
                        </h3>
                        <a href="{{ route('inventory.products.index') }}" class="text-xs text-red-500 font-bold uppercase tracking-wider hover:text-gray-900 dark:text-white transition-colors">Yönet</a>
                    </div>
                    <div class="flex-1 overflow-auto divide-y divide-white/5 max-h-[400px] custom-scrollbar">
                        @forelse($criticalProductsList as $product)
                        <div class="p-4 flex items-center justify-between hover:bg-white/5 transition-colors group cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center border border-gray-200 dark:border-white/10 text-red-500 group-hover:border-red-500/30 transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-red-500 transition-colors">{{ $product->name }}</div>
                                    <div class="text-[11px] text-gray-600 dark:text-slate-500 font-bold uppercase tracking-wider">
                                        {{ $product->category->name ?? 'Kategorisiz' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-black text-red-500" title="{{ (int)$product->current_stock }} {{ $product->unit?->symbol ?? 'Adet' }}">
                                    {{ (int)$product->current_stock > 20 ? '20+' : (int)$product->current_stock }}
                                </div>
                                <div class="text-[10px] text-gray-600 dark:text-slate-400 font-bold uppercase">
                                    {{ $product->unit?->symbol ?? 'Adet' }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center text-gray-600 dark:text-slate-500 text-sm">Tüm stoklar güvenli seviyede.</div>
                        @endforelse
                    </div>
                </x-card>
                @endif
            </div>
        </div>
    </div>

    <!-- Live Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('live-clock').textContent = `${hours}:${minutes}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</x-app-layout>

