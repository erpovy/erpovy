<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Kullanıcı Düzenle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('hr.users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- İsim -->
                        <div>
                            <label class="input-label text-gray-300">Ad Soyad</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="custom-input text-white" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="input-label text-gray-300">E-posta</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="custom-input text-white" required>
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Şifre -->
                        <div>
                            <label class="input-label text-gray-300">Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                            <input type="password" name="password" class="custom-input text-white" placeholder="********">
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Rol -->
                        <div>
                            <label class="input-label text-gray-300">Rol</label>
                            <select name="role" class="custom-input text-white !bg-[#0f172a]" required>
                                <option value="" class="bg-[#0f172a] text-white">Seçiniz</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" class="bg-[#0f172a] text-white" 
                                        {{ (old('role') == $role->name || $user->hasRole($role->name)) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        </div>
                    </div>

                    <div class="mt-8 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-widest border-b border-white/10 pb-2 flex items-center gap-2">
                             <span class="material-symbols-outlined text-lg">person_add_analytics</span>
                             Ekstra Yetkiler (Opsiyonel)
                        </h3>
                        <p class="text-xs text-gray-500 italic">Bu yetkiler, kullanıcının rolüne ek olarak doğrudan kendisine atanır.</p>
                        <x-permission-grid :permissions="$permissions" :selectedPermissions="$selectedPermissions" />
                    </div>

                    @if($user->employee)
                    <div class="border-t border-white/10 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Çalışan Bilgileri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="input-label text-gray-300">Departman</label>
                                <input type="text" name="department" value="{{ old('department', $user->employee->department) }}" class="custom-input text-white">
                            </div>
                            <div>
                                <label class="input-label text-gray-300">Pozisyon / Unvan</label>
                                <input type="text" name="position" value="{{ old('position', $user->employee->position) }}" class="custom-input text-white">
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('hr.users.index') }}" class="px-6 py-2.5 rounded-xl border border-white/10 text-gray-300 hover:bg-white/5 transition-colors">
                            İptal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-neon-active text-white font-medium hover:bg-blue-600 transition-colors shadow-lg shadow-blue-500/30">
                            Güncelle
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
