<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Rol Detayları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="p-8">
                    <!-- Header Section with Role Name and Department Badge -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 border-b border-white/10 pb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h1 class="text-3xl font-bold text-white">{{ $role->name }}</h1>
                                @if(isset($role->department_id) && isset($departments[$role->department_id]))
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400 border border-blue-500/20">
                                        {{ $departments[$role->department_id]->name }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-700/50 text-gray-400 border border-gray-600/30">
                                        Genel Rol
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-400 text-sm">
                                Bu rol <span class="text-gray-300 font-medium">{{ $role->created_at->format('d.m.Y') }}</span> tarihinde, saat <span class="text-gray-300 font-medium">{{ $role->created_at->format('H:i') }}</span>'de oluşturuldu.
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                             <!-- Action Buttons moved to top-right for better UX -->
                            <a href="{{ route('hr.roles.index') }}" class="px-4 py-2 rounded-lg border border-white/10 text-gray-300 hover:bg-white/5 transition-colors text-sm font-medium">
                                Geri Dön
                            </a>
                            <a href="{{ route('hr.roles.edit', $role) }}" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary-600 text-white transition-colors text-sm font-medium shadow-lg shadow-primary/20">
                                Düzenle
                            </a>
                        </div>
                    </div>

                    <!-- Details Section (Placeholder for future Permissions) -->
                    <div class="grid grid-cols-1 gap-8">
                        <div class="bg-white/5 rounded-xl p-6 border border-white/5">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined text-gray-400">lock_person</span>
                                İzinler ve Yetkiler
                            </h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                Bu role henüz özel bir yetki atanmamış. Sistem yöneticisi tarafından "İzin Yönetimi" ekranından yetkilendirme yapılabilir.
                                <br><br>
                                <span class="text-xs text-gray-500 italic">* Yetkilendirme modülü aktif edildiğinde burada detaylı izin listesi görüntülenecektir.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
