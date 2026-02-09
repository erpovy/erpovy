<x-app-layout>
    <x-slot name="header">
        İnsan Kaynakları
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                        <h2 class="text-xl font-bold text-white">{{ isset($department) ? 'Departmanı Düzenle' : 'Yeni Departman Ekle' }}</h2>
                        <a href="{{ route('hr.departments.index') }}" class="text-slate-400 hover:text-white flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                            Listeye Dön
                        </a>
                    </div>

                    <form action="{{ isset($department) ? route('hr.departments.update', $department) : route('hr.departments.store') }}" method="POST" class="space-y-6">
                        @csrf
                        @if(isset($department))
                            @method('PUT')
                        @endif
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="input-label text-gray-300">Departman Adı</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $department->name ?? '') }}" 
                                       class="custom-input" required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="input-label text-gray-300">Açıklama</label>
                                <textarea name="description" id="description" rows="3"
                                          class="custom-input">{{ old('description', $department->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $department->is_active ?? true) ? 'checked' : '' }} class="rounded bg-[#0f172a] border-gray-600 text-primary focus:ring-primary">
                                <label for="is_active" class="text-white font-medium cursor-pointer select-none">
                                    Aktif
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-white/10">
                            <button type="submit" class="btn-primary">
                                {{ isset($department) ? 'Güncelle' : 'Kaydet' }}
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
