<x-app-layout>
    <x-slot name="header">
        Yeni Hesap Kartı
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Yeni Hesap Oluştur</h1>
            <p class="text-sm text-gray-600 dark:text-slate-400">Tek Düzen Hesap Planı'na göre yeni bir hesap kartı oluşturun.</p>
        </div>

        <form action="{{ route('accounting.accounts.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form Card -->
                <div class="lg:col-span-2">
                    <x-card class="p-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">account_balance</span>
                            Hesap Bilgileri
                        </h2>

                        <div class="space-y-6">
                            <!-- Code -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                                    Hesap Kodu <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 dark:text-slate-500 text-[20px]">tag</span>
                                    <input type="text" 
                                           name="code" 
                                           value="{{ old('code') }}" 
                                           class="w-full pl-11 pr-4 py-3 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
                                           placeholder="Örn: 120"
                                           required>
                                </div>
                                <p class="mt-2 text-xs text-gray-500 dark:text-slate-500 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">info</span>
                                    Tek Düzen Hesap Planı'na uygun kod giriniz (örn: 100, 120, 600)
                                </p>
                                @error('code')
                                    <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                                    Hesap Adı <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 dark:text-slate-500 text-[20px]">description</span>
                                    <input type="text" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           class="w-full pl-11 pr-4 py-3 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" 
                                           placeholder="Örn: Alıcılar, Satıcılar, Kasa"
                                           required>
                                </div>
                                @error('name')
                                    <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                                    Hesap Türü <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 dark:text-slate-500 text-[20px]">category</span>
                                    <select name="type" 
                                            class="w-full pl-11 pr-10 py-3 rounded-xl bg-white dark:bg-slate-900/50 border border-gray-300 dark:border-white/10 text-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all appearance-none cursor-pointer">
                                        @foreach(\Modules\Accounting\Models\Account::getTypes() as $key => $label)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }} class="bg-white dark:bg-slate-900 text-gray-900 dark:text-white py-2">
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-400 dark:text-slate-500 text-[20px] pointer-events-none">expand_more</span>
                                </div>
                                @error('type')
                                    <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Help Card -->
                <div class="lg:col-span-1">
                    <x-card class="p-6 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 border-2 border-blue-200 dark:border-blue-800/50">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">help</span>
                            Yardım
                        </h3>
                        
                        <div class="space-y-4 text-xs text-gray-700 dark:text-slate-300">
                            <div class="flex gap-2">
                                <span class="material-symbols-outlined text-[16px] text-blue-600 dark:text-blue-400 flex-shrink-0">lightbulb</span>
                                <div>
                                    <p class="font-semibold mb-1">Hesap Kodu Örnekleri:</p>
                                    <ul class="space-y-1 text-gray-600 dark:text-slate-400">
                                        <li>• 100 - Kasa</li>
                                        <li>• 120 - Alıcılar</li>
                                        <li>• 320 - Satıcılar</li>
                                        <li>• 600 - Yurt İçi Satışlar</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <span class="material-symbols-outlined text-[16px] text-blue-600 dark:text-blue-400 flex-shrink-0">info</span>
                                <div>
                                    <p class="font-semibold mb-1">Hesap Türleri:</p>
                                    <p class="text-gray-600 dark:text-slate-400">Hesap türü, hesabın mali tablolarda nerede görüneceğini belirler.</p>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <span class="material-symbols-outlined text-[16px] text-blue-600 dark:text-blue-400 flex-shrink-0">verified</span>
                                <div>
                                    <p class="font-semibold mb-1">Tek Düzen Hesap Planı:</p>
                                    <p class="text-gray-600 dark:text-slate-400">Türkiye'de yasal olarak zorunlu olan standart hesap planıdır.</p>
                                </div>
                            </div>
                        </div>
                    </x-card>

                    <!-- Quick Links -->
                    <x-card class="p-4 mt-4">
                        <h4 class="text-xs font-bold text-gray-700 dark:text-slate-300 mb-3 uppercase tracking-wide">Hızlı Erişim</h4>
                        <div class="space-y-2">
                            <a href="{{ route('accounting.accounts.index') }}" class="flex items-center gap-2 text-xs text-gray-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[16px]">list</span>
                                Hesap Listesi
                            </a>
                            <a href="#" class="flex items-center gap-2 text-xs text-gray-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[16px]">book</span>
                                Hesap Planı Rehberi
                            </a>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Action Buttons -->
            <x-card class="p-6">
                <div class="flex justify-between items-center">
                    <a href="{{ route('accounting.accounts.index') }}" 
                       class="flex items-center gap-2 px-6 py-3 rounded-xl border-2 border-gray-200 dark:border-white/10 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-all font-semibold">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                        İptal
                    </a>
                    <button type="submit" 
                            class="flex items-center gap-2 bg-gradient-to-r from-primary to-blue-600 text-white px-8 py-3 rounded-xl hover:shadow-xl hover:scale-105 active:scale-95 transition-all font-bold shadow-lg">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        Hesabı Kaydet
                    </button>
                </div>
            </x-card>
        </form>
    </div>
</x-app-layout>
