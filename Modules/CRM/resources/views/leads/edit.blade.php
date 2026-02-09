<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-2">
            <a href="{{ route('crm.leads.index') }}" class="text-gray-400 hover:text-white transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            {{ __('Potansiyel Müşteriyi Düzenle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('crm.leads.update', $lead->id) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Column: Personal & Contact Info -->
                        <div class="lg:col-span-2 space-y-6">
                            <h3 class="text-lg font-semibold text-white flex items-center gap-2 border-b border-white/10 pb-2">
                                <span class="material-symbols-outlined text-primary">person</span>
                                Kişisel ve Firma Bilgileri
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Ad <span class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $lead->first_name) }}" required class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Soyad</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $lead->last_name) }}" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Unvan</label>
                                    <input type="text" name="title" value="{{ old('title', $lead->title) }}" placeholder="Örn: Satın Alma Müdürü" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Firma Adı</label>
                                    <input type="text" name="company_name" value="{{ old('company_name', $lead->company_name) }}" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold text-white flex items-center gap-2 border-b border-white/10 pb-2 pt-4">
                                <span class="material-symbols-outlined text-blue-400">contact_mail</span>
                                İletişim Bilgileri
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-2">E-posta</label>
                                    <input type="email" name="email" value="{{ old('email', $lead->email) }}" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-400 mb-2">Telefon</label>
                                    <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Adres / Konum</label>
                                <textarea name="address" rows="2" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">{{ old('address', $lead->address) }}</textarea>
                            </div>
                        </div>

                        <!-- Right Column: CRM Details -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-white flex items-center gap-2 border-b border-white/10 pb-2">
                                <span class="material-symbols-outlined text-green-400">settings_accessibility</span>
                                CRM Detayları
                            </h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Durum</label>
                                <select name="status" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                                    <option value="New" {{ old('status', $lead->status) == 'New' ? 'selected' : '' }}>Yeni (New)</option>
                                    <option value="Contacted" {{ old('status', $lead->status) == 'Contacted' ? 'selected' : '' }}>İletişime Geçildi</option>
                                    <option value="Qualified" {{ old('status', $lead->status) == 'Qualified' ? 'selected' : '' }}>Uygun Görüldü</option>
                                    <option value="Lost" {{ old('status', $lead->status) == 'Lost' ? 'selected' : '' }}>Kaybedildi</option>
                                    <option value="Won" {{ old('status', $lead->status) == 'Won' ? 'selected' : '' }}>Kazanıldı</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Kaynak</label>
                                <select name="source" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Seçiniz...</option>
                                    @foreach($sources as $source)
                                        <option value="{{ $source }}" {{ old('source', $lead->source) == $source ? 'selected' : '' }}>{{ $source }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Sıcaklık Skoru (0-100)</label>
                                <input type="number" name="score" value="{{ old('score', $lead->score) }}" min="0" max="100" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Temsilci Ata</label>
                                <select name="assigned_to" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Atamasız</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Notlar</label>
                                <textarea name="notes" rows="4" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">{{ old('notes', $lead->notes) }}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="flex justify-end pt-6 border-t border-white/10 mt-6">
                        <button type="submit" class="px-8 py-2.5 rounded-xl bg-primary hover:bg-primary-600 text-white font-bold transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            Güncelle
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
