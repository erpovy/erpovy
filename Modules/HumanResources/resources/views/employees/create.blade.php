<x-app-layout>
    <x-slot name="header">
        İnsan Kaynakları
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                        <h2 class="text-xl font-bold text-white">Yeni Çalışan Ekle</h2>
                        <a href="{{ route('hr.employees.index') }}" class="text-slate-400 hover:text-white flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                            Listeye Dön
                        </a>
                    </div>

                    <form action="{{ route('hr.employees.store') }}" method="POST" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kişisel Bilgiler -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-white">Kişisel Bilgiler</h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="input-label text-gray-300">Ad</label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="custom-input" required>
                                        @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="input-label text-gray-300">Soyad</label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="custom-input" required>
                                        @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="input-label text-gray-300">E-posta</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="custom-input">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="input-label text-gray-300">Telefon</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="custom-input">
                                </div>
                            </div>

                            <!-- User Creation Section -->
                            <div class="col-span-full border-t border-white/10 pt-6 mt-2" x-data="{ createUser: false }">
                                <div class="flex items-center gap-3 mb-4">
                                    <input type="checkbox" id="create_user" name="create_user" value="1" x-model="createUser" class="rounded bg-[#0f172a] border-gray-600 text-primary focus:ring-primary">
                                    <label for="create_user" class="text-white font-medium cursor-pointer select-none">
                                        Bu çalışan için kullanıcı hesabı oluştur
                                    </label>
                                </div>

                                <div x-show="createUser" x-transition class="bg-white/5 rounded-xl p-4 grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 border border-white/10">
                                    <div class="col-span-full bg-blue-500/10 border border-blue-500/20 rounded-lg p-4">
                                        <div class="flex items-start gap-3">
                                            <span class="material-symbols-outlined text-blue-400 shrink-0">info</span>
                                            <div class="text-sm">
                                                <p class="font-bold mb-1 text-white">Panel Giriş Bilgileri</p>
                                                <p class="text-white">Personelin sisteme giriş yapabilmesi için bir kullanıcı hesabı oluşturuyorsunuz.</p>
                                                <ul class="list-disc list-inside mt-2 text-slate-300 space-y-1 text-xs">
                                                    <li><strong class="text-white">Kullanıcı Adı:</strong> Otomatik olarak Ad ve Soyad'dan oluşturulur.</li>
                                                    <li><strong class="text-white">E-posta:</strong> Yukarıda girilen e-posta adresi kullanılır.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="input-label text-gray-300">Geçici Parola</label>
                                        <input type="password" name="password" class="custom-input" :required="createUser" placeholder="En az 8 karakter">
                                        <p class="text-xs text-slate-500 mt-1">Personel sisteme ilk girişinde bu parolayı kullanacaktır.</p>
                                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="input-label text-gray-300">Yetki Rolü</label>
                                        <select name="role" class="custom-input bg-[#0f172a]">
                                            <option value="User" class="bg-[#0f172a] text-gray-300">Standart Kullanıcı</option>
                                            <option value="Admin" class="bg-[#0f172a] text-gray-300">Yönetici</option>
                                        </select>
                                    </div>
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
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }} class="bg-[#0f172a] text-gray-300">
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="input-label text-gray-300">Pozisyon / Unvan</label>
                                    <input type="text" name="position" value="{{ old('position') }}" class="custom-input">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="input-label text-gray-300">İşe Giriş Tarihi</label>
                                        <input type="date" name="hire_date" value="{{ old('hire_date') }}" class="custom-input">
                                    </div>
                                    <div>
                                        <label class="input-label text-gray-300">Durum</label>
                                        <select name="status" class="custom-input bg-[#0f172a]">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }} class="bg-[#0f172a] text-gray-300">Aktif</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }} class="bg-[#0f172a] text-gray-300">Pasif</option>
                                        <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }} class="bg-[#0f172a] text-gray-300">İzinli</option>
                                        <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }} class="bg-[#0f172a] text-gray-300">İşten Ayrıldı</option>
                                    </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="input-label text-gray-300">Başlangıç Maaşı</label>
                                    <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" class="custom-input">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6 border-t border-white/10 pt-6">
                            <a href="{{ route('hr.employees.index') }}" class="btn-danger">İptal</a>
                            <button type="submit" 
                                    class="btn-primary flex items-center gap-2" 
                                    :disabled="loading" 
                                    :class="{'opacity-75 cursor-not-allowed': loading}">
                                <span x-show="loading" class="material-symbols-outlined animate-spin text-[18px]">sync</span>
                                <span x-text="loading ? 'Kaydediliyor...' : 'Kaydet'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
