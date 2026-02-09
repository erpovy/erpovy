<x-app-layout>
    <x-slot name="header">
        Panel Genel Bakış
    </x-slot>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Toplam Şirket -->
        <div class="bg-[#1a2332]/40 border border-white/5 rounded-[2rem] p-6 backdrop-blur-xl relative overflow-hidden group hover:border-blue-500/30 transition-all duration-500 shadow-xl">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-widest mb-2">Toplam Şirket</p>
                    <h3 class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['total_companies']) }}</h3>
                </div>
                <div class="bg-blue-600/20 w-12 h-12 flex items-center justify-center rounded-2xl text-blue-400 shadow-[inset_0_0_15px_rgba(37,99,235,0.2)] border border-blue-500/20">
                    <span class="material-symbols-outlined text-[28px] icon-filled">corporate_fare</span>
                </div>
            </div>
            <div class="flex items-center text-xs font-bold {{ $stats['company_growth'] >= 0 ? 'text-green-400' : 'text-red-400' }}">
                <div class="flex items-center {{ $stats['company_growth'] >= 0 ? 'bg-green-500/10' : 'bg-red-500/10' }} px-2 py-1 rounded-lg mr-2">
                    <span class="material-symbols-outlined text-[14px] mr-1">{{ $stats['company_growth'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                    {{ $stats['company_growth'] >= 0 ? '+' : '' }}{{ $stats['company_growth'] }}%
                </div>
                <span class="text-slate-500 text-[10px]">geçen aya göre</span>
            </div>
        </div>

        <!-- Aylık Gelir -->
        <div class="bg-[#1a2332]/40 border border-white/5 rounded-[2rem] p-6 backdrop-blur-xl relative overflow-hidden group hover:border-purple-500/30 transition-all duration-500 shadow-xl">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-widest mb-2">Aylık Toplam Gelir</p>
                    <h3 class="text-4xl font-extrabold text-white tracking-tight">
                        {{ $stats['monthly_revenue'] > 10000 ? '₺' . number_format($stats['monthly_revenue'] / 1000, 1) . 'K' : '₺' . number_format($stats['monthly_revenue'], 2) }}
                    </h3>
                </div>
                <div class="bg-purple-600/20 w-12 h-12 flex items-center justify-center rounded-2xl text-purple-400 shadow-[inset_0_0_15px_rgba(147,51,234,0.2)] border border-purple-500/20">
                    <span class="material-symbols-outlined text-[28px] icon-filled">payments</span>
                </div>
            </div>
            <div class="flex items-center text-xs font-bold {{ $stats['revenue_growth'] >= 0 ? 'text-green-400' : 'text-red-400' }}">
                <div class="flex items-center {{ $stats['revenue_growth'] >= 0 ? 'bg-green-500/10' : 'bg-red-500/10' }} px-2 py-1 rounded-lg mr-2">
                    <span class="material-symbols-outlined text-[14px] mr-1">{{ $stats['revenue_growth'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                     {{ $stats['revenue_growth'] >= 0 ? '+' : '' }}{{ $stats['revenue_growth'] }}%
                </div>
                <span class="text-slate-500 text-[10px]">geçen aya göre</span>
            </div>
        </div>

        <!-- Aktif Kullanıcı -->
        <div class="bg-[#1a2332]/40 border border-white/5 rounded-[2rem] p-6 backdrop-blur-xl relative overflow-hidden group hover:border-orange-500/30 transition-all duration-500 shadow-xl">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-widest mb-2">Toplam Kullanıcı</p>
                    <h3 class="text-4xl font-extrabold text-white tracking-tight">{{ number_format($stats['total_users']) }}</h3>
                </div>
                <div class="bg-orange-600/20 w-12 h-12 flex items-center justify-center rounded-2xl text-orange-400 shadow-[inset_0_0_15px_rgba(234,88,12,0.2)] border border-orange-500/20">
                    <span class="material-symbols-outlined text-[28px] icon-filled">group</span>
                </div>
            </div>
            <div class="flex items-center text-xs font-bold {{ $stats['user_growth'] >= 0 ? 'text-green-400' : 'text-red-400' }}">
                <div class="flex items-center {{ $stats['user_growth'] >= 0 ? 'bg-green-500/10' : 'bg-red-500/10' }} px-2 py-1 rounded-lg mr-2">
                    <span class="material-symbols-outlined text-[14px] mr-1">{{ $stats['user_growth'] >= 0 ? 'trending_up' : 'trending_down' }}</span>
                    {{ $stats['user_growth'] >= 0 ? '+' : '' }}{{ $stats['user_growth'] }}%
                </div>
                <span class="text-slate-500 text-[10px]">geçen haftaya göre</span>
            </div>
        </div>

        <!-- Sunucu Durumu -->
        <div class="bg-[#1a2332]/40 border border-white/5 rounded-[2rem] p-6 backdrop-blur-xl relative overflow-hidden group hover:border-emerald-500/30 transition-all duration-500 shadow-xl">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-widest mb-2">Sunucu Durumu</p>
                    <h3 class="text-4xl font-extrabold text-white tracking-tight">%99.9</h3>
                </div>
                <div class="bg-emerald-600/20 w-12 h-12 flex items-center justify-center rounded-2xl text-emerald-400 shadow-[inset_0_0_15px_rgba(16,185,129,0.2)] border border-emerald-500/20">
                    <span class="material-symbols-outlined text-[28px] icon-filled">dns</span>
                </div>
            </div>
            <div class="flex items-center text-xs font-bold text-emerald-400">
                <div class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                Sistemler Operasyonel
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        <!-- Map Area -->
        <div class="lg:col-span-8 bg-[#1a2332]/40 border border-white/5 rounded-[2.5rem] backdrop-blur-xl overflow-hidden flex flex-col shadow-2xl">
            <div class="p-8 flex justify-between items-center">
                <h2 class="text-xl font-extrabold text-white tracking-tight">Abone Dağılımı</h2>
                <div class="flex bg-black/40 rounded-full p-1.5 border border-white/5 shadow-inner">
                    <button class="px-5 py-2 rounded-full text-xs font-bold text-slate-400 hover:text-white transition-colors">Dünya</button>
                    <button class="px-5 py-2 rounded-full text-xs font-bold text-white bg-blue-600 shadow-[0_0_15px_rgba(37,99,235,0.4)]">Türkiye</button>
                </div>
            </div>
            <div class="relative h-[500px] px-8 pb-8">
                <!-- Map Search Overlay -->
                <div class="absolute top-4 left-12 z-[1000] w-72">
                    <div class="flex items-center rounded-2xl bg-black/50 border border-white/10 px-4 py-3 focus-within:bg-black/70 focus-within:border-blue-500/50 transition-all shadow-xl backdrop-blur-md">
                        <span class="material-symbols-outlined text-slate-500 text-[20px]">search</span>
                        <input class="ml-3 w-full bg-transparent text-sm text-white placeholder-slate-600 focus:outline-none border-none p-0" placeholder="Bölge ara..." type="text"/>
                    </div>
                </div>
                <!-- Map Selection Tool -->
                <div class="absolute bottom-12 right-12 z-[1000] flex flex-col gap-3">
                    <button @click="$refs.mapContainer.map?.zoomIn()" class="w-12 h-12 rounded-2xl bg-black/50 hover:bg-black/70 border border-white/10 flex items-center justify-center text-white transition-all shadow-xl backdrop-blur-md hover:scale-105 active:scale-95">
                        <span class="material-symbols-outlined text-[24px]">add</span>
                    </button>
                    <button @click="$refs.mapContainer.map?.zoomOut()" class="w-12 h-12 rounded-2xl bg-black/50 hover:bg-black/70 border border-white/10 flex items-center justify-center text-white transition-all shadow-xl backdrop-blur-md hover:scale-105 active:scale-95">
                        <span class="material-symbols-outlined text-[24px]">remove</span>
                    </button>
                </div>

                <!-- Interactive Turkey Map with Leaflet -->
                <div id="turkeyMap" x-ref="mapContainer" class="w-full h-full rounded-[2rem] overflow-hidden border border-white/5 relative" 
                     x-data="{
                         map: null,
                         markers: [],
                         cityCoordinates: {
                             'Adana': [37.0000, 35.3213], 'Adıyaman': [37.7648, 38.2786], 'Afyonkarahisar': [38.7507, 30.5567], 'Ağrı': [39.7191, 43.0503], 'Amasya': [40.6499, 35.8353], 'Ankara': [39.9334, 32.8597], 'Antalya': [36.8969, 30.7133], 'Artvin': [41.1828, 41.8183], 'Aydın': [37.8560, 27.8416], 'Balıkesir': [39.6484, 27.8826],
                             'Bilecik': [40.1451, 29.9799], 'Bingöl': [38.8851, 40.4983], 'Bitlis': [38.4006, 42.1095], 'Bolu': [40.7350, 31.6061], 'Burdur': [37.7204, 30.2908], 'Bursa': [40.1826, 29.0665], 'Çanakkale': [40.1553, 26.4142], 'Çankırı': [40.6013, 33.6134], 'Çorum': [40.5506, 34.9556], 'Denizli': [37.7765, 29.0864],
                             'Diyarbakır': [37.9144, 40.2306], 'Edirne': [41.6818, 26.5623], 'Elazığ': [38.6810, 39.2260], 'Erzincan': [39.7500, 39.5000], 'Erzurum': [39.9043, 41.2679], 'Eskişehir': [39.7767, 30.5206], 'Gaziantep': [37.0662, 37.3833], 'Giresun': [40.9128, 38.3895], 'Gümüşhane': [40.4600, 39.4814], 'Hakkari': [37.5833, 43.7333],
                             'Hatay': [36.4018, 36.3498], 'Isparta': [37.7648, 30.5566], 'Mersin': [36.8121, 34.6415], 'İstanbul': [41.0082, 28.9784], 'Istanbul': [41.0082, 28.9784], 'İzmir': [38.4237, 27.1428], 'Izmir': [38.4237, 27.1428], 'Kars': [40.6172, 43.0974], 'Kastamonu': [41.3887, 33.7827], 'Kayseri': [38.7312, 35.4787],
                             'Kırklareli': [41.7333, 27.2167], 'Kırşehir': [39.1425, 34.1709], 'Kocaeli': [40.8533, 29.8815], 'Konya': [37.8746, 32.4932], 'Kütahya': [39.4167, 29.9833], 'Malatya': [38.3552, 38.3095], 'Manisa': [38.6191, 27.4289], 'Kahramanmaraş': [37.5858, 36.9371], 'Mardin': [37.3212, 40.7245], 'Muğla': [37.2153, 28.3636],
                             'Muş': [38.9462, 41.7539], 'Nevşehir': [38.6939, 34.6857], 'Niğde': [37.9667, 34.6833], 'Ordu': [40.9839, 37.8764], 'Rize': [41.0201, 40.5234], 'Sakarya': [40.7569, 30.3783], 'Samsun': [41.2867, 36.3300], 'Siirt': [37.9333, 41.9500], 'Sinop': [42.0231, 35.1531], 'Sivas': [39.7477, 37.0179],
                             'Tekirdağ': [40.9833, 27.5167], 'Tokat': [40.3167, 36.5500], 'Trabzon': [41.0015, 39.7178], 'Tunceli': [39.3074, 39.4388], 'Şanlıurfa': [37.1591, 38.7969], 'Uşak': [38.6823, 29.4082], 'Van': [38.4891, 43.4089], 'Yozgat': [39.8181, 34.8147], 'Zonguldak': [41.4564, 31.7987], 'Aksaray': [38.3687, 34.0370],
                             'Bayburt': [40.2552, 40.2249], 'Karaman': [37.1759, 33.2287], 'Kırıkkale': [39.8468, 33.5153], 'Batman': [37.8812, 41.1291], 'Şırnak': [37.5164, 42.4611], 'Bartın': [41.6344, 32.3375], 'Ardahan': [41.1105, 42.7022], 'Iğdır': [39.9196, 44.0453], 'Yalova': [40.6500, 29.2667], 'Karabük': [41.2061, 32.6204],
                             'Kilis': [36.7184, 37.1212], 'Osmaniye': [37.0742, 36.2467], 'Düzce': [40.8438, 31.1565]
                         },
                         locations: {{ json_encode($companyLocations ?? []) }},
                         initMap() {
                             // Initialize map centered on Turkey
                             this.map = L.map('turkeyMap', {
                                 zoomControl: false,
                                 attributionControl: false
                             }).setView([39.0, 35.0], 6);
                             
                             // Attach map instance to DOM element for external access
                             this.$el.map = this.map;
                             
                             // Add CartoDB Dark Matter tiles (dark theme, no API key needed)
                             L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                                 maxZoom: 19,
                                 minZoom: 5,
                                 subdomains: 'abcd'
                             }).addTo(this.map);
                             
                             // Add markers for each location
                             this.locations.forEach(location => {
                                 const coords = this.cityCoordinates[location.city];
                                 if (coords) {
                                     // Create custom icon with count badge
                                     const icon = L.divIcon({
                                         className: 'custom-marker',
                                         html: `
                                             <div style='position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;'>
                                                 <div class='animate-ping' style='position: absolute; width: 48px; height: 48px; background-color: rgba(59, 130, 246, 0.3); border-radius: 50%;'></div>
                                                 <div style='position: relative; width: 40px; height: 40px; background: linear-gradient(to bottom right, #3b82f6, #2563eb); border-radius: 50%; border: 3px solid white; box-shadow: 0 0 20px rgba(59,130,246,0.6); display: flex; align-items: center; justify-content: center;'>
                                                     <div style='width: 12px; height: 12px; background-color: white; border-radius: 50%;'></div>
                                                 </div>
                                                 <div style='position: absolute; top: -5px; right: -5px; width: 24px; height: 24px; background: linear-gradient(to bottom right, #ef4444, #dc2626); border-radius: 50%; border: 2px solid white; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: center; z-index: 999;'>
                                                     <span style='color: white; font-size: 11px; font-weight: bold;'>${location.count}</span>
                                                 </div>
                                             </div>
                                         `,
                                         iconSize: [60, 60],
                                         iconAnchor: [30, 30]
                                     });
                                     
                                     const marker = L.marker(coords, { icon: icon }).addTo(this.map);
                                     marker.bindPopup(`
                                         <div class='bg-slate-800/95 backdrop-blur-xl border border-white/10 rounded-xl p-4 shadow-2xl'>
                                             <h3 class='font-bold text-lg text-white mb-1'>${location.city}</h3>
                                             <p class='text-sm text-slate-400'>${location.count} Şirket Konumu</p>
                                         </div>
                                     `, {
                                         className: 'custom-popup',
                                         closeButton: false
                                     });
                                     this.markers.push(marker);
                                 }
                             });
                         }
                     }"
                     x-init="setTimeout(() => initMap(), 100)">
                     
                    <!-- Legend -->
                    <div class="absolute bottom-4 left-4 z-[1000] bg-black/60 backdrop-blur-md rounded-xl p-4 border border-white/10">
                        <div class="flex items-center gap-2 text-xs text-slate-300">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <span>Şirket Konumu</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-300 mt-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <span>Şirket Sayısı</span>
                        </div>
                    </div>

                    <!-- Total Companies Counter -->
                    <div class="absolute top-4 right-4 z-[1000] bg-black/60 backdrop-blur-md rounded-xl px-4 py-3 border border-white/10" x-data="{ locations: {{ json_encode($companyLocations ?? []) }} }">
                        <div class="text-xs text-slate-400 uppercase tracking-wider mb-1">Toplam Konum</div>
                        <div class="text-2xl font-bold text-white" x-text="locations.length"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Registrations Area -->
        <div class="lg:col-span-4 bg-[#1a2332]/40 border border-white/5 rounded-[2.5rem] backdrop-blur-xl flex flex-col shadow-2xl">
            <div class="p-8 flex justify-between items-center border-b border-white/5">
                <h2 class="text-xl font-extrabold text-white tracking-tight">Son Kayıtlar</h2>
                <a href="#" class="text-blue-500 hover:text-blue-400 text-[11px] font-black uppercase tracking-[0.1em] transition-colors">Tümünü Gör</a>
            </div>
            <div class="flex-1 p-8 space-y-6">
                @foreach($recentCompanies as $rc)
                    <div class="flex items-center justify-between group cursor-pointer hover:translate-x-1 transition-transform duration-300">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-[1.25rem] {{ $rc['bg'] }} {{ $rc['c'] }} flex items-center justify-center font-black text-sm ring-1 ring-white/10 shadow-lg">
                                {{ $rc['init'] }}
                            </div>
                            <div>
                                <div class="text-white text-sm font-bold group-hover:text-blue-400 transition-colors">{{ $rc['n'] }}</div>
                                <div class="text-[10px] text-slate-500 flex items-center gap-2 mt-0.5">
                                    <span class="font-bold uppercase tracking-wider text-slate-400">{{ $rc['p'] }}</span> 
                                    <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                                    <span class="font-medium">{{ $rc['t'] }}</span>
                                </div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-slate-700 group-hover:text-white transition-all text-[20px]">chevron_right</span>
                    </div>
                @endforeach
            </div>
            <div class="p-8 border-t border-white/5 bg-white/[0.02]">
                <button class="w-full bg-blue-600/10 hover:bg-blue-600 hover:text-white text-blue-500 border border-blue-600/20 py-4 rounded-[1.5rem] text-sm font-black uppercase tracking-widest transition-all shadow-xl active:scale-[0.98]">
                    Rapor İndir
                </button>
            </div>
        </div>
    </div>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Custom Map Styling -->
    <style>
        /* Hide default Leaflet popup styling */
        .leaflet-popup-content-wrapper {
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
        }
        .leaflet-popup-tip {
            background: rgba(30, 41, 59, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        .custom-marker {
            background: transparent !important;
            border: none !important;
            overflow: visible !important;
        }
        /* Map container styling */
        #turkeyMap {
            background: #1e293b;
        }
        .leaflet-container {
            background: #1e293b !important;
        }
    </style>
</x-app-layout>
