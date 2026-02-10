<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Rol Düzenle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form action="{{ route('hr.roles.update', $role) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="input-label text-gray-300">Departman (Opsiyonel)</label>
                            <select name="department_id" class="custom-input text-white !bg-[#0f172a]">
                                <option value="" class="bg-[#0f172a] text-white">Genel Rol (Departmansız)</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ (old('department_id', $role->department_id) == $department->id) ? 'selected' : '' }} class="bg-[#0f172a] text-white">
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="input-label text-gray-300">Rol Adı</label>
                            <input type="text" name="name" value="{{ old('name', $role->name) }}" class="custom-input text-white" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 space-y-4">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-widest border-b border-white/10 pb-2 flex items-center justify-between gap-2">
                             <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-lg">shield_lock</span>
                                Rol Yetkileri
                             </div>
                             <form action="{{ route('hr.permissions.seed') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-[10px] bg-amber-500/10 hover:bg-amber-500/20 text-amber-500 px-3 py-1 rounded-lg border border-amber-500/20 transition-all flex items-center gap-1 group">
                                    <span class="material-symbols-outlined text-sm group-hover:rotate-180 transition-transform">sync</span>
                                    Yetkileri Otomatik Onar
                                </button>
                             </form>
                        </h3>
                        <x-permission-grid :permissions="$permissions" :selectedPermissions="$selectedPermissions" />
                    </div>

                    <div class="flex justify-end gap-3 mt-6 border-t border-white/10 pt-6">
                        <a href="{{ route('hr.roles.index') }}" class="px-6 py-2.5 rounded-xl border border-white/10 text-gray-300 hover:bg-white/5 transition-colors">
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
