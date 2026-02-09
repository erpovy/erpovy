<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-white leading-tight flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-400">tune</span>
            {{ __('CRM Yapılandırması') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- V2 Card Design -->
            <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-white/10 shadow-2xl">
                <!-- Background Ambiance -->
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <form action="{{ route('setup.crm.update') }}" method="POST" class="relative z-10 p-8">
                    @csrf
                    @method('PUT')

                    <!-- SECTION 1: General Settings -->
                    <div class="mb-10 pb-8 border-b border-white/5">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                <span class="material-symbols-outlined text-xl">settings</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">Genel Ayarlar</h3>
                                <p class="text-slate-400 text-sm">Temel CRM davranışlarını buradan yönetebilirsiniz.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Pipeline Name -->
                            <div class="group">
                                <label class="block text-sm font-bold text-white mb-2 group-focus-within:text-blue-400 transition-colors">
                                    Varsayılan Boru Hattı (Pipeline) Adı
                                </label>
                                <input type="text" name="default_pipeline_name" value="{{ $settings['crm_settings']['default_pipeline_name'] ?? 'Standart Satış Süreci' }}" 
                                    class="w-full rounded-xl bg-slate-800 border border-white/20 text-white focus:border-blue-500 focus:ring-blue-500 placeholder-slate-400 transition-all hover:bg-slate-700 font-medium">
                                <p class="text-xs text-slate-300 mt-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">info</span>
                                    Ana satış sürecinizin sistemde görünen adıdır.
                                </p>
                            </div>

                            <!-- Auto Assign Toggle -->
                            <div class="flex items-center h-full pt-4">
                                <label class="flex items-center gap-4 cursor-pointer group p-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-white/10 hover:border-blue-500/50 transition-all w-full select-none">
                                    <div class="relative flex items-center">
                                        <input type="hidden" name="auto_assign_leads" value="0">
                                        <input type="checkbox" name="auto_assign_leads" value="1" {{ ($settings['crm_settings']['auto_assign_leads'] ?? false) ? 'checked' : '' }} 
                                            class="peer sr-only">
                                        <div class="w-12 h-7 bg-slate-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500 shadow-inner"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-white group-hover:text-blue-300 transition-colors">Otomatik Atama</span>
                                        <span class="text-xs text-slate-300">Web formlarından gelen talepleri temsilcilere otomatik dağıt.</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: List Managers -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                        
                        <!-- Lead Sources -->
                        <div x-data="listManager('{{ $settings['crm_settings']['lead_source_options'] ?? 'Web Sitesi, Referans, Sosyal Medya, Doğrudan Satış' }}')" class="bg-slate-800 rounded-2xl border border-white/10 p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    <span class="material-symbols-outlined text-lg">source</span>
                                </div>
                                <h3 class="text-lg font-bold text-white">Müşteri Kaynakları</h3>
                            </div>
                            
                            <div class="flex gap-2 mb-4">
                                <input type="text" x-model="newItem" @keydown.enter.prevent="addItem()" placeholder="Yeni kaynak ekle..."
                                    class="flex-1 rounded-xl bg-slate-900 border border-white/20 text-white text-sm focus:border-emerald-500 focus:ring-emerald-500 placeholder-slate-400 font-medium">
                                <button type="button" @click="addItem()" class="bg-emerald-600 hover:bg-emerald-500 text-white p-2.5 rounded-xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-emerald-900/20">
                                    <span class="material-symbols-outlined text-xl">add</span>
                                </button>
                            </div>
                            
                            <div class="flex flex-wrap gap-2 min-h-[50px]">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="bg-emerald-600 text-white pl-4 pr-3 py-2 rounded-xl text-sm font-bold shadow-lg shadow-emerald-900/20 flex items-center gap-2 group hover:bg-emerald-500 hover:scale-105 transition-all cursor-default">
                                        <span x-text="item"></span>
                                        <button type="button" @click="removeItem(index)" class="p-1 rounded-lg bg-emerald-700/50 hover:bg-red-500/80 text-emerald-100 hover:text-white transition-colors">
                                            <span class="material-symbols-outlined text-[16px] font-bold">close</span>
                                        </button>
                                    </div>
                                </template>
                                <span x-show="items.length === 0" class="text-sm text-slate-400 italic p-2 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-lg opacity-70">info</span>
                                    Henüz kaynak eklenmedi.
                                </span>
                            </div>
                            <input type="hidden" name="lead_source_options" :value="items.join(', ')">
                            <p class="text-xs text-slate-300 mt-4 border-t border-white/10 pt-3">
                                Müşterinin size nasıl ulaştığını belirten seçenekler.
                            </p>
                        </div>

                        <!-- Deal Stages -->
                        <div x-data="listManager('{{ $settings['crm_settings']['deal_stages'] ?? 'Yeni Fırsat, Toplantı, Teklif, Pazarlık, Sözleşme' }}')" class="bg-slate-800 rounded-2xl border border-white/10 p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 rounded-lg bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                    <span class="material-symbols-outlined text-lg">steps</span>
                                </div>
                                <h3 class="text-lg font-bold text-white">Anlaşma Aşamaları</h3>
                            </div>

                            <div class="flex gap-2 mb-4">
                                <input type="text" x-model="newItem" @keydown.enter.prevent="addItem()" placeholder="Yeni aşama ekle..."
                                    class="flex-1 rounded-xl bg-slate-900 border border-white/20 text-white text-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400 font-medium">
                                <button type="button" @click="addItem()" class="bg-indigo-600 hover:bg-indigo-500 text-white p-2.5 rounded-xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-indigo-900/20">
                                    <span class="material-symbols-outlined text-xl">add</span>
                                </button>
                            </div>
                            
                            <div class="space-y-2 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="bg-indigo-600 text-white p-3 rounded-xl shadow-lg shadow-indigo-900/20 flex items-center justify-between group hover:bg-indigo-500 hover:scale-[1.02] transition-all cursor-grab active:cursor-grabbing border border-indigo-400/20">
                                        <div class="flex items-center gap-3">
                                            <span class="bg-indigo-800/50 text-white w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black shadow-inner" x-text="index + 1"></span>
                                            <span class="text-white text-sm font-bold tracking-wide" x-text="item"></span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <div class="flex flex-col gap-0.5">
                                                <button type="button" @click="moveUp(index)" class="text-indigo-200 hover:text-white p-0.5 hover:bg-indigo-400/30 rounded" title="Yukarı Taşı" x-show="index > 0">
                                                    <span class="material-symbols-outlined text-[18px]">keyboard_arrow_up</span>
                                                </button>
                                                <button type="button" @click="moveDown(index)" class="text-indigo-200 hover:text-white p-0.5 hover:bg-indigo-400/30 rounded" title="Aşağı Taşı" x-show="index < items.length - 1">
                                                    <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
                                                </button>
                                            </div>
                                            <div class="w-px h-6 bg-indigo-400/30 mx-2"></div>
                                            <button type="button" @click="removeItem(index)" class="text-indigo-200 hover:text-red-100 p-2 hover:bg-red-500 rounded-lg transition-colors" title="Sil">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                <span x-show="items.length === 0" class="text-sm text-slate-400 italic p-2 block text-center">Henüz aşama eklenmedi.</span>
                            </div>
                            <input type="hidden" name="deal_stages" :value="items.join(', ')">
                            <p class="text-xs text-slate-300 mt-4 border-t border-white/10 pt-3">
                                Kanban görünümündeki süreç adımları. Sıralama önemlidir.
                            </p>
                        </div>

                        <!-- Lost Reasons -->
                        <div x-data="listManager('{{ $settings['crm_settings']['lost_reasons'] ?? 'Fiyat Yüksek, Stok Yok, Rakip Tercih Edildi, İletişim Koptu' }}')" class="bg-slate-800 rounded-2xl border border-white/10 p-6 shadow-lg">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 rounded-lg bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                    <span class="material-symbols-outlined text-lg">dangerous</span>
                                </div>
                                <h3 class="text-lg font-bold text-white">Kayıp Nedenleri</h3>
                            </div>

                            <div class="flex gap-2 mb-4">
                                <input type="text" x-model="newItem" @keydown.enter.prevent="addItem()" placeholder="Yeni neden ekle..."
                                    class="flex-1 rounded-xl bg-slate-900 border border-white/20 text-white text-sm focus:border-rose-500 focus:ring-rose-500 placeholder-slate-400 font-medium">
                                <button type="button" @click="addItem()" class="bg-rose-600 hover:bg-rose-500 text-white p-2.5 rounded-xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-rose-900/20">
                                    <span class="material-symbols-outlined text-xl">add</span>
                                </button>
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="bg-rose-600 text-white pl-4 pr-3 py-2 rounded-xl text-sm font-bold shadow-lg shadow-rose-900/20 flex items-center gap-2 group hover:bg-rose-500 hover:scale-105 transition-all cursor-default">
                                    <span x-text="item"></span>
                                    <button type="button" @click="removeItem(index)" class="p-1 rounded-lg bg-rose-700/50 hover:bg-slate-900/50 text-rose-100 hover:text-white transition-colors">
                                        <span class="material-symbols-outlined text-[16px] font-bold">close</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <input type="hidden" name="lost_reasons" :value="items.join(', ')">
                         <p class="text-xs text-slate-300 mt-4 border-t border-white/10 pt-3">
                            Bir anlaşma kaybedildiğinde seçilecek nedenler.
                        </p>
                    </div>

                    <!-- Footer Actions -->
                    <div class="sticky bottom-0 bg-slate-900/95 backdrop-blur-xl py-6 -mx-8 px-8 border-t border-white/10 flex justify-between items-center z-20">
                        <div class="text-xs text-slate-400 font-medium">
                            <span class="text-blue-400">*</span> Değişiklikler anında geçerli olmayabilir, sayfayı yenilemeniz gerekebilir.
                        </div>
                        <div class="flex items-center gap-4">
                            <button type="button" class="text-slate-300 hover:text-white font-medium text-sm transition-colors">
                                Değişiklikleri Geri Al
                            </button>
                            <button type="submit" class="group relative px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl transition-all shadow-lg shadow-blue-900/20 hover:shadow-blue-900/40 hover:-translate-y-0.5">
                                <span class="flex items-center gap-2 font-bold tracking-brand">
                                    <span class="material-symbols-outlined group-hover:animate-pulse">save</span>
                                    AYARLARI KAYDET
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('listManager', (initialString) => ({
                items: initialString ? initialString.split(',').map(s => s.trim()).filter(s => s !== '') : [],
                newItem: '',

                addItem() {
                    if (this.newItem.trim() === '') return;
                    this.items.push(this.newItem.trim());
                    this.newItem = '';
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                moveUp(index) {
                    if (index === 0) return;
                    const temp = this.items[index];
                    this.items[index] = this.items[index - 1];
                    this.items[index - 1] = temp;
                },

                moveDown(index) {
                    if (index === this.items.length - 1) return;
                    const temp = this.items[index];
                    this.items[index] = this.items[index + 1];
                    this.items[index + 1] = temp;
                }
            }));
        });
    </script>
</x-app-layout>
