<x-app-layout>
    <x-slot name="header">
        İnsan Kaynakları - Filo Yönetimi
    </x-slot>

    <div class="h-[calc(100vh-8rem)] flex flex-col">
        <x-card class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-[#0f172a]/50">
                <h2 class="text-xl font-bold text-white">Araç Listesi</h2>
                <a href="{{ route('hr.fleet.create') }}" class="btn-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span>
                    Yeni Araç Ekle
                </a>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-auto p-6" x-data="{ expandedRow: null }">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs font-bold text-slate-400 border-b border-white/5 uppercase tracking-wider">
                            <th class="py-3 px-4">Plaka</th>
                            <th class="py-3 px-4">Marka / Model</th>
                            <th class="py-3 px-4">Zimmetli Personel</th>
                            <th class="py-3 px-4">Durum</th>
                            <th class="py-3 px-4 text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm text-slate-300">
                        @foreach($vehicles as $vehicle)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-3 px-4 font-semibold text-white">{{ $vehicle->plate_number }}</td>
                                <td class="py-3 px-4">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }})</td>
                                <td class="py-3 px-4">
                                    @if($vehicle->employee)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-primary/20 text-primary flex items-center justify-center text-xs font-bold">
                                                {{ substr($vehicle->employee->first_name, 0, 1) }}{{ substr($vehicle->employee->last_name, 0, 1) }}
                                            </div>
                                            <span>{{ $vehicle->employee->full_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-500 italic">Boşta</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $statusClass = match($vehicle->status) {
                                            'active' => 'bg-green-500/20 text-green-400',
                                            'maintenance' => 'bg-yellow-500/20 text-yellow-400',
                                            'out_of_service' => 'bg-red-500/20 text-red-400',
                                            default => 'bg-slate-500/20 text-slate-400'
                                        };
                                        $statusText = match($vehicle->status) {
                                            'active' => 'Aktif',
                                            'maintenance' => 'Bakımda',
                                            'out_of_service' => 'Hizmet Dışı',
                                            default => $vehicle->status
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button @click="expandedRow === {{ $vehicle->id }} ? expandedRow = null : expandedRow = {{ $vehicle->id }}" 
                                                class="text-blue-400 hover:text-blue-300 transition-colors" title="Görüntüle">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </button>
                                        <a href="{{ route('hr.fleet.edit', $vehicle) }}" class="text-yellow-400 hover:text-yellow-300 transition-colors" title="Düzenle">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <form action="{{ route('hr.fleet.destroy', $vehicle) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu aracı silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 transition-colors pt-1" title="Sil">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <!-- Expenses Row -->
                            <tr x-show="expandedRow === {{ $vehicle->id }}" x-cloak class="bg-black/20">
                                <td colspan="5" class="p-0">
                                    <div class="p-4 border-b border-white/5">
                                        <div class="flex justify-between items-center mb-3">
                                            <h4 class="text-sm font-bold text-white">Masraf Geçmişi</h4>
                                            
                                            <!-- Add Expense Form -->
                                            <form action="{{ route('hr.fleet.expenses.store', $vehicle) }}" method="POST" class="flex items-end gap-2 text-xs">
                                                @csrf
                                                <div>
                                                    <label class="block text-slate-500 mb-1">Tarih</label>
                                                    <input type="date" name="date" required class="bg-[#0f172a] border border-white/10 rounded px-2 py-1 text-white focus:outline-none focus:border-primary">
                                                </div>
                                                <div>
                                                    <label class="block text-slate-500 mb-1">Tür</label>
                                                    <select name="type" required class="bg-[#0f172a] border border-white/10 rounded px-2 py-1 text-white focus:outline-none focus:border-primary">
                                                        <option value="fuel">Yakıt</option>
                                                        <option value="maintenance">Bakım/Tamir</option>
                                                        <option value="insurance">Sigorta/Kasko</option>
                                                        <option value="fine">Trafik Cezası</option>
                                                        <option value="other">Diğer</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-slate-500 mb-1">Tutar (₺)</label>
                                                    <input type="number" name="amount" step="0.01" required class="bg-[#0f172a] border border-white/10 rounded px-2 py-1 text-white w-24 focus:outline-none focus:border-primary">
                                                </div>
                                                <button type="submit" class="bg-primary hover:bg-primary/80 text-white rounded px-3 py-1 font-bold shadow-neon transition-all">Ekle</button>
                                            </form>
                                        </div>

                                        @if($vehicle->expenses->count() > 0)
                                            <table class="w-full text-xs">
                                                <thead>
                                                    <tr class="text-slate-500 border-b border-white/5">
                                                        <th class="pb-2 text-left">Tarih</th>
                                                        <th class="pb-2 text-left">Tür</th>
                                                        <th class="pb-2 text-right">Tutar</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-slate-300">
                                                    @foreach($vehicle->expenses as $expense)
                                                        <tr>
                                                            <td class="py-1">{{ $expense->date->format('d.m.Y') }}</td>
                                                            <td class="py-1 capitalize">{{ match($expense->type) { 'fuel' => 'Yakıt', 'maintenance' => 'Bakım', 'insurance' => 'Sigorta', 'fine' => 'Trafik Cezası', default => 'Diğer' } }}</td>
                                                            <td class="py-1 text-right">{{ number_format($expense->amount, 2) }} ₺</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="text-slate-500 text-xs italic">Henüz masraf kaydı bulunmuyor.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-app-layout>
