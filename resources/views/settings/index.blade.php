<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-900 dark:text-white tracking-tight">
            {{ __('Sistem Ayarları') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ activeTab: 'appearance' }">
        <div class="max-w-7xl mx-auto space-y-6">
            
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/50 text-green-400 p-4 rounded-xl flex items-center gap-3 animate-fade-in">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl animate-fade-in">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-symbols-outlined">error</span>
                        <span class="text-sm font-bold">Hata! Lütfen aşağıdaki sorunları düzeltin:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 ml-9">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl flex items-center gap-3 animate-fade-in">
                    <span class="material-symbols-outlined">error</span>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Tab Navigation -->
            <div class="bg-gray-100 dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-2 flex gap-2">
                @if(auth()->user()->is_super_admin)
                <button 
                    @click="activeTab = 'appearance'" 
                    :class="activeTab === 'appearance' ? 'bg-gradient-to-r from-pink-500 to-purple-500 text-white shadow-lg shadow-pink-500/25' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-white/5'"
                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-xl font-bold text-sm transition-all duration-300">
                    <span class="material-symbols-outlined text-[20px]">palette</span>
                    <span>Görünüm</span>
                </button>
                @endif
                
                @if(auth()->user()->is_super_admin)
                <button 
                    @click="activeTab = 'performance'" 
                    :class="activeTab === 'performance' ? 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-500/25' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-white/5'"
                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-xl font-bold text-sm transition-all duration-300">
                    <span class="material-symbols-outlined text-[20px]">speed</span>
                    <span>Performans</span>
                </button>
                @endif
                
                <button 
                    @click="activeTab = 'system'" 
                    :class="activeTab === 'system' ? 'bg-gradient-to-r from-purple-500 to-indigo-500 text-white shadow-lg shadow-purple-500/25' : 'text-gray-500 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-white/5'"
                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-xl font-bold text-sm transition-all duration-300">
                    <span class="material-symbols-outlined text-[20px]">info</span>
                    <span>Sistem Bilgisi</span>
                </button>
            </div>

            <!-- Tab Content -->
            <div class="transition-all duration-300">
                
                <!-- Appearance Settings Tab -->
                @if(auth()->user()->is_super_admin)
                <div x-show="activeTab === 'appearance'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-xl">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white" style="background: linear-gradient(135deg, #ec4899, #a855f7);">
                                    <span class="material-symbols-outlined text-[28px]">palette</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Görünüm Ayarları</h3>
                                    <p class="text-sm text-gray-500 dark:text-slate-400">Logolarınızı ve giriş ekranı arkaplanınızı özelleştirin</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('settings.update-appearance') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                
                                <!-- Collapsed Logo (Favicon) -->
                                <div x-data="{ preview: null }" class="group">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-white mb-3">
                                        <span class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px] text-pink-400">favicon</span>
                                            Menü Kapalı Logo & Favicon
                                        </span>
                                    </label>
                                    
                                    <div class="relative h-64 rounded-2xl bg-gradient-to-br from-slate-900/50 to-slate-800/50 border-2 border-dashed border-slate-700 hover:border-pink-500/50 transition-all cursor-pointer overflow-hidden group-hover:shadow-xl group-hover:shadow-pink-500/10"
                                         style="min-height: 256px;"
                                         @click="$refs.logoCollapsedInput.click()">
                                        
                                        @php $logoCollapsed = \App\Models\Setting::get('logo_collapsed'); @endphp
                                        @if($logoCollapsed)
                                            <img x-show="!preview" src="{{ str_starts_with($logoCollapsed, 'http') ? (str_contains($logoCollapsed, '127.0.0.1') ? preg_replace('/^http:\/\/127\.0\.0\.1:9000/', '', $logoCollapsed) : $logoCollapsed) : '/'.ltrim($logoCollapsed, '/') }}" class="absolute inset-0 w-full h-full object-contain p-8">
                                        @endif
                                        
                                        <img x-show="preview" :src="preview" class="absolute inset-0 w-full h-full object-contain p-8">
                                        
                                        <div :class="preview || '{{ $logoCollapsed }}' ? 'opacity-0 group-hover:opacity-100' : 'opacity-100'" class="absolute inset-0 flex flex-col items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
                                            <span class="material-symbols-outlined text-[48px] text-slate-400 mb-2">upload</span>
                                            <p class="text-xs font-bold text-slate-300 uppercase tracking-wider">Favicon Seç</p>
                                        </div>
                                    </div>
                                    
                                    <input x-ref="logoCollapsedInput" type="file" name="logo_collapsed" class="hidden" accept="image/png, image/jpeg, image/svg+xml, image/x-icon"
                                           @change="if($event.target.files[0]) {
                                               const reader = new FileReader();
                                               reader.onload = (e) => preview = e.target.result;
                                               reader.readAsDataURL($event.target.files[0]);
                                           }">
                                    
                                    <p class="mt-3 text-xs text-slate-500 text-center">
                                        <span class="inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">info</span>
                                            Önerilen: 64x64px (.png veya .ico)
                                        </span>
                                    </p>
                                </div>

                                <!-- Logo (Aydınlık Tema) -->
                                <div x-data="{ preview: null }" class="group">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-white mb-3">
                                        <span class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px] text-orange-400">light_mode</span>
                                            Aydınlık Tema Logosu
                                        </span>
                                    </label>
                                    
                                    <div class="relative h-64 rounded-2xl bg-white border-2 border-dashed border-slate-300 hover:border-orange-500/50 transition-all cursor-pointer overflow-hidden group-hover:shadow-xl group-hover:shadow-orange-500/10"
                                         style="min-height: 256px;"
                                         @click="$refs.logoLightInput.click()">
                                        
                                        @php $logoLight = \App\Models\Setting::get('logo_light'); @endphp
                                        @if($logoLight)
                                            <img x-show="!preview" src="{{ str_starts_with($logoLight, 'http') ? (str_contains($logoLight, '127.0.0.1') ? preg_replace('/^http:\/\/127\.0\.0\.1:9000/', '', $logoLight) : $logoLight) : '/'.ltrim($logoLight, '/') }}" class="absolute inset-0 w-full h-full object-contain p-8">
                                        @endif
                                        
                                        <img x-show="preview" :src="preview" class="absolute inset-0 w-full h-full object-contain p-8">
                                        
                                        <div :class="preview || '{{ $logoLight }}' ? 'opacity-0 group-hover:opacity-100' : 'opacity-100'" class="absolute inset-0 flex flex-col items-center justify-center bg-black/20 backdrop-blur-sm transition-opacity">
                                            <span class="material-symbols-outlined text-[48px] text-slate-600 mb-2">upload</span>
                                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Logo Seç</p>
                                        </div>
                                    </div>
                                    
                                    <input x-ref="logoLightInput" type="file" name="logo_light" class="hidden" accept="image/png, image/jpeg, image/svg+xml"
                                           @change="if($event.target.files[0]) {
                                               const reader = new FileReader();
                                               reader.onload = (e) => preview = e.target.result;
                                               reader.readAsDataURL($event.target.files[0]);
                                           }">
                                    
                                    <p class="mt-3 text-xs text-slate-500 text-center">
                                        <span class="inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">info</span>
                                            Aydınlık modda görünecek geniş logo
                                        </span>
                                    </p>
                                </div>

                                <!-- Logo (Karanlık Tema) -->
                                <div x-data="{ preview: null }" class="group">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-white mb-3">
                                        <span class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px] text-indigo-400">dark_mode</span>
                                            Karanlık Tema Logosu
                                        </span>
                                    </label>
                                    
                                    <div class="relative h-64 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 border-2 border-dashed border-slate-700 hover:border-indigo-500/50 transition-all cursor-pointer overflow-hidden group-hover:shadow-xl group-hover:shadow-indigo-500/10"
                                         style="min-height: 256px;"
                                         @click="$refs.logoDarkInput.click()">
                                        
                                        @php $logoDark = \App\Models\Setting::get('logo_dark'); @endphp
                                        @if($logoDark)
                                            <img x-show="!preview" src="{{ str_starts_with($logoDark, 'http') ? (str_contains($logoDark, '127.0.0.1') ? preg_replace('/^http:\/\/127\.0\.0\.1:9000/', '', $logoDark) : $logoDark) : '/'.ltrim($logoDark, '/') }}" class="absolute inset-0 w-full h-full object-contain p-8">
                                        @endif
                                        
                                        <img x-show="preview" :src="preview" class="absolute inset-0 w-full h-full object-contain p-8">
                                        
                                        <div :class="preview || '{{ $logoDark }}' ? 'opacity-0 group-hover:opacity-100' : 'opacity-100'" class="absolute inset-0 flex flex-col items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
                                            <span class="material-symbols-outlined text-[48px] text-slate-400 mb-2">upload</span>
                                            <p class="text-xs font-bold text-slate-300 uppercase tracking-wider">Logo Seç</p>
                                        </div>
                                    </div>
                                    
                                    <input x-ref="logoDarkInput" type="file" name="logo_dark" class="hidden" accept="image/png, image/jpeg, image/svg+xml"
                                           @change="if($event.target.files[0]) {
                                               const reader = new FileReader();
                                               reader.onload = (e) => preview = e.target.result;
                                               reader.readAsDataURL($event.target.files[0]);
                                           }">
                                    
                                    <p class="mt-3 text-xs text-slate-500 text-center">
                                        <span class="inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">info</span>
                                            Karanlık mod ve Giriş ekranı logosu
                                        </span>
                                    </p>
                                </div>

                                <!-- Login Background -->
                                <div x-data="{ preview: null, isVideo: false }" class="group">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-white mb-3">
                                        <span class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px] text-blue-400">wallpaper</span>
                                            Giriş Ekranı Arkaplanı
                                        </span>
                                    </label>
                                    
                                    <div class="relative h-64 rounded-2xl bg-gradient-to-br from-slate-900/50 to-slate-800/50 border-2 border-dashed border-slate-700 hover:border-blue-500/50 transition-all cursor-pointer overflow-hidden group-hover:shadow-xl group-hover:shadow-blue-500/10"
                                         style="min-height: 256px;"
                                         @click="$refs.loginBackgroundInput.click()">
                                        
                                        @if(isset($loginBackground) && $loginBackground)
                                            <div x-show="!preview" class="absolute inset-0">
                                                @if(Str::endsWith($loginBackground, ['.mp4', '.webm']))
                                                    <video src="{{ str_starts_with($loginBackground, 'http') ? (str_contains($loginBackground, '127.0.0.1') ? preg_replace('/^http:\/\/127\.0\.0\.1:9000/', '', $loginBackground) : $loginBackground) : '/'.ltrim($loginBackground, '/') }}" class="w-full h-full object-cover" muted loop autoplay></video>
                                                @else
                                                    <img src="{{ str_starts_with($loginBackground, 'http') ? (str_contains($loginBackground, '127.0.0.1') ? preg_replace('/^http:\/\/127\.0\.0\.1:9000/', '', $loginBackground) : $loginBackground) : '/'.ltrim($loginBackground, '/') }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                        @endif
                                        
                                        <div x-show="preview" class="absolute inset-0">
                                            <template x-if="!isVideo">
                                                <img :src="preview" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="isVideo">
                                                <video :src="preview" class="w-full h-full object-cover" muted loop autoplay></video>
                                            </template>
                                        </div>
                                        
                                        <div :class="preview || '{{ $loginBackground ?? '' }}' ? 'opacity-0 group-hover:opacity-100' : 'opacity-100'" class="absolute inset-0 flex flex-col items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
                                            <span class="material-symbols-outlined text-[48px] text-slate-400 mb-2">upload</span>
                                            <p class="text-xs font-bold text-slate-300 uppercase tracking-wider">Dosya Seç</p>
                                        </div>
                                    </div>
                                    
                                    <input x-ref="loginBackgroundInput" type="file" name="login_background" class="hidden" accept="image/*,video/mp4,video/webm"
                                           @change="if($event.target.files[0]) {
                                               const file = $event.target.files[0];
                                               isVideo = file.type.startsWith('video/');
                                               const reader = new FileReader();
                                               reader.onload = (e) => preview = e.target.result;
                                               reader.readAsDataURL(file);
                                           }">
                                    
                                    <p class="mt-3 text-xs text-slate-500 text-center">
                                        <span class="inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">info</span>
                                            Resim veya Video
                                        </span>
                                    </p>

                                    <!-- Remove/Cancel Actions -->
                                    <div class="flex justify-center mt-3 gap-2">
                                        <input type="hidden" name="remove_login_background" id="remove_login_background_input" value="0">
                                        
                                        @if(isset($loginBackground) && $loginBackground)
                                            <button type="button" 
                                                    x-show="!preview"
                                                    @click="if(confirm('Mevcut arka planı kaldırmak istediğinize emin misiniz?')) { document.getElementById('remove_login_background_input').value = '1'; $el.closest('form').submit(); }"
                                                    class="flex items-center gap-1.5 text-xs font-bold text-red-400 hover:text-red-300 transition-colors bg-red-500/10 hover:bg-red-500/20 px-4 py-2 rounded-lg border border-red-500/20">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                                Mevcut Videoyu Kaldır
                                            </button>
                                        @endif
                                        
                                        <button type="button" 
                                                x-show="preview" 
                                                @click="preview = null; $refs.loginBackgroundInput.value = '';"
                                                class="flex items-center gap-1.5 text-xs font-bold text-slate-400 hover:text-white transition-colors bg-slate-800 hover:bg-slate-700 px-4 py-2 rounded-lg border border-slate-700">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                            Seçimi İptal Et
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-white/10">
                                <button type="submit" class="flex items-center gap-2 py-3 px-8 rounded-xl bg-gradient-to-r from-pink-600 to-purple-600 text-white font-bold text-sm transition-all hover:scale-105 hover:shadow-lg hover:shadow-pink-600/25 active:scale-95">
                                    <span class="material-symbols-outlined text-[20px]">save</span>
                                    Değişiklikleri Kaydet
                                </button>
                            </div>
                        </form>
                    </x-card>
                </div>
                @endif

                <!-- Performance Tab -->
                @if(auth()->user()->is_super_admin)
                <div x-show="activeTab === 'performance'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-xl">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white" style="background: linear-gradient(135deg, #3b82f6, #06b6d4);">
                                    <span class="material-symbols-outlined text-[28px]">speed</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Performans & Önbellek</h3>
                                    <p class="text-sm text-gray-500 dark:text-slate-400">Sistem önbelleğini yönetin ve performansı optimize edin</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                            <div class="bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-white/5 rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px] text-green-500">database</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-slate-400 uppercase tracking-wider font-bold">Önbellek</p>
                                        <p class="text-sm text-gray-900 dark:text-white font-bold">Uygulama</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-white/5 rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px] text-blue-500">visibility</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-slate-400 uppercase tracking-wider font-bold">Görünüm</p>
                                        <p class="text-sm text-gray-900 dark:text-white font-bold">Görünüm</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-white/5 rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px] text-purple-500">settings</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-slate-400 uppercase tracking-wider font-bold">Ayarlar</p>
                                        <p class="text-sm text-gray-900 dark:text-white font-bold">Yapılandırma</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-white/5 rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[20px] text-orange-500">route</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-slate-400 uppercase tracking-wider font-bold">Rotalar</p>
                                        <p class="text-sm text-gray-900 dark:text-white font-bold">Rotalar</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-blue-500/10 to-cyan-500/10 border border-blue-500/20 rounded-2xl p-6 mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-slate-400 mb-1">Toplam Önbellek Boyutu</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $cacheSize }}</p>
                                </div>
                                <div class="w-16 h-16 rounded-2xl bg-blue-500/20 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[32px] text-blue-400">storage</span>
                                </div>
                            </div>
                            <div class="h-2 bg-slate-900/50 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full" style="width: 35%"></div>
                            </div>
                        </div>

                        <form action="{{ route('settings.clear-cache') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 px-8 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold text-sm transition-all hover:scale-[1.02] hover:shadow-xl hover:shadow-blue-600/25 active:scale-[0.98]">
                                <span class="material-symbols-outlined text-[24px]">delete_sweep</span>
                                Önbelleği Temizle
                            </button>
                        </form>
                    </x-card>
                </div>
                @endif

                <!-- System Info Tab -->
                <div x-show="activeTab === 'system'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <x-card class="p-8 lg:p-12 border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 backdrop-blur-xl">
                        <!-- Header with Icon -->
                        <div class="mb-12">
                            <div class="flex items-center gap-4 mb-3">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl blur-xl opacity-50 animate-pulse"></div>
                                    <div class="relative w-16 h-16 rounded-2xl flex items-center justify-center text-white shadow-2xl shadow-purple-500/30" style="background: linear-gradient(135deg, #a855f7, #6366f1);">
                                        <span class="material-symbols-outlined text-[32px]">info</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Sistem Bilgisi</h3>
                                    <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">Sunucu ve uygulama detayları</p>
                                </div>
                            </div>
                        </div>

                        <!-- System Info Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                            <!-- Version Card -->
                            <div class="group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-indigo-500/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="relative bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:border-purple-500/30 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-purple-500/20 group-hover:-translate-y-1">
                                    <div class="flex items-start justify-between mb-6">
                                        <div class="flex-1">
                                            <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-2 flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[14px] text-purple-400">package_2</span>
                                                Versiyon
                                            </p>
                                            <p class="text-4xl font-black text-gray-900 dark:text-white mb-1 tracking-tight">{{ $appVersion['version'] ?? '1.0.4' }}</p>
                                            <p class="text-xs text-gray-400">Uygulama Sürümü</p>
                                        </div>
                                    </div>
                                    <div class="h-1 w-full bg-slate-800/50 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- PHP Card -->
                            <div class="group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="relative bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:border-blue-500/30 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-blue-500/20 group-hover:-translate-y-1">
                                    <div class="flex items-start justify-between mb-6">
                                        <div class="flex-1">
                                            <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-2 flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[14px] text-blue-400">code</span>
                                                PHP
                                            </p>
                                            <p class="text-4xl font-black text-gray-900 dark:text-white mb-1 tracking-tight">{{ PHP_VERSION }}</p>
                                            <p class="text-xs text-gray-400">Sistem Dili</p>
                                        </div>
                                    </div>
                                    <div class="h-1 w-full bg-slate-800/50 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Environment Card -->
                            <div class="group relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <div class="relative bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:border-green-500/30 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-green-500/20 group-hover:-translate-y-1">
                                    <div class="flex items-start justify-between mb-6">
                                        <div class="flex-1">
                                            <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-2 flex items-center gap-2">
                                                <span class="material-symbols-outlined text-[14px] text-green-400">dns</span>
                                                Ortam
                                            </p>
                                            <p class="text-3xl font-black text-gray-900 dark:text-white mb-1 tracking-tight">{{ config('app.env') === 'local' ? 'ABS' : strtoupper(config('app.env')) }}</p>
                                            <p class="text-xs text-gray-400">{{ config('app.env') === 'local' ? 'Artovy Beta Servers' : 'Sunucu Modu' }}</p>
                                        </div>
                                    </div>
                                    <div class="h-1 w-full bg-slate-800/50 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" style="width: 90%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Technologies Section -->
                        <div class="relative">
                            <!-- Section Header -->
                            <div class="flex items-center gap-3 mb-8">
                                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                                <h4 class="text-xl font-black text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[24px] text-purple-400">stacks</span>
                                    Proje Teknolojileri
                                </h4>
                                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                            </div>

                            <!-- Technology Cards -->
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Laravel -->
                                <div class="group relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/0 to-red-500/0 group-hover:from-red-500/10 group-hover:to-orange-500/10 rounded-2xl transition-all duration-500"></div>
                                    <div class="relative bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:border-red-500/30 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-red-500/20 group-hover:-translate-y-2">
                                        <div class="flex flex-col items-center text-center gap-4">
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-red-500/30 rounded-xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                                <div class="relative w-16 h-16 rounded-xl bg-gradient-to-br from-red-500/20 to-orange-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-red-500/30">
                                                    <svg class="w-8 h-8" style="color: #FF2D20;" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.96 1.41a1.27 1.27 0 0 0-.71.21L4.3 6.3a1.41 1.41 0 0 0-.69 1.15V17a1.49 1.49 0 0 0 .76 1.29l6.95 3.9a1.27 1.27 0 0 0 1.34 0l6.95-3.9a1.49 1.49 0 0 0 .76-1.29V7.45a1.41 1.41 0 0 0-.69-1.15L12.67 1.62a1.27 1.27 0 0 0-.71-.21ZM6.11 7.6l5.85-3.23 5.85 3.23v8.8L11.96 19.6l-5.85-3.2Z"/>
                                                        <path d="M11.96 11.23a1.28 1.28 0 0 0-.64.17L7.74 13.5a.77.77 0 0 0 .78 1.33l2.8-1.63v4.49a.77.77 0 0 0 1.54 0v-5.26a.71.71 0 0 0-.25-.56.66.66 0 0 0-.65-.08Z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-slate-500 font-bold uppercase tracking-wider mb-1">Altyapi</p>
                                                <p class="text-lg text-gray-900 dark:text-white font-black">Laravel 12</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Livewire -->
                                <div class="group relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/0 to-purple-500/0 group-hover:from-fuchsia-500/10 group-hover:to-purple-500/10 rounded-2xl transition-all duration-500"></div>
                                    <div class="relative bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:border-fuchsia-500/30 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-fuchsia-500/20 group-hover:-translate-y-2">
                                        <div class="flex flex-col items-center text-center gap-4">
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-fuchsia-500/30 rounded-xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                                <div class="relative w-16 h-16 rounded-xl bg-gradient-to-br from-fuchsia-500/20 to-purple-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-fuchsia-500/30">
                                                    <svg class="w-8 h-8" style="color: #FB7185;" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12 2C14.7 2 17 4.3 17 7C17 9.2 15.6 11.2 13.6 11.8L16.2 18.2C16.4 18.7 16 19.2 15.5 19.2C15.2 19.2 14.9 19 14.8 18.8L12.2 12.3C12.1 12.3 12.1 12.4 12 12.4C9.3 12.4 7 10.1 7 7.4C7 4.7 9.3 2.4 12 2.4V2ZM12 4C10.3 4 9 5.3 9 7C9 8.7 10.3 10 12 10C13.7 10 15 8.7 15 7C15 5.3 13.7 4 12 4Z"/>
                                                        <path d="M5 21C5 21 6.5 18 12 18C17.5 18 19 21 19 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-slate-500 font-bold uppercase tracking-wider mb-1">Tam Yığın</p>
                                                <p class="text-lg text-gray-900 dark:text-white font-black">Livewire 3</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alpine.js -->
                                <div class="group relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/0 to-teal-500/0 group-hover:from-emerald-500/10 group-hover:to-teal-500/10 rounded-2xl transition-all duration-500"></div>
                                    <div class="relative bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:border-emerald-500/30 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-emerald-500/20 group-hover:-translate-y-2">
                                        <div class="flex flex-col items-center text-center gap-4">
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-emerald-500/30 rounded-xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                                <div class="relative w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-emerald-500/30">
                                                    <svg class="w-8 h-8" style="color: #10B981;" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12 2L2 19H22L12 2ZM12 6.3L18.4 17H5.6L12 6.3Z"/>
                                                        <path d="M16 12L20.3 19H11.7L16 12Z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-slate-500 font-bold uppercase tracking-wider mb-1">Ön Yüz</p>
                                                <p class="text-lg text-gray-900 dark:text-white font-black">Alpine.js</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tailwind CSS -->
                                <div class="group relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/0 to-blue-500/0 group-hover:from-cyan-500/10 group-hover:to-blue-500/10 rounded-2xl transition-all duration-500"></div>
                                    <div class="relative bg-white dark:bg-white/5 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-6 hover:border-cyan-500/30 transition-all duration-300 group-hover:shadow-2xl group-hover:shadow-cyan-500/20 group-hover:-translate-y-2">
                                        <div class="flex flex-col items-center text-center gap-4">
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-cyan-500/30 rounded-xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                                <div class="relative w-16 h-16 rounded-xl bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-500 border border-cyan-500/30">
                                                    <svg class="w-8 h-8" style="color: #06B6D4;" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M18.5 5C16.5 5 15.5 6.5 14.5 8C13.5 9.5 12.5 11 10.5 11C8.5 11 7.5 9.5 6.5 8C5.5 6.5 4.5 5 2.5 5C1 5 0 6 0 7.5C0 9 1 10 2.5 10C4.5 10 5.5 8.5 6.5 7C7.5 5.5 8.5 4 10.5 4C12.5 4 13.5 5.5 14.5 7C15.5 8.5 16.5 10 18.5 10C20 10 21 9 21 7.5C21 6 20 5 18.5 5Z"/>
                                                        <path d="M10.5 13C8.5 13 7.5 14.5 6.5 16C5.5 17.5 4.5 19 2.5 19C1 19 0 20 0 21.5C0 23 1 24 2.5 24C4.5 24 5.5 22.5 6.5 21C7.5 19.5 8.5 18 10.5 18C12.5 18 13.5 19.5 14.5 21C15.5 22.5 16.5 24 18.5 24C20 24 21 23 21 21.5C21 20 20 19 18.5 19C16.5 19 15.5 17.5 14.5 16C13.5 14.5 12.5 13 10.5 13Z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-slate-500 font-bold uppercase tracking-wider mb-1">Tasarım</p>
                                                <p class="text-lg text-gray-900 dark:text-white font-black">Tailwind 4</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
