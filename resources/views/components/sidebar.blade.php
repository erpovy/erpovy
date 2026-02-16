<aside 
    x-data="sidebarMenu('{{ $activeMenu ?? null }}')"
    :class="isCollapsed ? 'w-20' : 'w-72'"
    class="sticky top-0 h-screen z-50 flex flex-col 
           border-r border-gray-200 dark:border-white/10 
           bg-white dark:bg-[#0f172a]/20 
           backdrop-blur-xl shadow-glass transition-all duration-300">
    
    <!-- Brand (Fixed) -->
    <div class="flex flex-col items-center px-6 py-6 border-b border-gray-200 dark:border-white/5 flex-shrink-0">
                <div class="flex items-center gap-3 mb-1 overflow-hidden whitespace-nowrap">
                    @php
                        $sysLogoCollapsed = \App\Models\Setting::get('logo_collapsed');
                        $sysLogoLight = \App\Models\Setting::get('logo_light');
                        $sysLogoDark = \App\Models\Setting::get('logo_dark');
                        $defaultLogo = asset('images/logo.png');

                        $collapsedUrl = $sysLogoCollapsed ? (str_starts_with($sysLogoCollapsed, 'http') ? $sysLogoCollapsed : asset($sysLogoCollapsed)) : $defaultLogo;
                        $lightUrl = $sysLogoLight ? (str_starts_with($sysLogoLight, 'http') ? $sysLogoLight : asset($sysLogoLight)) : $defaultLogo;
                        $darkUrl = $sysLogoDark ? (str_starts_with($sysLogoDark, 'http') ? $sysLogoDark : asset($sysLogoDark)) : $defaultLogo;
                    @endphp

                    <!-- Collapsed Logo (Always same, or can be theme aware if requested, but plan said favicon/collapsed is shared) -->
                    <img src="{{ $collapsedUrl }}" 
                         alt="Erpovy" 
                         class="h-8 w-auto flex-shrink-0 transition-all duration-200"
                         :class="isCollapsed ? 'block opacity-100' : 'hidden opacity-0'"
                    >

                    <!-- Expanded Logo (Light Theme) -->
                    <img src="{{ $lightUrl }}" 
                         alt="Erpovy" 
                         class="h-10 w-auto flex-shrink-0 transition-all duration-200"
                         x-show="!isCollapsed && !darkMode"
                    >

                    <!-- Expanded Logo (Dark Theme) -->
                    <img src="{{ $darkUrl }}" 
                         alt="Erpovy" 
                         class="h-10 w-auto flex-shrink-0 transition-all duration-200"
                         x-show="!isCollapsed && darkMode"
                    >

                    <div class="flex flex-col transition-opacity duration-200" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">
                        <span class="text-gray-600 dark:text-slate-500 text-[10px] font-bold uppercase tracking-widest">{{ auth()->user()->is_super_admin ? 'Superadmin' : 'Şirket Paneli' }}</span>
                    </div>
                </div>
    </div>

    <!-- Navigation (Scrollable) -->
    <div class="flex-1 overflow-y-auto px-4 py-4 custom-scrollbar">
        @php
            $activeMenu = null;
            if (request()->routeIs('accounting.*')) $activeMenu = 'accounting';
            elseif (request()->routeIs('sales.*')) $activeMenu = 'sales';
            elseif (request()->routeIs('crm.*')) $activeMenu = 'crm';
            elseif (request()->routeIs('inventory.*')) $activeMenu = 'inventory';
            elseif (request()->routeIs('manufacturing.*')) $activeMenu = 'manufacturing';
            elseif (request()->routeIs('hr.users.*') || request()->routeIs('hr.roles.*') || request()->routeIs('hr.permissions.*')) $activeMenu = 'user_management';
            elseif (request()->routeIs('hr.*')) $activeMenu = 'hr';
            elseif (request()->routeIs('fixedassets.*')) $activeMenu = 'fixedassets';
            elseif (request()->routeIs('purchasing.*')) $activeMenu = 'purchasing';
            elseif (request()->routeIs('logistics.*')) $activeMenu = 'logistics';
            elseif (request()->routeIs('servicemanagement.*')) $activeMenu = 'servicemanagement';
            elseif (request()->routeIs('ecommerce.*')) $activeMenu = 'ecommerce';
            elseif (request()->routeIs('setup.*')) $activeMenu = 'setup';
        @endphp

        <div class="flex flex-col gap-2">
                
                <!-- Dashboard -->
                @if(auth()->user()->hasModuleAccess('dashboard'))
                <a href="{{ route('dashboard') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('dashboard') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                    @if(request()->routeIs('dashboard'))
                        <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                        <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                    @endif
                    <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">dashboard</span>
                    <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Özet (Dashboard)</span>
                </a>
                @endif



                <!-- Accounting Group -->
                @if(auth()->user()->hasModuleAccess('Accounting'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('accounting')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('accounting.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('accounting.*'))
                             <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                             <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('accounting.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">account_balance</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Muhasebe</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('accounting'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('accounting')" x-cloak class="mt-1 space-y-1 pl-4">
                        @if(auth()->user()->hasModuleAccess('accounting.dashboard'))
                        <a href="{{ route('accounting.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.dashboard') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.dashboard') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('accounting.accounts'))
                        <a href="{{ route('accounting.accounts.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.accounts.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.accounts.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Hesap Planı
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('accounting.transactions'))
                        <a href="{{ route('accounting.transactions.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.transactions.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.transactions.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Fiş İşlemleri
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('accounting.invoices'))
                        <a href="{{ route('accounting.invoices.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.invoices.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.invoices.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Faturalar
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('accounting.templates'))
                        <a href="{{ route('accounting.invoice-templates.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.invoice-templates.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.invoice-templates.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Fatura Şablonları
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('accounting.e_transformation'))
                        <div x-data="{ open: {{ request()->routeIs('accounting.e-transformation.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="w-full flex items-center justify-between gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.e-transformation.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                                <div class="flex items-center gap-3">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.e-transformation.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                                    e-Dönüşüm
                                </div>
                                <span class="material-symbols-outlined text-xs transition-transform" :class="open ? 'rotate-90' : ''">chevron_right</span>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-1 border-l border-gray-100 dark:border-white/5">
                                <a href="{{ route('accounting.e-transformation.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-1.5 text-xs transition-colors {{ request()->routeIs('accounting.e-transformation.index') ? 'text-primary font-bold' : 'text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">Özet Paneli</a>
                                <a href="{{ route('accounting.e-transformation.incoming') }}" class="flex items-center gap-3 rounded-lg px-4 py-1.5 text-xs transition-colors {{ request()->routeIs('accounting.e-transformation.incoming') ? 'text-primary font-bold' : 'text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">Gelen Kutusu</a>
                                <a href="{{ route('accounting.e-transformation.outgoing') }}" class="flex items-center gap-3 rounded-lg px-4 py-1.5 text-xs transition-colors {{ request()->routeIs('accounting.e-transformation.outgoing') ? 'text-primary font-bold' : 'text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">Giden Kutusu</a>
                            </div>
                        </div>
                        @endif
                        @if(auth()->user()->hasModuleAccess('accounting.cash_bank'))
                        <div x-data="{ open: {{ request()->routeIs('accounting.cash-bank-accounts.*') || request()->routeIs('accounting.bank-statements.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open" class="w-full flex items-center justify-between gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.cash-bank-accounts.*') || request()->routeIs('accounting.bank-statements.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                                <div class="flex items-center gap-3">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.cash-bank-accounts.*') || request()->routeIs('accounting.bank-statements.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                                    Kasa & Banka
                                </div>
                                <span class="material-symbols-outlined text-xs transition-transform" :class="open ? 'rotate-90' : ''">chevron_right</span>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 ml-4 space-y-1 border-l border-gray-100 dark:border-white/5">
                                <a href="{{ route('accounting.cash-bank-accounts.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-1.5 text-xs transition-colors {{ request()->routeIs('accounting.cash-bank-accounts.*') ? 'text-primary font-bold' : 'text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">Hesap Listesi</a>
                                <a href="{{ route('accounting.bank-statements.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-1.5 text-xs transition-colors {{ request()->routeIs('accounting.bank-statements.*') ? 'text-primary font-bold' : 'text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">Banka Ekstreleri</a>
                            </div>
                        </div>
                        @endif
                        @if(auth()->user()->hasModuleAccess('accounting.portfolio'))
                        <a href="{{ route('accounting.portfolio.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.portfolio.*') || request()->routeIs('accounting.cheques.*') || request()->routeIs('accounting.promissory-notes.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.portfolio.*') || request()->routeIs('accounting.cheques.*') || request()->routeIs('accounting.promissory-notes.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Çek/Senet
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('accounting.collections'))
                        <a href="{{ route('accounting.cash-bank-transactions.create', ['type' => 'collection']) }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request('type') == 'collection' ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request('type') == 'collection' ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Ödeme Al (Tahsilat)
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('accounting.payments'))
                        <a href="{{ route('accounting.cash-bank-transactions.create', ['type' => 'payment']) }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request('type') == 'payment' ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request('type') == 'payment' ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Ödeme Yap (Tediye)
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('accounting.reports'))
                        <a href="{{ route('accounting.cash-flow.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.cash-flow.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.cash-flow.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Nakit Akış Öngörüsü
                        </a>
                        <a href="{{ route('accounting.reports.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.reports.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.reports.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Finansal Raporlar
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Sales Group -->
                @if(auth()->user()->hasModuleAccess('Sales'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('sales')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('sales.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('sales.*'))
                                <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                                <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('sales.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">point_of_sale</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Satış</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('sales'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('sales')" x-cloak class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('sales.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.dashboard') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.dashboard') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        @if(auth()->user()->hasModuleAccess('sales.crm_sync'))

                        <a href="{{ route('crm.contacts.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.contacts.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.contacts.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Müşteri İlişkileri Yönetimi
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('sales.list'))
                        <a href="{{ route('sales.sales.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.sales.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.sales.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Satışlar
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('sales.quotes'))
                        <a href="{{ route('sales.quotes.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.quotes.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.quotes.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Teklif Hazırla
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('sales.pos'))
                        <a href="{{ route('sales.pos.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.pos.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.pos.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Satış Noktası
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('sales.subscriptions'))
                        <a href="{{ route('sales.subscriptions.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.subscriptions.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.subscriptions.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Abonelikler
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('sales.rentals'))
                        <a href="{{ route('sales.rentals.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.rentals.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.rentals.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kiralama
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- CRM Group -->
                @if(auth()->user()->hasModuleAccess('CRM'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('crm')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('crm.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('crm.*'))
                             <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                             <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('crm.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">groups</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">CRM</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('crm'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('crm')" x-cloak class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('crm.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.dashboard') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.dashboard') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        @if(auth()->user()->hasModuleAccess('crm.contacts'))

                        <a href="{{ route('crm.contacts.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.contacts.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.contacts.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kişiler & Firmalar
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('crm.leads'))
                        <a href="{{ route('crm.leads.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.leads.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.leads.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Potansiyel Müşteriler
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('crm.deals'))
                        <a href="{{ route('crm.deals.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.deals.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.deals.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Anlaşmalar
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('crm.contracts'))
                        <a href="{{ route('crm.contracts.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.contracts.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.contracts.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Sözleşmeler
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Logistics Group -->
                @if(auth()->user()->hasModuleAccess('Logistics'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('logistics')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('logistics.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('logistics.*'))
                             <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                             <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('logistics.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">local_shipping</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Lojistik & Sevkiyat</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('logistics'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('logistics')" x-cloak class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('logistics.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('logistics.dashboard') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('logistics.dashboard') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        <a href="{{ route('logistics.shipments.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('logistics.shipments.index') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('logistics.shipments.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Sevkiyat Listesi
                        </a>
                        <a href="{{ route('logistics.shipments.create') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('logistics.shipments.create') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('logistics.shipments.create') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Yeni Sevkiyat
                        </a>
                        <a href="{{ route('logistics.routes.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('logistics.routes.index') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('logistics.routes.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Rota Planlama
                        </a>
                        <a href="{{ route('logistics.routes.create') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('logistics.routes.create') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('logistics.routes.create') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Yeni Rota
                        </a>
                        <a href="{{ route('logistics.vehicles.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('logistics.vehicles.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('logistics.vehicles.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Araç Yönetimi
                        </a>
                        <a href="{{ route('logistics.settings.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('logistics.settings.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('logistics.settings.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Lojistik Ayarları
                        </a>
                    </div>
                </div>
                @endif

                <!-- Inventory Group -->
                @if(auth()->user()->hasModuleAccess('Inventory'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('inventory')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('inventory.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('inventory.*'))
                             <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                             <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('inventory.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">inventory_2</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Stok Yönetimi</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('inventory'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('inventory')" x-cloak class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('inventory.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.dashboard') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.dashboard') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        @if(auth()->user()->hasModuleAccess('inventory.analytics'))

                        <a href="{{ route('inventory.analytics.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.analytics.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.analytics.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Stok Analitik
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('inventory.products'))
                        <a href="{{ route('inventory.products.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.products.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.products.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Ürünler
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('inventory.categories'))
                        <a href="{{ route('inventory.categories.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.categories.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.categories.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kategoriler
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('inventory.brands'))
                        <a href="{{ route('inventory.brands.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.brands.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.brands.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Markalar
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('inventory.units'))
                        <a href="{{ route('inventory.units.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.units.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.units.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Ölçü Birimleri
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('inventory.warehouses'))
                        <a href="{{ route('inventory.warehouses.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.warehouses.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.warehouses.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Depolar
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Purchasing Group -->
                @if(auth()->user()->hasModuleAccess('Purchasing'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('purchasing')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('purchasing.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('purchasing.*'))
                             <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                             <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('purchasing.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">shopping_cart</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Satın Alma</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('purchasing'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('purchasing')" x-cloak class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('purchasing.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('purchasing.dashboard') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('purchasing.dashboard') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        <a href="{{ route('purchasing.orders.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('purchasing.orders.index') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('purchasing.orders.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Sipariş Listesi
                        </a>
                        <a href="{{ route('purchasing.orders.create') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('purchasing.orders.create') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('purchasing.orders.create') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Yeni Sipariş
                        </a>
                        <a href="{{ route('purchasing.orders.index', ['status' => 'pending']) }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request('status') == 'pending' ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request('status') == 'pending' ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Mal Kabul Bekleyenler
                        </a>
                        <a href="{{ route('purchasing.suppliers.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('purchasing.suppliers.index') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('purchasing.suppliers.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Tedarikçi Listesi
                        </a>
                        <a href="{{ route('crm.contacts.create', ['type' => 'vendor']) }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request('type') == 'vendor' && request()->routeIs('crm.contacts.create') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request('type') == 'vendor' && request()->routeIs('crm.contacts.create') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Yeni Tedarikçi
                        </a>
                    </div>
                </div>
                @endif

                <!-- Ecommerce Group -->
                @if(auth()->user()->hasModuleAccess('Ecommerce'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('ecommerce')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('ecommerce.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('ecommerce.*'))
                                <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                                <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('ecommerce.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">shopping_bag</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">e-Ticaret</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('ecommerce'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('ecommerce')" x-cloak class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('ecommerce.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('ecommerce.index') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('ecommerce.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Genel Bakış
                        </a>
                        <a href="{{ route('ecommerce.platforms.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('ecommerce.platforms.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('ecommerce.platforms.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Mağaza Ayarları
                        </a>
                    </div>
                </div>
                @endif

                <!-- Manufacturing Group -->
                @if(auth()->user()->hasModuleAccess('Manufacturing'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('manufacturing')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('manufacturing.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('manufacturing.*'))
                             <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                             <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('manufacturing.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">factory</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Üretim</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('manufacturing'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('manufacturing')" x-cloak class="mt-1 space-y-1 pl-4">
                        @if(auth()->user()->hasModuleAccess('manufacturing.dashboard'))
                        <a href="{{ route('manufacturing.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.index') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('manufacturing.orders'))
                        <a href="{{ route('manufacturing.create') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.create') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.create') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Yeni İş Emri
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('manufacturing.boms'))
                        <a href="{{ route('manufacturing.mrp.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.mrp.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.mrp.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Malzeme İhtiyaç Planlaması (MRP)
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('manufacturing.mes'))
                        <a href="{{ route('manufacturing.mes.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.mes.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.mes.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Üretim Yönetim Sistemi (MES)
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('manufacturing.plm'))
                        <a href="{{ route('manufacturing.plm.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.plm.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.plm.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Ürün Yaşam Döngüsü (PLM)
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('manufacturing.quality'))
                        <a href="{{ route('manufacturing.quality.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.quality.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.quality.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kalite Kontrol
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('manufacturing.shopfloor'))
                        <a href="{{ route('manufacturing.shopfloor.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.shopfloor.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.shopfloor.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Üretim Alanı
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('manufacturing.maintenance'))
                        <a href="{{ route('manufacturing.maintenance.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.maintenance.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.maintenance.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Bakım Yönetimi
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Human Resources Group -->
                @if(auth()->user()->hasModuleAccess('HumanResources'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('hr')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('hr.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('hr.*'))
                             <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                             <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('hr.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">badge</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">İnsan Kaynakları</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('hr'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('hr')" x-cloak class="mt-1 space-y-1 pl-4">
                        @if(auth()->user()->hasModuleAccess('hr.dashboard'))
                        <a href="{{ route('hr.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.index') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('hr.departments'))
                        <a href="{{ route('hr.departments.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.departments.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.departments.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Departmanlar
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('hr.employees'))
                        <a href="{{ route('hr.employees.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.employees.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.employees.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Personel Listesi
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('hr.leave'))
                        <a href="{{ route('hr.leaves.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.leaves.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.leaves.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            İzin Takvimi
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('hr.payrolls'))
                        <a href="{{ route('hr.payrolls.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.payrolls.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.payrolls.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Bordro Yönetimi
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('hr.fleet'))
                        <a href="{{ route('hr.fleet.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.fleet.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.fleet.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Filo Yönetimi
                        </a>
                        @endif


                        
                        <!-- User Management Submenu Items -->
                        @if(auth()->user()->hasModuleAccess('hr.users'))
                        <a href="{{ route('hr.users.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.users.*') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.users.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kullanıcılar
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Fixed Assets (Demirbaşlar) -->
                @if(auth()->user()->hasModuleAccess('FixedAssets'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('fixedassets')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('fixedassets.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('fixedassets.*'))
                             <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                             <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('fixedassets.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">inventory_2</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Demirbaş Yönetimi</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('fixedassets'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('fixedassets')" x-cloak class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('fixedassets.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('fixedassets.dashboard') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('fixedassets.dashboard') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>

                        <a href="{{ route('fixedassets.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('fixedassets.index') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('fixedassets.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Demirbaş Listesi
                        </a>
                        
                        <a href="{{ route('fixedassets.create') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('fixedassets.create') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('fixedassets.create') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Yeni Demirbaş
                        </a>

                        <a href="{{ route('fixedassets.categories.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('fixedassets.categories.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('fixedassets.categories.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kategoriler
                        </a>
                    </div>
                </div>
                @endif

                <!-- Service/Maintenance Group -->
                @if(auth()->user()->hasModuleAccess('ServiceManagement'))
                <div>
                    <button type="button" @click.stop.prevent="toggle('servicemanagement')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('servicemanagement.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('servicemanagement.*'))
                             <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                             <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('servicemanagement.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">build</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Servis / Bakım</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('servicemanagement'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('servicemanagement')" x-cloak class="mt-1 space-y-1 pl-4">
                        <a href="{{ route('servicemanagement.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('servicemanagement.index') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('servicemanagement.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        <a href="{{ route('servicemanagement.vehicles.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('servicemanagement.vehicles.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('servicemanagement.vehicles.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Araçlar
                        </a>
                        <a href="{{ route('servicemanagement.maintenance-schedule') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('servicemanagement.maintenance-schedule') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('servicemanagement.maintenance-schedule') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Bakım Planlama
                        </a>
                        <a href="{{ route('servicemanagement.service-records.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('servicemanagement.service-records.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('servicemanagement.service-records.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Servis Kayıtları
                        </a>
                    </div>
                </div>
                @endif

                <!-- Activities -->
                @if(auth()->user()->hasModuleAccess('activities'))
                <a href="{{ route('activities.index') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('activities.index') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                    @if(request()->routeIs('activities.index'))
                        <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                        <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                    @endif
                    <span class="material-symbols-outlined {{ request()->routeIs('activities.index') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">history</span>
                    <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Aktiviteler</span>
                </a>
                @endif

                <!-- Setup Group -->
                @if(auth()->user()->hasModuleAccess('Setup'))
                <div>
                     <button type="button" @click.stop.prevent="toggle('setup')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('setup.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        @if(request()->routeIs('setup.*'))
                             <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                             <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ request()->routeIs('setup.*') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">settings_suggest</span>
                            <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Kurulum</span>
                        </div>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 opacity-50" :class="{'rotate-90': isOpen('setup'), 'hidden': isCollapsed}">chevron_right</span>
                    </button>
                    
                    <div x-show="isOpen('setup')" x-cloak class="mt-1 space-y-1 pl-4">
                        @if(auth()->user()->hasModuleAccess('setup.accounting'))
                        <a href="{{ route('setup.accounting') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('setup.accounting') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('setup.accounting') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Muhasebe Kurulumu
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('setup.invoice'))
                        <a href="{{ route('setup.invoice') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('setup.invoice') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('setup.invoice') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Fatura Kurulumu
                        </a>
                        @endif
                        @if(auth()->user()->hasModuleAccess('setup.crm'))
                        <a href="{{ route('setup.crm') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('setup.crm') ? 'text-white bg-primary/20 dark:bg-white/10' : 'text-slate-500 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('setup.crm') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            CRM Kurulumu
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Module Market (Visible to all admins) -->
                @if(auth()->user()->hasModuleAccess('market.index'))
                <a href="{{ route('superadmin.market.index') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('superadmin.market.index') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                    @if(request()->routeIs('superadmin.market.index'))
                        <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                        <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                    @endif
                    <span class="material-symbols-outlined {{ request()->routeIs('superadmin.market.index') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">storefront</span>
                    <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Modül Market</span>
                </a>
                @endif

                @if(auth()->user()->is_super_admin)
                 {{-- Settings removed --}}
                @endif

                @if(auth()->user()->is_super_admin)
                <!-- SuperAdmin specific section -->
                <div class="pt-4 mt-2 border-t border-gray-200 dark:border-white/5">
                    <p class="px-4 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Sistem Yönetimi</p>
                    
                    <a href="{{ route('superadmin.index') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('superadmin.index') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-primary/20' : 'text-slate-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('superadmin.index') ? 'icon-filled text-primary' : 'group-hover:text-primary' }}">home</span>
                        <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Ana Sayfa</span>
                    </a>

                    <a href="{{ route('superadmin.companies.index') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('superadmin.companies.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('superadmin.companies.*') ? 'icon-filled text-primary' : 'group-hover:text-primary' }}">corporate_fare</span>
                        <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Şirketler</span>
                    </a>


                    <a href="#" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 text-slate-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        <span class="material-symbols-outlined group-hover:text-primary">payments</span>
                        <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Finans</span>
                    </a>

                    <a href="{{ route('settings.index') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('settings.*') ? 'text-gray-900 bg-gray-100 dark:text-white dark:bg-white/5' : 'text-slate-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('settings.*') ? 'icon-filled text-primary' : 'group-hover:text-primary' }}">settings</span>
                        <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Sistem Ayarları</span>
                    </a>


                </div>
                @endif

        </div>
    </div>

    <!-- Sidebar Footer (Fixed) -->
    <div class="mt-auto p-4 border-t border-gray-200 dark:border-white/10 flex flex-col gap-2 bg-gray-50 dark:bg-slate-900/50 backdrop-blur-md flex-shrink-0">
            <!-- Collapse Toggle Button -->
            <button @click="toggleSidebar()" class="flex items-center gap-3 rounded-xl px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition-all">
                <span class="material-symbols-outlined transition-transform duration-300" :class="isCollapsed ? 'rotate-180' : ''">keyboard_double_arrow_left</span>
                <span class="font-medium text-sm transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Menüyü Daralt</span>
            </button>

            <!-- User Profile -->
            <a class="flex items-center gap-3 rounded-xl bg-gray-100 dark:bg-white/5 p-3 hover:bg-gray-200 dark:hover:bg-white/10 transition-colors border border-gray-200 dark:border-white/5" href="{{ route('profile.edit') }}">
                <div class="relative flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-primary to-purple-600 p-[2px]">
                        <div class="w-full h-full rounded-full bg-white dark:bg-slate-900 flex items-center justify-center font-bold text-gray-900 dark:text-white">
                             {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                     <div class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-gray-100 dark:ring-[#111418]"></div>
                </div>
                <div class="flex flex-col overflow-hidden transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">
                    <span class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                    <span class="truncate text-xs text-gray-600 dark:text-gray-400">Yönetici Hesabı</span>
                </div>
                <span class="material-symbols-outlined ml-auto text-gray-500 transition-opacity duration-200" :class="isCollapsed ? 'opacity-0 hidden' : 'opacity-100'">expand_less</span>
            </a>
            
             <!-- Logout Form -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-lg bg-red-500/10 py-2 text-xs font-bold text-red-500 hover:bg-red-500/20 transition-colors" :title="isCollapsed ? 'Çıkış Yap' : ''">
                    <span class="material-symbols-outlined text-[16px]">logout</span>
                    <span class="transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">ÇIKIŞ YAP</span>
                </button>
            </form>
        </div>
</aside>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sidebarMenu', (initialState) => ({
            activeMenu: initialState || localStorage.getItem('sidebarActiveMenu'),
            isCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            _toggling: false,

            init() {
                // If a specific menu is active via route, sync it to storage
                if (initialState) {
                    localStorage.setItem('sidebarActiveMenu', initialState);
                }
            },

            toggle(menu) {
                if (this.isCollapsed) {
                    this.toggleSidebar();
                    setTimeout(() => {
                         this.updateActiveMenu(menu);
                    }, 50);
                    return;
                }

                if (this._toggling) return;
                this._toggling = true;

                this.updateActiveMenu(menu);

                setTimeout(() => {
                    this._toggling = false;
                }, 300);
            },

            updateActiveMenu(menu) {
                const newMenu = this.activeMenu === menu ? null : menu;
                this.activeMenu = newMenu;
                
                if (newMenu) {
                    localStorage.setItem('sidebarActiveMenu', newMenu);
                } else {
                    localStorage.removeItem('sidebarActiveMenu');
                }
            },

            isOpen(menu) {
                return this.activeMenu === menu && !this.isCollapsed;
            },

            toggleSidebar() {
                this.isCollapsed = !this.isCollapsed;
                localStorage.setItem('sidebarCollapsed', this.isCollapsed);
            }
        }));
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>

