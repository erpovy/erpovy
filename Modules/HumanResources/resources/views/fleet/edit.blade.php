<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('hr.fleet.index') }}" class="btn-secondary flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Fila Yönetimi
            </a>
            <span class="text-white/20">|</span>
            <span class="text-white font-bold">Araç Düzenle: {{ $vehicle->plate_number }}</span>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <x-card class="p-6">
            <form action="{{ route('hr.fleet.update', $vehicle) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="input-label text-gray-300">Plaka</label>
                        <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" class="custom-input text-white uppercase" required oninput="this.value = this.value.toUpperCase()">
                        @error('plate_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="input-label text-gray-300">Model Yılı</label>
                        <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" class="custom-input text-white" required>
                        @error('year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="input-label text-gray-300">Marka</label>
                        <input type="text" name="make" value="{{ old('make', $vehicle->make) }}" class="custom-input text-white" required>
                        @error('make') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="input-label text-gray-300">Model</label>
                        <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" class="custom-input text-white" required>
                        @error('model') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="input-label text-gray-300">Zimmetli Personel</label>
                    <select name="employee_id" class="custom-input !bg-[#0f172a] text-white">
                        <option value="" class="bg-[#0f172a] text-white">-- Boşta --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id', $vehicle->employee_id) == $employee->id ? 'selected' : '' }} class="bg-[#0f172a] text-white">
                                {{ $employee->full_name }} ({{ $employee->department?->name ?? 'Departman Yok' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="input-label text-gray-300">Durum</label>
                    <select name="status" class="custom-input !bg-[#0f172a] text-white" required>
                        <option value="active" {{ old('status', $vehicle->status) == 'active' ? 'selected' : '' }} class="bg-[#0f172a] text-white">Aktif</option>
                        <option value="maintenance" {{ old('status', $vehicle->status) == 'maintenance' ? 'selected' : '' }} class="bg-[#0f172a] text-white">Bakımda</option>
                        <option value="out_of_service" {{ old('status', $vehicle->status) == 'out_of_service' ? 'selected' : '' }} class="bg-[#0f172a] text-white">Hizmet Dışı</option>
                    </select>
                </div>

                <div class="flex justify-between pt-4 border-t border-white/5">
                    <button type="button" onclick="confirm('Silmek istediğinize emin misiniz?') || event.preventDefault(); document.getElementById('delete-form').submit();" class="text-red-500 hover:text-red-400 text-sm font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined">delete</span>
                        Aracı Sil
                    </button>
                    <button type="submit" class="btn-primary">
                        Değişiklikleri Kaydet
                    </button>
                </div>
            </form>

            <form id="delete-form" action="{{ route('hr.fleet.destroy', $vehicle) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </x-card>
    </div>
</x-app-layout>
