<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-indigo-500/5 to-blue-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-white tracking-tight mb-1">
                        Çek Düzenle
                    </h2>
                    <p class="text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">edit</span>
                        Çek Bilgilerini Güncelle
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-4xl px-6 lg:px-8">
            
            <form action="{{ route('accounting.cheques.update', $cheque) }}" method="POST">
                @csrf
                @method('PUT')
                
                <x-card class="p-8 border-white/10 bg-white/5 backdrop-blur-2xl">
                    
                    <!-- Çek Tipi (Readonly) -->
                    <div class="mb-6 opacity-60 pointer-events-none">
                        <label class="block text-sm font-medium text-slate-300 mb-3">Çek Tipi</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="group relative flex items-center p-4 border-2 border-white/10 rounded-xl cursor-default {{ $cheque->type === 'received' ? 'border-green-500 bg-green-500/10' : '' }}">
                                <input type="radio" value="received" class="sr-only" {{ $cheque->type === 'received' ? 'checked' : '' }} disabled>
                                <div class="flex items-center gap-3 w-full">
                                    <div class="p-2 rounded-lg {{ $cheque->type === 'received' ? 'bg-green-500 text-white' : 'bg-green-500/20 text-green-400' }}">
                                        <span class="material-symbols-outlined text-[28px]">arrow_downward</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white text-lg">Alınan Çek</p>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="group relative flex items-center p-4 border-2 border-white/10 rounded-xl cursor-default {{ $cheque->type === 'issued' ? 'border-red-500 bg-red-500/10' : '' }}">
                                <input type="radio" value="issued" class="sr-only" {{ $cheque->type === 'issued' ? 'checked' : '' }} disabled>
                                <div class="flex items-center gap-3 w-full">
                                    <div class="p-2 rounded-lg {{ $cheque->type === 'issued' ? 'bg-red-500 text-white' : 'bg-red-500/20 text-red-400' }}">
                                        <span class="material-symbols-outlined text-[28px]">arrow_upward</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white text-lg">Verilen Çek</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Çek Numarası -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Çek Numarası *</label>
                            <input type="text" name="cheque_number" value="{{ old('cheque_number', $cheque->cheque_number) }}" required
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('cheque_number')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Banka Adı -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Banka Adı *</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $cheque->bank_name) }}" required
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Şube -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Şube</label>
                            <input type="text" name="branch" value="{{ old('branch', $cheque->branch) }}"
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Hesap No -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Hesap Numarası</label>
                            <input type="text" name="account_number" value="{{ old('account_number', $cheque->account_number) }}"
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Keşideci -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Keşideci *</label>
                            <input type="text" name="drawer" value="{{ old('drawer', $cheque->drawer) }}" required
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('drawer')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ciro Eden -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Ciro Eden</label>
                            <input type="text" name="endorser" value="{{ old('endorser', $cheque->endorser) }}"
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Tutar -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Tutar *</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="amount" value="{{ old('amount', $cheque->amount) }}" required
                                       class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-12">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                    {{ $cheque->currency }}
                                </div>
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keşide Tarihi (Readonly oldugu dusunulur ama duzenlenebilir genelde) -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Keşide Tarihi</label>
                            <input type="date" name="issue_date" value="{{ old('issue_date', $cheque->issue_date->format('Y-m-d')) }}" required disabled
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-slate-400 cursor-not-allowed">
                            <p class="text-xs text-slate-500 mt-1">Keşide tarihi değiştirilemez.</p>
                        </div>

                        <!-- Vade Tarihi -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Vade Tarihi *</label>
                            <input type="date" name="due_date" value="{{ old('due_date', $cheque->due_date->format('Y-m-d')) }}" required
                                   class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cari Hesap -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Cari Hesap</label>
                            <select name="contact_id" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:ring-2 focus:ring-blue-500">
                                <option value="">Seçiniz...</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}" {{ old('contact_id', $cheque->contact_id) == $contact->id ? 'selected' : '' }}>
                                        {{ $contact->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Notlar -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Notlar</label>
                            <textarea name="notes" rows="3" 
                                      class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $cheque->notes) }}</textarea>
                        </div>
                    </div>

                    <!-- Butonlar -->
                    <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-white/10">
                        <a href="{{ route('accounting.cheques.show', $cheque) }}" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white rounded-lg font-medium transition-all">
                            İptal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-semibold shadow-lg shadow-blue-500/30 transition-all">
                            Değişiklikleri Kaydet
                        </button>
                    </div>

                </x-card>
            </form>

        </div>
    </div>
</x-app-layout>
