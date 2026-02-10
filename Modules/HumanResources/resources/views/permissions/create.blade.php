<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Yeni Yetki Ekle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('hr.permissions.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="input-label text-gray-300">Yetki Adı</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="custom-input text-white" placeholder="Örn: view users, create invoices" required>
                            <p class="mt-1 text-xs text-gray-500 italic">Format: eylem kaynak (Örn: view users)</p>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 border-t border-white/10 pt-6">
                        <a href="{{ route('hr.permissions.index') }}" class="px-6 py-2.5 rounded-xl border border-white/10 text-gray-300 hover:bg-white/5 transition-colors">
                            İptal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-neon-active text-white font-medium hover:bg-blue-600 transition-colors shadow-lg shadow-blue-500/30">
                            Kaydet
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
