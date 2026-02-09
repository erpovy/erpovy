<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Ürün Türleri Yönetimi
            </h2>
            <button onclick="openModal('createModal')" class="bg-primary-600 hover:bg-primary-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-neon">
                + Yeni Tür Ekle
            </button>
        </div>
    </x-slot>

    <x-card class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-400">
                <thead class="text-xs text-slate-400 uppercase bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-3 rounded-l-lg">Tür Adı</th>
                        <th class="px-6 py-3">Kod</th>
                        <th class="px-6 py-3">Durum</th>
                        <th class="px-6 py-3 rounded-r-lg text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($types as $type)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 font-medium text-white">
                                {{ $type->name }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-800 text-slate-300 px-2 py-1 rounded text-xs font-mono">
                                    {{ $type->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($type->is_active)
                                    <span class="text-green-400 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span> Aktif
                                    </span>
                                @else
                                    <span class="text-slate-500 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Pasif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="editType({{ $type->id }}, '{{ $type->name }}', '{{ $type->code }}', {{ $type->is_active }})" class="text-blue-400 hover:text-blue-300 transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    
                                    <form action="{{ route('inventory.settings.types.destroy', $type) }}" method="POST" onsubmit="return confirm('Bu türü silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('createModal')"></div>
        <div class="relative w-full max-w-md bg-slate-900 border border-white/10 rounded-2xl shadow-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Yeni Ürün Türü Ekle</h3>
            <form action="{{ route('inventory.settings.types.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Tür Adı</label>
                        <input type="text" name="name" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500" required placeholder="Örn: Hammadde">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Kod (Opsiyonel)</label>
                        <input type="text" name="code" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500" placeholder="Otomatik oluşturulur">
                    </div>
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded bg-slate-800 border-white/10 text-primary-600 focus:ring-primary-600">
                            <span class="text-sm text-slate-300">Aktif</span>
                        </label>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 text-slate-400 hover:text-white transition-colors">İptal</button>
                        <button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">Kaydet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
        <div class="relative w-full max-w-md bg-slate-900 border border-white/10 rounded-2xl shadow-2xl p-6">
            <h3 class="text-lg font-bold text-white mb-4">Ürün Türünü Düzenle</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Tür Adı</label>
                        <input type="text" name="name" id="editName" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Kod</label>
                        <input type="text" name="code" id="editCode" class="w-full rounded-lg bg-slate-800 border border-white/10 text-white focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <input type="hidden" name="is_active" value="0">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" id="editActive" value="1" class="rounded bg-slate-800 border-white/10 text-primary-600 focus:ring-primary-600">
                            <span class="text-sm text-slate-300">Aktif</span>
                        </label>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 text-slate-400 hover:text-white transition-colors">İptal</button>
                        <button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white px-4 py-2 rounded-lg font-medium transition-colors">Güncelle</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        function editType(id, name, code, isActive) {
            document.getElementById('editName').value = name;
            document.getElementById('editCode').value = code;
            document.getElementById('editActive').checked = isActive;
            
            // Set form action dynamically
            document.getElementById('editForm').action = "{{ route('inventory.settings.types.update', ':id') }}".replace(':id', id);
            
            openModal('editModal');
        }
    </script>
</x-app-layout>
