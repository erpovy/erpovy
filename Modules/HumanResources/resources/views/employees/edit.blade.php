<x-app-layout>
    <x-slot name="header">
        İnsan Kaynakları
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                        <h2 class="text-xl font-bold text-white">Çalışan Düzenle: {{ $employee->full_name }}</h2>
                        <a href="{{ route('hr.employees.index') }}" class="text-slate-400 hover:text-white flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                            Listeye Dön
                        </a>
                    </div>

                    <form action="{{ route('hr.employees.update', $employee) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kişisel Bilgiler -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-white">Kişisel Bilgiler</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="input-label text-gray-300">Ad</label>
                                        <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" class="custom-input" required>
                                        @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="input-label text-gray-300">Soyad</label>
                                        <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" class="custom-input" required>
                                        @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="input-label text-gray-300">E-posta</label>
                                    <input type="email" name="email" value="{{ old('email', $employee->email) }}" class="custom-input">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="input-label text-gray-300">Telefon</label>
                                    <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="custom-input">
                                </div>
                            </div>

                            <!-- İş Bilgileri -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-white">İş Bilgileri</h3>
                                
                                <div>
                                    <label class="input-label text-gray-300">Departman</label>
                                    <select name="department_id" class="custom-input bg-[#0f172a]">
                                        <option value="" class="bg-[#0f172a] text-gray-300">Seçiniz</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }} class="bg-[#0f172a] text-gray-300">
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="input-label text-gray-300">Pozisyon / Unvan</label>
                                    <input type="text" name="position" value="{{ old('position', $employee->position) }}" class="custom-input">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="input-label text-gray-300">İşe Giriş Tarihi</label>
                                        <input type="date" name="hire_date" value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}" class="custom-input">
                                    </div>
                                    <div>
                                        <label class="input-label text-gray-300">Durum</label>
                                        <select name="status" class="custom-input bg-[#0f172a]">
                                        <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }} class="bg-[#0f172a] text-gray-300">Aktif</option>
                                        <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }} class="bg-[#0f172a] text-gray-300">Pasif</option>
                                        <option value="on_leave" {{ old('status', $employee->status) == 'on_leave' ? 'selected' : '' }} class="bg-[#0f172a] text-gray-300">İzinli</option>
                                        <option value="terminated" {{ old('status', $employee->status) == 'terminated' ? 'selected' : '' }} class="bg-[#0f172a] text-gray-300">İşten Ayrıldı</option>
                                    </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="input-label text-gray-300">Başlangıç Maaşı</label>
                                    <input type="number" step="0.01" name="salary" value="{{ old('salary', $employee->salary) }}" class="custom-input">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-white/10">
                            <button type="submit" class="btn-primary">
                                Değişiklikleri Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
