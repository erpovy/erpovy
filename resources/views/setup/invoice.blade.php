<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Fatura Kurulumu
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-white mb-6">Fatura ve E-posta Ayarları</h3>
                    <p class="text-slate-400 text-sm mb-6">Faturalarınızın müşterilere e-posta ile gönderilebilmesi için SMTP ayarlarını yapılandırın ve fatura şablonunuzu düzenleyin.</p>

                    @php
                        $invoiceSettings = $settings['invoice_settings'] ?? [];
                    @endphp

                    <form action="{{ route('setup.invoice.update') }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- GIB e-Arşiv Portal Ayarları -->
                        <div>
                            <h4 class="text-md font-bold text-primary mb-4 pb-2 border-b border-white/5">GİB e-Arşiv Portal Ayarları</h4>
                            <p class="text-xs text-slate-400 mb-4">Bu bilgiler, faturalarınızı entegratör kullanmadan doğrudan GİB Portal'a taslak olarak göndermek için kullanılır.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="input-label">GİB Portal Kullanıcı Kodu</label>
                                    <input type="text" name="gib_username" value="{{ old('gib_username', $company->gib_username ?? '') }}" class="custom-input" placeholder="Kullanıcı Kodu">
                                </div>
                                <div>
                                    <label class="input-label">GİB Portal Şifresi</label>
                                    <input type="password" name="gib_password" value="{{ old('gib_password', $company->gib_password ? '********' : '') }}" class="custom-input" placeholder="Şifre">
                                    <p class="text-xs text-slate-500 mt-1">Güvenlik için şifreli saklanır.</p>
                                </div>
                            </div>
                        </div>



                        <div class="flex justify-end pt-6 border-t border-white/10">
                            <button type="submit" class="btn-primary">
                                Ayarları Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
