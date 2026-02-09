<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Yeni Kullanıcı Ekle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('hr.users.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- İsim -->
                        <div>
                            <label class="input-label text-gray-300">Ad Soyad</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="custom-input text-white" required placeholder="Ad Soyad">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="input-label text-gray-300">E-posta</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="custom-input text-white" required placeholder="ornek@sirket.com">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Şifre -->
                        <div>
                            <label class="input-label text-gray-300">Şifre</label>
                            <input type="password" name="password" class="custom-input text-white" required placeholder="********">
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Rol -->
                        <div>
                            <label class="input-label text-gray-300">Rol</label>
                            <select name="role" class="custom-input text-white !bg-[#0f172a]" required>
                                <option value="" class="bg-[#0f172a] text-white">Seçiniz</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" class="bg-[#0f172a] text-white" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('hr.users.index') }}" class="px-6 py-2.5 rounded-xl border border-white/10 text-gray-300 hover:bg-white/5 transition-colors">
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
