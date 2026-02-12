<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Şirket Düzenle: {{ $company->name }}
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">edit_square</span>
                        Şirket Bilgilerini ve Modül Erişimlerini Güncelleyin
                    </p>
                </div>
                
                <a href="{{ route('superadmin.companies.index') }}" class="group flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-bold text-sm transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Listeye Dön
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                    <div class="p-8 border-b border-gray-200 dark:border-white/5">
                        <h2 class="text-gray-900 dark:text-white font-black text-base uppercase tracking-tight">Şirket Bilgilerini Düzenle</h2>
                        <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-1">Şirket temel bilgilerini güncelleyin</p>
                    </div>

                    @if(session('success'))
                        <div class="m-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-2xl flex items-center gap-3">
                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                            <span class="text-sm font-bold uppercase tracking-tight">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="m-8 bg-rose-500/10 border border-rose-500/20 text-rose-500 p-4 rounded-2xl flex items-center gap-3">
                            <span class="material-symbols-outlined text-[20px]">error</span>
                            <span class="text-sm font-bold uppercase tracking-tight">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('superadmin.companies.update', $company) }}" method="POST" class="p-8">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Company Name -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Şirket Unvanı</label>
                                <input type="text" name="name" value="{{ old('name', $company->name) }}" 
                                       class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all font-bold" 
                                       placeholder="Örn: ABC Teknoloji A.Ş." required>
                                @error('name')
                                    <p class="mt-1 text-[10px] font-black uppercase text-rose-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Domain -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Alt Alan Adı (Domain)</label>
                                <input type="text" name="domain" value="{{ old('domain', $company->domain) }}" 
                                       class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all font-bold" 
                                       placeholder="Örn: abc-tech">
                                <p class="mt-1 text-[10px] text-slate-500 font-bold uppercase tracking-tight ml-1">Boş bırakılabilir. Çoklu tenant yapısı için kullanılır.</p>
                                @error('domain')
                                    <p class="mt-1 text-[10px] font-black uppercase text-rose-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 ml-1">Durum</label>
                                <select name="status" class="w-full px-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white focus:border-primary/50 focus:ring-0 transition-all font-bold appearance-none">
                                    <option value="active" {{ old('status', $company->status) == 'active' ? 'selected' : '' }} class="bg-white dark:bg-slate-900">Aktif</option>
                                    <option value="suspended" {{ old('status', $company->status) == 'suspended' ? 'selected' : '' }} class="bg-white dark:bg-slate-900">Askıya Alınmış</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-[10px] font-black uppercase text-rose-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            @if($registrant)
                            <!-- Registrant Info -->
                            <div class="p-6 rounded-2xl bg-primary/5 border border-primary/20 space-y-4">
                                <div class="flex items-center gap-2 text-primary">
                                    <span class="material-symbols-outlined text-[20px]">person_pin_circle</span>
                                    <span class="text-xs font-black uppercase tracking-[0.2em]">Kayıt Eden Kullanıcı</span>
                                </div>
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">Ad Soyad</p>
                                        <p class="text-sm text-gray-900 dark:text-white font-bold">{{ $registrant->name }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest">E-posta</p>
                                        <p class="text-sm text-gray-900 dark:text-white font-bold">{{ $registrant->email }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="flex justify-between items-center pt-8 border-t border-gray-200 dark:border-white/5">
                                <div class="flex gap-4 w-full">
                                    <a href="{{ route('superadmin.companies.index') }}" class="flex-1 text-center px-6 py-4 rounded-xl border border-gray-200 dark:border-white/10 text-slate-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-all font-black text-xs uppercase tracking-widest">
                                        İptal
                                    </a>
                                    <button type="submit" class="flex-[2] bg-primary text-gray-900 dark:text-white px-8 py-4 rounded-xl hover:scale-[1.02] active:scale-[0.98] shadow-[0_0_20px_rgba(var(--color-primary),0.3)] transition-all font-black text-xs uppercase tracking-[0.2em]">
                                        ŞİRKETİ GÜNCELLE
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </x-card>

                <!-- Module Management Section -->
                <x-card class="border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden h-fit">
                    <div class="p-8 border-b border-gray-200 dark:border-white/5 bg-primary/5">
                        <h2 class="text-gray-900 dark:text-white font-black text-base uppercase tracking-tight">Modül & Menü Yönetimi</h2>
                        <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-1">Şirket paneli için aktif menü öğelerini ve özellikleri belirleyin</p>
                    </div>

                    <div class="p-8 space-y-4" x-data="{ openGroup: null }">
                        @php $activeModules = $company->settings['modules'] ?? []; @endphp
                        
                        @foreach($menuGroups as $groupId => $group)
                            @php 
                                $groupItems = array_keys($group['items']);
                                $isGroupAllActive = count(array_intersect($groupItems, $activeModules)) === count($groupItems);
                                $isGroupPartiallyActive = count(array_intersect($groupItems, $activeModules)) > 0;
                            @endphp
                            <div class="border border-gray-200 dark:border-white/5 rounded-2xl overflow-hidden bg-white/5 transition-all duration-300"
                                :class="openGroup === '{{ $groupId }}' ? 'ring-2 ring-primary/20 bg-primary/5' : ''">
                                <!-- Group Header -->
                                <div class="flex items-center justify-between p-5 hover:bg-white/5 transition-all group cursor-pointer"
                                     @click="openGroup = (openGroup === '{{ $groupId }}' ? null : '{{ $groupId }}')">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-sm">
                                            <span class="material-symbols-outlined text-[24px]">{{ $group['icon'] }}</span>
                                        </div>
                                        <div class="text-left">
                                            <h3 class="font-black text-gray-900 dark:text-white text-sm uppercase tracking-tight">{{ $group['name'] }}</h3>
                                            <p class="text-[9px] text-slate-500 uppercase tracking-widest font-black">
                                                {{ count($group['items']) }} Alternatif Öğe
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-6">
                                        <!-- Master Toggle for Group -->
                                        <form action="{{ route('superadmin.companies.toggle-module', $company) }}" method="POST" @click.stop>
                                            @csrf
                                            <input type="hidden" name="module" value="{{ implode(',', $groupItems) . ',' . $groupId }}">
                                            <input type="hidden" name="is_group" value="1">
                                            <button type="submit" 
                                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-all duration-300 focus:outline-none {{ $isGroupAllActive ? 'bg-primary shadow-[0_0_15px_rgba(var(--color-primary),0.5)]' : ($isGroupPartiallyActive ? 'bg-primary/40' : 'bg-slate-700') }}">
                                                <span aria-hidden="true" 
                                                      class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-300 {{ $isGroupAllActive ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                            </button>
                                        </form>
                                        <span class="material-symbols-outlined text-slate-500 transition-transform duration-300" 
                                              :class="openGroup === '{{ $groupId }}' ? 'rotate-180' : ''">expand_more</span>
                                    </div>
                                </div>

                                <!-- Group Items -->
                                <div x-show="openGroup === '{{ $groupId }}'" 
                                     x-collapse 
                                     class="border-t border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-black/20">
                                    <div class="p-4 space-y-2">
                                        @foreach($group['items'] as $itemId => $itemName)
                                            @php $isActive = in_array($itemId, $activeModules); @endphp
                                            <div class="flex items-center justify-between p-4 rounded-xl hover:bg-white/10 transition-all group/item bg-white/5 border border-transparent hover:border-white/10">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-2 h-2 rounded-full {{ $isActive ? 'bg-primary shadow-[0_0_8px_rgba(var(--color-primary),0.8)]' : 'bg-slate-700' }}"></div>
                                                    <span class="text-xs uppercase tracking-tight transition-all {{ $isActive ? 'text-gray-900 dark:text-white font-black' : 'text-slate-500 group-hover/item:text-slate-400' }}">
                                                        {{ $itemName }}
                                                    </span>
                                                </div>

                                                <form action="{{ route('superadmin.companies.toggle-module', $company) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="module" value="{{ $itemId }}">
                                                    <button type="submit" 
                                                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-all duration-300 focus:outline-none {{ $isActive ? 'bg-primary' : 'bg-slate-800' }}">
                                                        <span aria-hidden="true" 
                                                              class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-300 {{ $isActive ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Danger Zone -->
                    <div class="p-8 border-t border-gray-200 dark:border-white/5 bg-rose-500/5">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="material-symbols-outlined text-rose-500 text-[20px] animate-pulse">warning</span>
                            <h3 class="text-xs font-black text-rose-500 uppercase tracking-[0.2em]">Kritik Alan</h3>
                        </div>
                        <form action="{{ route('superadmin.companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Bu şirketi tamamen silmek istediğinize emin misiniz? Bu işlem geri alınamaz!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-3 px-6 py-4 rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition-all duration-300 font-black text-xs uppercase tracking-[0.2em] w-full justify-center border border-rose-500/20 shadow-sm hover:shadow-rose-500/25">
                                <span class="material-symbols-outlined text-[20px]">delete_forever</span>
                                ŞİRKETİ KALICI OLARAK SİL
                            </button>
                        </form>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
