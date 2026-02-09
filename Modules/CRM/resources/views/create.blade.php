<x-app-layout>
    <x-slot name="header">
        Yeni Kişi / Kurum Ekle
    </x-slot>

    <x-card class="p-6" x-data="{ type: '{{ request('type', 'customer') }}' }">
        <h2 class="text-xl font-bold text-white mb-6">İletişim Bilgileri</h2>

        <form action="{{ route('crm.contacts.store') }}" method="POST">
            @csrf
            
            <!-- Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-400 mb-2">Kayıt Türü</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="customer" x-model="type" class="text-primary-600 bg-slate-900 border-white/10 focus:ring-primary-600">
                        <span class="text-white">Müşteri</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="vendor" x-model="type" class="text-primary-600 bg-slate-900 border-white/10 focus:ring-primary-600">
                        <span class="text-white">Tedarikçi</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-slate-400 mb-1">İsim / Firma Unvanı</label>
                    <input type="text" name="name" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" required>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">E-Posta Adresi</label>
                    <input type="email" name="email" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Telefon</label>
                    <input type="text" name="phone" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>

                <!-- Tax Number -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Vergi / Kimlik No</label>
                    <input type="text" name="tax_number" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>
                
                 <!-- Tax Office -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Vergi Dairesi</label>
                    <input type="text" name="tax_office" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                </div>
            </div>

            <!-- Address -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-slate-400 mb-1">Adres</label>
                <textarea name="address" rows="3" class="w-full rounded-lg bg-slate-900/50 border border-white/10 text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('crm.contacts.index') }}" class="px-6 py-2 rounded-lg border border-white/10 text-slate-300 hover:bg-white/5 transition-colors">
                    İptal
                </a>
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-500 shadow-neon transition-all">
                    Kaydet
                </button>
            </div>
        </form>
    </x-card>
</x-app-layout>
