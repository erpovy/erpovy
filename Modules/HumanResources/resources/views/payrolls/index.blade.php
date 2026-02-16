<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-white">Bordro Yönetimi</h2>
                <button onclick="document.getElementById('newPayrollModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
                    + Yeni Bordro Dönemi
                </button>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl border border-gray-700 rounded-2xl overflow-hidden">
                <table class="w-full text-left text-gray-300">
                    <thead class="bg-gray-900/50 text-gray-400 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Dönem</th>
                            <th class="px-6 py-4">Durum</th>
                            <th class="px-6 py-4">Çalışan Sayısı</th>
                            <th class="px-6 py-4">Toplam Ödeme</th>
                            <th class="px-6 py-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($payrolls as $payroll)
                        <tr class="hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4 font-medium text-white">
                                {{ $payroll->month }}/{{ $payroll->year }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-gray-500/20 text-gray-400',
                                        'approved' => 'bg-blue-500/20 text-blue-400',
                                        'paid' => 'bg-green-500/20 text-green-400',
                                        'cancelled' => 'bg-red-500/20 text-red-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$payroll->status] ?? 'bg-gray-500' }}">
                                    {{ ucfirst($payroll->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ $payroll->items_count ?? $payroll->items()->count() }} Kişi
                            </td>
                            <td class="px-6 py-4 text-sm">
                                ₺{{ number_format($payroll->items()->sum('final_net_paid'), 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('hr.payrolls.show', $payroll) }}" class="text-indigo-400 hover:text-indigo-300 transition text-sm font-medium">
                                    Detayları Gör
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Kayıtlı bordro dönemi bulunamadı.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="newPayrollModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-gray-800 border border-gray-700 rounded-2xl w-full max-w-md p-6">
            <h3 class="text-xl font-semibold text-white mb-4">Yeni Bordro Dönemi</h3>
            <form action="{{ route('hr.payrolls.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Ay</label>
                        <select name="month" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white">
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}" {{ now()->month == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Yıl</label>
                        <input type="number" name="year" value="{{ now()->year }}" class="w-full bg-gray-900 border-gray-700 rounded-lg text-white">
                    </div>
                </div>
                <div class="flex justify-end mt-6 gap-3">
                    <button type="button" onclick="document.getElementById('newPayrollModal').classList.add('hidden')" class="px-4 py-2 text-gray-400 hover:text-white transition">İptal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition">Oluştur</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
