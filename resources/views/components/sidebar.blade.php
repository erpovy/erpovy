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
            elseif (request()->routeIs('setup.*')) $activeMenu = 'setup';
        @endphp

        <div class="flex flex-col gap-2">
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('dashboard') ? 'text-gray-900 dark:text-white' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
                    @if(request()->routeIs('dashboard'))
                        <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                        <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                    @endif
                    <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">dashboard</span>
                    <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Özet (Dashboard)</span>
                </a>



                <!-- Accounting Group -->
                <div>
                    <button type="button" @click.stop.prevent="toggle('accounting')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('accounting.*') ? 'text-gray-900 dark:text-white' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
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
                        <a href="{{ route('accounting.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.dashboard') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.dashboard') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        <a href="{{ route('accounting.accounts.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.accounts.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.accounts.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Hesap Planı
                        </a>
                        <a href="{{ route('accounting.transactions.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.transactions.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.transactions.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Fiş İşlemleri
                        </a>
                        <a href="{{ route('accounting.invoices.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.invoices.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.invoices.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Faturalar
                        </a>
                        <a href="{{ route('accounting.invoice-templates.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.invoice-templates.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.invoice-templates.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Fatura Şablonları
                        </a>
                        <a href="{{ route('accounting.cash-bank-accounts.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.cash-bank-accounts.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.cash-bank-accounts.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kasa/Banka
                        </a>
                        <a href="{{ route('accounting.portfolio.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.portfolio.*') || request()->routeIs('accounting.cheques.*') || request()->routeIs('accounting.promissory-notes.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.portfolio.*') || request()->routeIs('accounting.cheques.*') || request()->routeIs('accounting.promissory-notes.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Çek/Senet
                        </a>
                        <a href="{{ route('accounting.cash-bank-transactions.create', ['type' => 'collection']) }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request('type') == 'collection' ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request('type') == 'collection' ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Ödeme Al (Tahsilat)
                        </a>
                        <a href="{{ route('accounting.cash-bank-transactions.create', ['type' => 'payment']) }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request('type') == 'payment' ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request('type') == 'payment' ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Ödeme Yap (Tediye)
                        </a>
                        <a href="{{ route('accounting.reports.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('accounting.reports.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('accounting.reports.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Finansal Raporlar
                        </a>
                    </div>
                </div>

                <!-- Sales Group -->
                <div>
                    <button type="button" @click.stop.prevent="toggle('sales')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('sales.*') ? 'text-gray-900 dark:text-white' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
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
                        <a href="{{ route('crm.contacts.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.contacts.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.contacts.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Müşteri İlişkileri Yönetimi
                        </a>
                        <a href="{{ route('sales.sales.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.sales.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.sales.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Satışlar
                        </a>
                        <a href="{{ route('sales.quotes.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.quotes.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.quotes.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Teklif Hazırla
                        </a>
                        <a href="{{ route('sales.pos.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.pos.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.pos.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Satış Noktası
                        </a>
                        <a href="{{ route('sales.subscriptions.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.subscriptions.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.subscriptions.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Abonelikler
                        </a>
                        <a href="{{ route('sales.rentals.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('sales.rentals.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('sales.rentals.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kiralama
                        </a>
                    </div>
                </div>

                <!-- CRM Group -->
                <div>
                    <button type="button" @click.stop.prevent="toggle('crm')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('crm.*') ? 'text-gray-900 dark:text-white' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
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
                        <a href="{{ route('crm.contacts.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.contacts.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.contacts.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kişiler & Firmalar
                        </a>
                        <a href="{{ route('crm.leads.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.leads.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.leads.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Potansiyel Müşteriler
                        </a>
                        <a href="{{ route('crm.deals.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.deals.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.deals.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Anlaşmalar
                        </a>
                        <a href="{{ route('crm.contracts.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('crm.contracts.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('crm.contracts.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Sözleşmeler
                        </a>
                    </div>
                </div>

                <!-- Inventory Group -->
                <div>
                    <button type="button" @click.stop.prevent="toggle('inventory')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('inventory.*') ? 'text-gray-900 dark:text-white' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
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
                        <a href="{{ route('inventory.analytics.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.analytics.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.analytics.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Stok Analitik
                        </a>
                        <a href="{{ route('inventory.products.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.products.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.products.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Ürünler
                        </a>
                        <a href="{{ route('inventory.categories.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.categories.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.categories.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kategoriler
                        </a>
                        <a href="{{ route('inventory.brands.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.brands.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.brands.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Markalar
                        </a>
                        <a href="{{ route('inventory.units.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.units.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.units.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Ölçü Birimleri
                        </a>
                        <a href="{{ route('inventory.warehouses.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('inventory.warehouses.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('inventory.warehouses.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Depolar
                        </a>
                    </div>
                </div>

                <!-- Manufacturing Group -->
                <div>
                    <button type="button" @click.stop.prevent="toggle('manufacturing')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('manufacturing.*') ? 'text-gray-900 dark:text-white' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
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
                        <a href="{{ route('manufacturing.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.index') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        <a href="{{ route('manufacturing.create') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.create') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.create') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Yeni İş Emri
                        </a>
                        <a href="{{ route('manufacturing.mrp.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.mrp.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.mrp.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Malzeme İhtiyaç Planlaması (MRP)
                        </a>
                        <a href="{{ route('manufacturing.mes.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.mes.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.mes.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Üretim Yönetim Sistemi (MES)
                        </a>
                        <a href="{{ route('manufacturing.plm.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.plm.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.plm.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Ürün Yaşam Döngüsü (PLM)
                        </a>
                        <a href="{{ route('manufacturing.quality.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.quality.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.quality.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kalite Kontrol
                        </a>
                        <a href="{{ route('manufacturing.shopfloor.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.shopfloor.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.shopfloor.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Üretim Alanı
                        </a>
                        <a href="{{ route('manufacturing.maintenance.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('manufacturing.maintenance.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('manufacturing.maintenance.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Bakım Yönetimi
                        </a>
                    </div>
                </div>

                <!-- Human Resources Group -->
                <div>
                    <button type="button" @click.stop.prevent="toggle('hr')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('hr.*') ? 'text-gray-900 dark:text-white' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
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
                        <a href="{{ route('hr.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.index') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.index') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Özet (Dashboard)
                        </a>
                        <a href="{{ route('hr.departments.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.departments.*') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.departments.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Departmanlar
                        </a>
                        <a href="{{ route('hr.employees.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.employees.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.employees.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Personel Listesi
                        </a>
                        <a href="{{ route('hr.leaves.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.leaves.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.leaves.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            İzin Takvimi
                        </a>
                        <a href="{{ route('hr.fleet.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.fleet.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.fleet.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Filo Yönetimi
                        </a>
                        
                        <!-- User Management Submenu Items -->
                        <a href="{{ route('hr.users.index') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('hr.users.*') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('hr.users.*') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Kullanıcılar
                        </a>
                    </div>
                </div>

                <!-- Activities -->
                <a href="{{ route('activities.index') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('activities.index') ? 'text-white' : 'text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    @if(request()->routeIs('activities.index'))
                        <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                        <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                    @endif
                    <span class="material-symbols-outlined {{ request()->routeIs('activities.index') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">history</span>
                    <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Aktiviteler</span>
                </a>

                <!-- Setup Group -->
                <div>
                     <button type="button" @click.stop.prevent="toggle('setup')" 
                        class="w-full group relative flex items-center justify-between gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('setup.*') ? 'text-gray-900 dark:text-white' : 'text-gray-600 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white' }}">
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
                        <a href="{{ route('setup.accounting') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('setup.accounting') ? 'text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('setup.accounting') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Muhasebe Kurulumu
                        </a>
                        <a href="{{ route('setup.invoice') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('setup.invoice') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('setup.invoice') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            Fatura Kurulumu
                        </a>
                        <a href="{{ route('setup.crm') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm transition-colors {{ request()->routeIs('setup.crm') ? 'text-white bg-gray-200 dark:bg-white/10' : 'text-gray-400 hover:text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/5' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('setup.crm') ? 'bg-primary shadow-[0_0_5px_#137fec]' : 'bg-gray-600' }}"></span>
                            CRM Kurulumu
                        </a>
                    </div>
                </div>

                <!-- Module Market (Visible to all admins) -->
                <a href="{{ route('superadmin.market.index') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('superadmin.market.index') ? 'text-white' : 'text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    @if(request()->routeIs('superadmin.market.index'))
                        <div class="absolute inset-0 rounded-xl bg-neon-active opacity-100 pointer-events-none"></div>
                        <div class="absolute left-0 h-6 w-1 rounded-r-full bg-primary shadow-[0_0_10px_#137fec] pointer-events-none"></div>
                    @endif
                    <span class="material-symbols-outlined {{ request()->routeIs('superadmin.market.index') ? 'icon-filled text-primary drop-shadow-[0_0_8px_rgba(19,127,236,0.8)]' : 'group-hover:text-primary group-hover:drop-shadow-[0_0_8px_rgba(19,127,236,0.6)]' }}">storefront</span>
                    <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Modül Market</span>
                </a>

                @if(auth()->user()->is_super_admin || auth()->user()->hasRole('Admin'))
                 {{-- Settings removed --}}
                @endif

                @if(auth()->user()->is_super_admin)
                <!-- SuperAdmin specific section -->
                <div class="pt-4 mt-2 border-t border-gray-200 dark:border-white/5">
                    <p class="px-4 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Sistem Yönetimi</p>
                    
                    <a href="{{ route('superadmin.index') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('superadmin.index') ? 'text-white bg-primary/20' : 'text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('superadmin.index') ? 'icon-filled text-primary' : 'group-hover:text-primary' }}">home</span>
                        <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Ana Sayfa</span>
                    </a>

                    <a href="{{ route('superadmin.companies.index') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('superadmin.companies.*') ? 'text-white bg-gray-100 dark:bg-white/5' : 'text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                        <span class="material-symbols-outlined {{ request()->routeIs('superadmin.companies.*') ? 'icon-filled text-primary' : 'group-hover:text-primary' }}">corporate_fare</span>
                        <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Şirketler</span>
                    </a>


                    <a href="#" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:text-white">
                        <span class="material-symbols-outlined group-hover:text-primary">payments</span>
                        <span class="font-medium transition-opacity duration-200 whitespace-nowrap" :class="isCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100'">Finans</span>
                    </a>

                    <a href="{{ route('settings.index') }}" class="group relative flex items-center gap-3 rounded-xl px-4 py-3 transition-all hover:bg-gray-100 dark:hover:bg-white/5 {{ request()->routeIs('settings.*') ? 'text-white bg-gray-100 dark:bg-white/5' : 'text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
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

