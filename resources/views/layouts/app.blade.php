<!DOCTYPE html>
<html x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || !localStorage.getItem('theme') }" 
      :class="darkMode ? 'dark' : ''" 
      x-init="$watch('darkMode', value => document.documentElement.classList.toggle('dark', value))"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php $favicon = \App\Models\Setting::get('logo_collapsed'); @endphp
        <link rel="icon" type="image/x-icon" href="{{ $favicon ? (str_starts_with($favicon, 'http') ? $favicon : asset($favicon)) : asset('favicon.png') }}">

        <title>{{ config('app.name', 'Erpovy Kurumsal Yönetim Sistemi V2') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=block" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { 
                font-family: 'Plus Jakarta Sans', sans-serif;
            }

            /* Theme-aware glass card */
            .glass-card {
                backdrop-filter: blur(20px);
                border-radius: 1.5rem;
                padding: 2rem;
            }
            .dark .glass-card {
                background: rgba(13, 17, 23, 0.4);
                border: 1px solid rgba(255, 255, 255, 0.05);
                box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.3);
            }
            html:not(.dark) .glass-card {
                background: rgba(255, 255, 255, 0.7);
                border: 1px solid rgba(0, 0, 0, 0.05);
                box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
            }

            /* Theme-aware custom input */
            .custom-input {
                width: 100%;
                border-radius: 12px !important;
                padding: 0.75rem 1rem !important;
                font-size: 14px !important;
                transition: all 0.3s ease;
            }
            .dark .custom-input {
                background-color: rgba(255, 255, 255, 0.03) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                color: white !important;
            }
            html:not(.dark) .custom-input {
                background-color: rgba(0, 0, 0, 0.02) !important;
                border: 1px solid rgba(0, 0, 0, 0.1) !important;
                color: #1f2937 !important;
            }
            .dark .custom-input:focus {
                background-color: rgba(255, 255, 255, 0.05) !important;
                border-color: #5c67ff !important;
                box-shadow: 0 0 0 1px rgba(92, 103, 255, 0.3) !important;
                outline: none !important;
            }
            html:not(.dark) .custom-input:focus {
                background-color: white !important;
                border-color: #5c67ff !important;
                box-shadow: 0 0 0 1px rgba(92, 103, 255, 0.3) !important;
                outline: none !important;
            }

            /* Theme-aware input label */
            .input-label {
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 0.5rem;
                display: block;
            }
            .dark .input-label {
                color: #94a3b8;
            }
            html:not(.dark) .input-label {
                color: #6b7280;
            }

            .btn-primary {
                background: #5c67ff;
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 10px;
                font-weight: 700;
                font-size: 13px;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                filter: brightness(1.1);
                transform: translateY(-1px);
                box-shadow: 0 10px 15px -3px rgba(92, 103, 255, 0.4);
            }

            .btn-danger {
                background: rgba(239, 68, 68, 0.1);
                color: #ef4444;
                padding: 0.75rem 1.5rem;
                border-radius: 10px;
                font-weight: 700;
                font-size: 13px;
                border: 1px solid rgba(239, 68, 68, 0.2);
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .btn-danger:hover {
                background: #ef4444;
                color: white;
            }

            [x-cloak] { display: none !important; }

            /* Theme-aware Custom Thin Scrollbar */
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
                height: 4px;
            }
            .dark .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.02);
            }
            html:not(.dark) .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.02);
            }
            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
            }
            html:not(.dark) .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.15);
                border-radius: 10px;
            }
            .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.2);
            }
            html:not(.dark) .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: rgba(0, 0, 0, 0.25);
            }
            .dark .custom-scrollbar {
                scrollbar-width: thin;
                scrollbar-color: rgba(255, 255, 255, 0.1) rgba(255, 255, 255, 0.02);
            }
            html:not(.dark) .custom-scrollbar {
                scrollbar-width: thin;
                scrollbar-color: rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.02);
            }
        </style>
    </head>
    <body class="font-display bg-background-light dark:bg-[#0f172a] text-gray-900 dark:text-gray-100 antialiased overflow-hidden">
        <!-- Background ambiance wrapper -->
        <div class="relative flex h-screen w-full bg-gradient-to-br from-gray-50 to-gray-100 dark:bg-deep-space dark:bg-[#0f172a] overflow-hidden">
            <!-- Abstract gradient blob for depth behind glass -->
            <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-primary/20 rounded-full blur-[120px] pointer-events-none z-0"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-purple-900/20 rounded-full blur-[120px] pointer-events-none z-0"></div>
            
            <!-- Sidebar -->
            <x-sidebar />
            
            <!-- Main Content Area -->
            <main class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
                @if(session('is_inspecting'))
                    <div class="bg-blue-600 px-6 py-2 flex items-center justify-between sticky top-0 z-[60] shadow-lg">
                        <div class="flex items-center gap-3 text-white text-xs font-bold uppercase tracking-widest">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                            Gözetim Modu: {{ session('inspected_company_name') }} (Salt-Okunur)
                        </div>
                        <form action="{{ route('superadmin.stop-inspection') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-wider transition-colors">
                                Gözetimden Çık
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Top Navbar - Floating Glass -->
                <header class="sticky top-0 z-40 mx-6 mt-4 mb-6 rounded-2xl border 
                               border-gray-200 dark:border-white/10 
                               bg-white/80 dark:bg-[#0f172a]/70 
                               backdrop-blur-md shadow-lg pointer-events-none">
                    <div class="flex items-center justify-between px-6 py-3 pointer-events-auto">
                        <div class="flex items-center gap-4">
                            <button type="button" class="md:hidden text-gray-900 dark:text-white">
                                <span class="material-symbols-outlined">menu</span>
                            </button>
                            <!-- Search - Global with Alpine -->
                            <div x-data="{
                                query: '',
                                results: [],
                                isSearching: false,
                                open: false,
                                selectedIndex: -1,
                                async fetchResults() {
                                    if (this.query.length < 2) {
                                        this.results = [];
                                        this.open = false;
                                        return;
                                    }
                                    
                                    this.isSearching = true;
                                    this.open = true;
                                    
                                    try {
                                        const response = await fetch(`{{ route('global.search') }}?query=${this.query}`);
                                        this.results = await response.json();
                                        this.selectedIndex = -1;
                                    } catch (e) {
                                        console.error('Search failed', e);
                                    } finally {
                                        this.isSearching = false;
                                    }
                                },
                                selectResult(index) {
                                    if (index >= 0 && index < this.results.length) {
                                        window.location.href = this.results[index].url;
                                    }
                                }
                            }" 
                            @click.away="open = false"
                            class="hidden md:block relative w-96">
                                <div class="relative flex items-center rounded-lg bg-white/5 border border-white/5 px-3 py-2 focus-within:bg-white/10 focus-within:border-primary/50 transition-all">
                                    <span class="material-symbols-outlined text-gray-500 dark:text-gray-400 text-[20px]" :class="isSearching ? 'animate-spin text-primary' : ''" x-text="isSearching ? 'sync' : 'search'"></span>
                                    <input 
                                        x-model="query"
                                        @input.debounce.300ms="fetchResults()"
                                        @keydown.down.prevent="selectedIndex = Math.min(selectedIndex + 1, results.length - 1)"
                                        @keydown.up.prevent="selectedIndex = Math.max(selectedIndex - 1, 0)"
                                        @keydown.enter.prevent="selectResult(selectedIndex)"
                                        @focus="if(query.length >= 2) open = true"
                                        class="ml-2 w-full bg-transparent text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none border-none p-0 focus:ring-0" 
                                        placeholder="Ara: Kişi, Fatura, Ürün..." 
                                        type="text"
                                        autocomplete="off"
                                    />
                                    <div x-show="query.length > 0" @click="query = ''; open = false; results = []" class="cursor-pointer text-gray-500 hover:text-white">
                                        <span class="material-symbols-outlined text-[16px]">close</span>
                                    </div>
                                </div>

                                <!-- Search Results Dropdown -->
                                <div x-show="open && query.length >= 2" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="absolute top-full left-0 w-full mt-2 bg-[#0f172a] border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50 ring-1 ring-white/5">
                                    
                                    <template x-if="results.length === 0 && !isSearching">
                                        <div class="p-4 text-center text-slate-400 text-sm">
                                            Sonuç bulunamadı...
                                        </div>
                                    </template>

                                    <ul class="max-h-96 overflow-y-auto">
                                        <template x-for="(result, index) in results" :key="index">
                                            <li>
                                                <a :href="result.url" 
                                                   class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors border-b border-white/5 last:border-0"
                                                   :class="{'bg-primary/10': index === selectedIndex}">
                                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-primary">
                                                        <span class="material-symbols-outlined text-[18px]" x-text="result.icon"></span>
                                                    </div>
                                                    <div class="flex flex-col overflow-hidden">
                                                        <span class="text-sm font-bold text-white truncate" x-text="result.title"></span>
                                                        <span class="text-xs text-slate-400 truncate" x-text="result.description"></span>
                                                    </div>
                                                    <span class="ml-auto text-[10px] uppercase font-bold text-slate-500 bg-white/5 px-2 py-0.5 rounded border border-white/5" x-text="result.type"></span>
                                                </a>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 md:gap-6">
                            <!-- Enhanced Theme Toggle Switch -->
                            <div x-init="$watch('darkMode', value => { localStorage.setItem('theme', value ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', value); })"
                                 class="relative group">
                                <button @click="darkMode = !darkMode"
                                        class="relative w-20 h-10 rounded-full transition-all duration-500 backdrop-blur-xl border-2 shadow-lg hover:shadow-2xl hover:scale-105 active:scale-95"
                                        :class="darkMode ? 'bg-gradient-to-r from-slate-800 to-slate-900 border-slate-700' : 'bg-gradient-to-r from-blue-400 to-cyan-300 border-blue-300'">
                                    
                                    <!-- Background Glow Effect -->
                                    <div class="absolute inset-0 rounded-full blur-md opacity-50 transition-opacity duration-500"
                                         :class="darkMode ? 'bg-purple-500' : 'bg-yellow-400'"></div>
                                    
                                    <!-- Stars (Dark Mode) -->
                                    <div x-show="darkMode" x-transition class="absolute inset-0 overflow-hidden rounded-full">
                                        <div class="absolute top-2 left-3 w-1 h-1 bg-white rounded-full animate-pulse"></div>
                                        <div class="absolute top-4 left-6 w-0.5 h-0.5 bg-white rounded-full animate-pulse" style="animation-delay: 0.3s"></div>
                                        <div class="absolute top-3 left-10 w-0.5 h-0.5 bg-white rounded-full animate-pulse" style="animation-delay: 0.6s"></div>
                                    </div>
                                    
                                    <!-- Toggle Circle with Icon -->
                                    <div class="absolute top-1 left-1 w-8 h-8 rounded-full transition-all duration-500 flex items-center justify-center shadow-2xl transform"
                                         :class="darkMode ? 'translate-x-10 bg-gradient-to-br from-slate-700 to-slate-900' : 'translate-x-0 bg-gradient-to-br from-yellow-300 to-yellow-500'">
                                        <!-- Moon Icon (Dark Mode) -->
                                        <div x-show="darkMode" x-transition:enter="transition ease-out duration-300" 
                                             x-transition:enter-start="opacity-0 rotate-90" 
                                             x-transition:enter-end="opacity-100 rotate-0"
                                             class="absolute inset-0 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px] text-yellow-200">dark_mode</span>
                                        </div>
                                        <!-- Sun Icon (Light Mode) -->
                                        <div x-show="!darkMode" x-transition:enter="transition ease-out duration-300" 
                                             x-transition:enter-start="opacity-0 -rotate-90" 
                                             x-transition:enter-end="opacity-100 rotate-0"
                                             class="absolute inset-0 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[18px] text-orange-600 animate-pulse">light_mode</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Clouds (Light Mode) -->
                                    <div x-show="!darkMode" x-transition class="absolute inset-0 overflow-hidden rounded-full">
                                        <div class="absolute top-2 right-4 w-3 h-2 bg-white/40 rounded-full"></div>
                                        <div class="absolute top-3 right-2 w-2 h-1.5 bg-white/30 rounded-full"></div>
                                    </div>
                                </button>
                                
                                <!-- Tooltip -->
                                <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                    <div class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-bold px-3 py-1.5 rounded-lg shadow-xl whitespace-nowrap">
                                        <span x-text="darkMode ? 'Aydınlık Mod' : 'Karanlık Mod'"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modern Minimalist Weather Widget -->
                            <div x-data="{ 
                                weather: null, 
                                isWeatherLoading: true,
                                showDetails: false,
                                async fetchWeather() {
                                    try {
                                        const response = await fetch('{{ route('api.weather') }}');
                                        this.weather = await response.json();
                                    } catch (error) {
                                        console.error('Weather fetch failed:', error);
                                    } finally {
                                        this.isWeatherLoading = false;
                                    }
                                },
                            }" 
                            x-init="fetchWeather()"
                            @mouseenter="showDetails = true"
                            @mouseleave="showDetails = false"
                            class="hidden lg:block relative">
                                <!-- Loading State -->
                                <template x-if="isWeatherLoading">
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10">
                                        <span class="material-symbols-outlined text-gray-900 dark:text-white text-[20px] animate-pulse">partly_cloudy_day</span>
                                        <span class="text-xs text-gray-600 dark:text-slate-400 font-bold">Yükleniyor...</span>
                                    </div>
                                </template>
                                
                                <!-- Weather Display -->
                                <template x-if="!isWeatherLoading && weather">
                                    <div class="relative">
                                        <!-- Main Widget -->
                                        <div class="flex items-center gap-3 px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 hover:border-gray-300 dark:hover:border-white/20 transition-all duration-300 cursor-pointer group">
                                            <!-- Weather Icon -->
                                            <div class="relative">
                                                <span class="material-symbols-outlined text-[28px] text-gray-900 dark:text-white transition-all duration-300" 
                                                      x-text="weather.icon || 'wb_cloudy'"></span>
                                            </div>
                                            
                                            <!-- Temperature & City -->
                                            <div class="flex items-baseline gap-2">
                                                <span class="text-3xl font-black text-gray-900 dark:text-white leading-none" x-text="weather.temp_c + '°'"></span>
                                                <span class="text-sm text-gray-700 dark:text-slate-300 font-bold" x-text="weather.city"></span>
                                            </div>
                                        </div>
                                        
                                        <!-- Details Tooltip -->
                                        <div x-show="showDetails"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 translate-y-1"
                                             class="absolute top-full right-0 mt-2 w-64 p-4 rounded-xl bg-white dark:bg-slate-900/95 backdrop-blur-xl border border-gray-200 dark:border-white/10 shadow-2xl z-50">
                                            <!-- Weather Description -->
                                            <div class="text-sm font-bold text-gray-900 dark:text-white mb-3" x-text="weather.weather_desc"></div>
                                            
                                            <!-- Details Grid -->
                                            <div class="space-y-2">

                                                
                                                <!-- Humidity -->
                                                <div class="flex items-center justify-between text-xs">
                                                    <div class="flex items-center gap-2 text-gray-600 dark:text-slate-400">
                                                        <span class="material-symbols-outlined text-[16px] text-cyan-400">water_drop</span>
                                                        <span class="font-bold">Nem</span>
                                                    </div>
                                                    <span class="font-black text-gray-900 dark:text-white" x-text="'%' + weather.humidity"></span>
                                                </div>
                                                
                                                <!-- Wind -->
                                                <div class="flex items-center justify-between text-xs">
                                                    <div class="flex items-center gap-2 text-gray-600 dark:text-slate-400">
                                                        <span class="material-symbols-outlined text-[16px] text-sky-400">air</span>
                                                        <span class="font-bold">Rüzgar</span>
                                                    </div>
                                                    <span class="font-black text-gray-900 dark:text-white" x-text="weather.wind_speed_kmph + ' km/h'"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </header>
                
                <!-- Success Notification -->
                @if(session('success'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 3000)"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="mx-6 mb-4 rounded-xl bg-green-500/10 border border-green-500/20 p-4 flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-green-400">check_circle</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-400">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="flex-shrink-0 text-green-400/60 hover:text-green-400">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                @endif

                <!-- Error Notification (Persistent) -->
                @if(session('error'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mx-6 mb-4 rounded-xl bg-red-500/10 border border-red-500/20 p-4 flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-red-500">error</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-red-400">Bir hata oluştu!</p>
                            <p class="text-sm text-red-300/80">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="flex-shrink-0 text-red-400/60 hover:text-red-400">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                @endif

                <!-- Validation Errors (Persistent) -->
                @if($errors->any())
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mx-6 mb-4 rounded-xl bg-red-500/10 border border-red-500/20 p-4 flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-red-500">warning</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-red-400 mb-1">Lütfen formdaki hataları kontrol edin:</p>
                            <ul class="list-disc list-inside text-sm text-red-300/80 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button @click="show = false" class="flex-shrink-0 text-red-400/60 hover:text-red-400">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                @endif
                
                <!-- Page Header (Slot) -->
                @if (isset($header))
                    <div class="px-6 mb-6">
                        {{ $header }}
                    </div>
                @endif
                
                <!-- Page Content -->
                <div class="flex-1 px-6 pb-6">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Global Delete Confirmation Modal -->
        <div id="global-delete-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>
            
            <!-- Modal Content -->
            <div class="relative w-full max-w-md scale-95 opacity-0 transition-all duration-300 ease-out glass-card border-none bg-slate-900/90 shadow-2xl shadow-red-500/10" id="modal-container">
                <div class="text-center">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-500/10 text-red-500">
                        <span class="material-symbols-outlined text-[32px]">warning</span>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-white" id="modal-title">Emin misiniz?</h3>
                    <p class="mb-8 text-sm leading-relaxed text-slate-400" id="modal-message">Bu işlemi gerçekleştirmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
                    
                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                        <button type="button" id="modal-cancel" class="flex-1 rounded-xl bg-white/5 px-6 py-3 text-sm font-bold text-slate-300 hover:bg-white/10 transition-colors">
                            İPTAL
                        </button>
                        <button type="button" id="modal-confirm" class="flex-1 rounded-xl bg-red-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-red-600/30 hover:bg-red-500 active:scale-95 transition-all uppercase">
                            EVET, SİL
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            #global-delete-modal.active { display: flex; }
            #global-delete-modal.active #modal-container { transform: scale(1); opacity: 1; }
        </style>
    </body>
</html>

