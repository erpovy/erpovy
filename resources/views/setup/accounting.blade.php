<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Muhasebe Kurulumu
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-white mb-6">Şirket Bilgileri (Resmi)</h3>
                    <p class="text-slate-400 text-sm mb-6">Lütfen faturalarınızda ve resmi evraklarınızda görünecek şirket bilgilerinizi eksiksiz giriniz.</p>

                    @php
                        $details = $settings['company_details'] ?? [];
                    @endphp

                    <form action="{{ route('setup.accounting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Logo -->
                        <div>
                            <label class="input-label">Şirket Logosu</label>
                            @if(isset($details['logo']))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $details['logo']) }}" alt="Logo" class="h-16 w-auto rounded">
                                </div>
                            @endif
                            <input type="file" name="logo" class="custom-input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary hover:file:bg-primary/30 text-slate-400">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Ticari Unvan -->
                            <div class="col-span-2">
                                <label class="input-label">Ticari Unvan (Tam Ad)</label>
                                <input type="text" name="title" value="{{ old('title', $details['title'] ?? $company?->name) }}" class="custom-input" placeholder="Örn: Artovy Yazılım A.Ş." required>
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- VKN / TCKN -->
                            <div>
                                <label class="input-label">Vergi Kimlik No / TCKN</label>
                                <input type="text" name="tax_number" value="{{ old('tax_number', $details['tax_number'] ?? '') }}" class="custom-input" placeholder="10 haneli VKN veya 11 haneli TCKN" required>
                                @error('tax_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Vergi Dairesi -->
                            <div>
                                <label class="input-label">Vergi Dairesi</label>
                                <input type="text" name="tax_office" value="{{ old('tax_office', $details['tax_office'] ?? '') }}" class="custom-input" placeholder="Bağlı bulunulan vergi dairesi" required>
                                @error('tax_office') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Mersis -->
                            <div>
                                <label class="input-label">Mersis No (Opsiyonel)</label>
                                <input type="text" name="mersis_no" value="{{ old('mersis_no', $details['mersis_no'] ?? '') }}" class="custom-input">
                            </div>

                            <!-- Ticaret Sicil -->
                            <div>
                                <label class="input-label">Ticaret Sicil No (Opsiyonel)</label>
                                <input type="text" name="trade_register_no" value="{{ old('trade_register_no', $details['trade_register_no'] ?? '') }}" class="custom-input">
                            </div>
                        </div>

                        <!-- Adres -->
                        <div>
                            <label class="input-label">Adres</label>
                            <textarea name="address" rows="3" class="custom-input" placeholder="Tam fatura adresi" required>{{ old('address', $details['address'] ?? '') }}</textarea>
                            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- İl -->
                            <div>
                                <label class="input-label">İl</label>
                                <input type="text" name="city" value="{{ old('city', $details['city'] ?? '') }}" class="custom-input" required>
                            </div>

                            <!-- İlçe -->
                            <div>
                                <label class="input-label">İlçe</label>
                                <input type="text" name="district" value="{{ old('district', $details['district'] ?? '') }}" class="custom-input" required>
                            </div>

                             <!-- Telefon -->
                             <div>
                                <label class="input-label">Telefon</label>
                                <input type="text" name="phone" value="{{ old('phone', $details['phone'] ?? '') }}" class="custom-input">
                            </div>

                             <!-- E-posta -->
                             <div>
                                <label class="input-label">Fatura E-posta Adresi</label>
                                <input type="email" name="email" value="{{ old('email', $details['email'] ?? $company?->users->first()->email ?? '') }}" class="custom-input" required>
                            </div>
                             
                             <!-- Web -->
                             <div class="col-span-2">
                                <label class="input-label">Web Sitesi</label>
                                <input type="url" name="website" value="{{ old('website', $details['website'] ?? ($company?->domain ? 'https://'.$company->domain : '')) }}" class="custom-input" placeholder="https://example.com">
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-white/10">
                            <button type="submit" class="btn-primary">
                                Kaydet ve Güncelle
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
