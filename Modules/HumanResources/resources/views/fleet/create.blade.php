<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('hr.fleet.index') }}" class="btn-secondary flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Fila Yönetimi
            </a>
            <span class="text-white/20">|</span>
            <span class="text-white font-bold">Yeni Araç Ekle</span>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <x-card class="p-6">
            <form action="{{ route('hr.fleet.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="input-label text-gray-300">Plaka</label>
                        <input type="text" name="plate_number" value="{{ old('plate_number') }}" class="custom-input text-white uppercase" required placeholder="34 ABC 123" oninput="this.value = this.value.toUpperCase()">
                        @error('plate_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="input-label text-gray-300">Model Yılı</label>
                        <input type="number" name="year" value="{{ old('year', date('Y')) }}" class="custom-input text-white" required min="1900" max="{{ date('Y') + 1 }}">
                        @error('year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="input-label text-gray-300">Marka</label>
                        <input type="text" name="make" value="{{ old('make') }}" class="custom-input text-white" required placeholder="Ford, Renault vb.">
                        @error('make') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="input-label text-gray-300">Model</label>
                        <input type="text" name="model" value="{{ old('model') }}" class="custom-input text-white" required placeholder="Focus, Clio vb.">
                        @error('model') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="input-label text-gray-300">Zimmetlenecek Personel</label>
                    <select name="employee_id" class="custom-input !bg-[#0f172a] text-white">
                        <option value="" class="bg-[#0f172a] text-white">-- Boşta --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }} class="bg-[#0f172a] text-white">
                                {{ $employee->full_name }} ({{ $employee->department?->name ?? 'Departman Yok' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="input-label text-gray-300">Durum</label>
                    <select name="status" class="custom-input !bg-[#0f172a] text-white" required>
                        <option value="active" class="bg-[#0f172a] text-white">Aktif</option>
                        <option value="maintenance" class="bg-[#0f172a] text-white">Bakımda</option>
                        <option value="out_of_service" class="bg-[#0f172a] text-white">Hizmet Dışı</option>
                    </select>
                </div>

                <div class="flex justify-end pt-4 border-t border-white/5">
                    <button type="submit" class="btn-primary">
                        Araç Ekle
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
