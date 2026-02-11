<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 via-emerald-500/5 to-green-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Yeni Çek
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">receipt</span>
                        Çek Bilgilerini Girin
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-4xl px-6 lg:px-8">
            
            <form action="{{ route('accounting.cheques.store') }}" method="POST">
                @csrf
                
                <x-card class="p-8 border-gray-200 dark:border-white/10 bg-white/80 dark:bg-white/5 backdrop-blur-2xl">
                    
                    <!-- Çek Tipi -->
                    <div class="mb-6" x-data="{ type: '{{ old('type', request('type')) }}' }">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-3">Çek Tipi *</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="group relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-300 outline-none"
                                   :class="type === 'received' ? 'border-green-500 bg-green-500/20 shadow-[0_0_30px_rgba(34,197,94,0.4)] scale-[1.02] ring-2 ring-green-500 ring-offset-2 ring-offset-[#0f172a] z-10' : (type && type !== 'received' ? 'border-white/5 bg-transparent opacity-40 grayscale scale-95' : 'border-white/10 hover:border-green-500/50 hover:bg-green-500/5')">
                                <input type="radio" name="type" value="received" class="sr-only" x-model="type" required>
                                <div class="flex items-center gap-3 w-full">
                                    <div class="p-3 rounded-xl transition-all duration-300 shadow-inner" :class="type === 'received' ? 'bg-green-500 text-white' : 'bg-white/5 text-slate-400 group-hover:text-green-400'">
                                        <span class="material-symbols-outlined text-[32px]">arrow_downward</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-lg transition-colors" :class="type === 'received' ? 'text-green-400' : 'text-gray-900 dark:text-white group-hover:text-green-400'">Alınan Çek</p>
                                        <p class="text-sm transition-colors" :class="type === 'received' ? 'text-green-200/70' : 'text-gray-600 dark:text-slate-400'">Müşteriden alınan</p>
                                    </div>
                                    <div class="ml-auto transition-all duration-300" :class="type === 'received' ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-2'">
                                        <div class="bg-green-500 rounded-full p-1">
                                            <span class="material-symbols-outlined text-white text-[20px] font-bold">check</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="group relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-300 outline-none"
                                   :class="type === 'issued' ? 'border-red-500 bg-red-500/20 shadow-[0_0_30px_rgba(239,68,68,0.4)] scale-[1.02] ring-2 ring-red-500 ring-offset-2 ring-offset-[#0f172a] z-10' : (type && type !== 'issued' ? 'border-white/5 bg-transparent opacity-40 grayscale scale-95' : 'border-white/10 hover:border-red-500/50 hover:bg-red-500/5')">
                                <input type="radio" name="type" value="issued" class="sr-only" x-model="type" required>
                                <div class="flex items-center gap-3 w-full">
                                    <div class="p-3 rounded-xl transition-all duration-300 shadow-inner" :class="type === 'issued' ? 'bg-red-500 text-white' : 'bg-white/5 text-slate-400 group-hover:text-red-400'">
                                        <span class="material-symbols-outlined text-[32px]">arrow_upward</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-lg transition-colors" :class="type === 'issued' ? 'text-red-400' : 'text-gray-900 dark:text-white group-hover:text-red-400'">Verilen Çek</p>
                                        <p class="text-sm transition-colors" :class="type === 'issued' ? 'text-red-200/70' : 'text-gray-600 dark:text-slate-400'">Tedarikçiye verilen</p>
                                    </div>
                                    <div class="ml-auto transition-all duration-300" :class="type === 'issued' ? 'opacity-100 translate-x-0' : 'opacity-0 -translate-x-2'">
                                         <div class="bg-red-500 rounded-full p-1">
                                            <span class="material-symbols-outlined text-white text-[20px] font-bold">check</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Çek Numarası -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Çek Numarası *</label>
                            <input type="text" name="cheque_number" value="{{ old('cheque_number') }}" required
                                   class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('cheque_number')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Banka Adı -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Banka Adı *</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                                   class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Şube -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Şube</label>
                            <input type="text" name="branch" value="{{ old('branch') }}"
                                   class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <!-- Hesap No -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Hesap Numarası</label>
                            <input type="text" name="account_number" value="{{ old('account_number') }}"
                                   class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <!-- Keşideci -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Keşideci *</label>
                            <input type="text" name="drawer" value="{{ old('drawer') }}" required
                                   class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('drawer')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ciro Eden -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Ciro Eden</label>
                            <input type="text" name="endorser" value="{{ old('endorser') }}"
                                   class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>

                        <!-- Tutar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Tutar *</label>
                            <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required
                                   class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('amount')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Para Birimi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Para Birimi *</label>
                            <select name="currency" required class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500">
                                <option value="TRY" {{ old('currency', 'TRY') == 'TRY' ? 'selected' : '' }}>TRY (₺)</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                            </select>
                        </div>

                        <!-- Keşide Tarihi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Keşide Tarihi *</label>
                            <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required
                                   class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500">
                            @error('issue_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Vade Tarihi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Vade Tarihi *</label>
                            <input type="date" name="due_date" value="{{ old('due_date') }}" required
                                   class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500">
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cari Hesap -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Cari Hesap</label>
                            <select name="contact_id" class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500">
                                <option value="">Seçiniz...</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}" {{ old('contact_id') == $contact->id ? 'selected' : '' }}>
                                        {{ $contact->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Notlar -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-900 dark:text-slate-300 mb-2">Notlar</label>
                            <textarea name="notes" rows="3" 
                                      class="w-full px-4 py-3 bg-white dark:bg-white/5 border border-gray-300 dark:border-white/10 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Butonlar -->
                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-white/10">
                        <a href="{{ route('accounting.cheques.index') }}" class="px-6 py-3 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-900 dark:text-white rounded-lg font-medium transition-all">
                            İptal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white rounded-lg font-semibold shadow-lg shadow-green-500/30 transition-all">
                            Çeki Kaydet
                        </button>
                    </div>

                </x-card>
            </form>

        </div>
    </div>
</x-app-layout>
