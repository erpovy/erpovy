<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Excel / CSV İçe Aktar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="p-6">
                    <div class="mb-8 p-4 bg-green-500/10 border border-green-500/20 rounded-xl">
                        <h3 class="text-green-400 font-semibold mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined">description</span>
                            Bilgilendirme
                        </h3>
                        <p class="text-gray-400 text-sm">
                            Ürünlerinizi Excel (CSV) formatında toplu olarak yükleyebilirsiniz. 
                            İndireceğiniz şablonu Excel ile açıp düzenleyebilir ve tekrar buraya yükleyebilirsiniz.
                            Ürün kodu (code) eşleşirse güncelleme, eşleşmezse yeni kayıt yapılır.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('inventory.products.import.sample') }}" class="text-green-400 hover:text-green-300 underline text-sm flex items-center gap-1">
                                <span class="material-symbols-outlined text-base">download</span>
                                Excel (CSV) Şablonunu İndir
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('inventory.products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Excel/CSV Dosyası Seçin</label>
                            <input type="file" name="csv_file" accept=".csv" class="block w-full text-sm text-gray-400
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-primary file:text-white
                                hover:file:bg-primary-600
                                cursor-pointer bg-white/5 rounded-lg border border-white/10 focus:outline-none focus:border-primary
                            " required>
                            @error('xml_file')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                            <a href="{{ route('inventory.products.index') }}" class="px-6 py-2.5 rounded-xl border border-white/10 text-gray-300 hover:bg-white/5 transition-colors">
                                İptal
                            </a>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-600 text-white font-medium transition-colors shadow-lg shadow-primary/20">
                                Yükle ve Başlat
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
