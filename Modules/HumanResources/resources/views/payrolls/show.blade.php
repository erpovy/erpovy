<x-app-layout>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <nav class="flex text-gray-500 text-sm mb-1" aria-label="Breadcrumb">
                        <a href="{{ route('hr.payrolls.index') }}" class="hover:text-white">Bordro Yönetimi</a>
                        <span class="mx-2">/</span>
                        <span class="text-white">Dönem Detayı</span>
                    </nav>
                    <h2 class="text-2xl font-semibold text-white">{{ $payroll->month }}/{{ $payroll->year }} Bordrosu</h2>
                </div>
                <div class="flex gap-3">
                    @if($payroll->status == 'posted')
                        <div class="bg-emerald-500/10 text-emerald-500 px-4 py-2 rounded-lg border border-emerald-500/20 flex items-center gap-2 text-sm font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Muhasebeleştirildi
                        </div>
                    @elseif($payroll->status == 'draft' && $payroll->items()->count() > 0)
                    <form action="{{ route('hr.payrolls.postAccounting', $payroll) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                            </svg>
                            Muhasebeleştir
                        </button>
                    </form>
                    @endif

                    @if($payroll->status == 'draft')
                    <form action="{{ route('hr.payrolls.calculate', $payroll) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                            Tümünü Hesapla
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
                    <p class="text-gray-400 text-sm mb-1">Toplam Brüt</p>
                    <h3 class="text-2xl font-bold text-white">₺{{ number_format($payroll->items()->sum('gross_salary'), 2, ',', '.') }}</h3>
                </div>
                <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
                    <p class="text-gray-400 text-sm mb-1">Toplam Net Ödeme</p>
                    <h3 class="text-2xl font-bold text-green-400">₺{{ number_format($payroll->items()->sum('final_net_paid'), 2, ',', '.') }}</h3>
                </div>
                <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
                    <p class="text-gray-400 text-sm mb-1">SGK + Vergi Yükü</p>
                    <h3 class="text-2xl font-bold text-orange-400">₺{{ number_format($payroll->items()->sum('total_employer_cost') - $payroll->items()->sum('final_net_paid'), 2, ',', '.') }}</h3>
                </div>
                <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
                    <p class="text-gray-400 text-sm mb-1">Toplam İşveren Maliyeti</p>
                    <h3 class="text-2xl font-bold text-indigo-400">₺{{ number_format($payroll->items()->sum('total_employer_cost'), 2, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-xl border border-gray-700 rounded-2xl overflow-x-auto">
                <table class="w-full text-left text-gray-300">
                    <thead class="bg-gray-900/50 text-gray-400 uppercase text-[10px] tracking-wider font-bold">
                        <tr>
                            <th class="px-4 py-3 sticky left-0 bg-gray-900 z-10 w-48">Personel</th>
                            <th class="px-4 py-3">Brüt</th>
                            <th class="px-4 py-3">SGK İşçi (%14)</th>
                            <th class="px-4 py-3">İşs. İşçi (%1)</th>
                            <th class="px-4 py-3">GV Matrahı</th>
                            <th class="px-4 py-3">Hes. GV</th>
                            <th class="px-4 py-3">GV İstisna</th>
                            <th class="px-4 py-3">Ödenecek GV</th>
                            <th class="px-4 py-3">Ödenecek DV</th>
                            <th class="px-4 py-3 font-bold text-green-400 bg-green-900/10">Net Ödenen</th>
                            <th class="px-4 py-3 text-indigo-400 bg-indigo-900/10">Maliyet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($payroll->items as $item)
                        <tr class="hover:bg-gray-700/30 transition text-xs">
                            <td class="px-4 py-3 font-medium text-white sticky left-0 bg-gray-800/80 backdrop-blur flex items-center gap-2">
                                <div class="h-7 w-7 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold border border-indigo-500/30">
                                    {{ substr($item->employee->first_name, 0, 1) }}
                                </div>
                                <span class="truncate w-32">{{ $item->employee->first_name }} {{ $item->employee->last_name }}</span>
                            </td>
                            <td class="px-4 py-3">₺{{ number_format($item->gross_salary, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">₺{{ number_format($item->sgk_worker_cut, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">₺{{ number_format($item->unemployment_worker_cut, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-gray-400 italic">₺{{ number_format($item->income_tax_base, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">₺{{ number_format($item->calculated_income_tax, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-green-500">-₺{{ number_format($item->income_tax_exemption, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-red-400">₺{{ number_format(max(0, $item->calculated_income_tax - $item->income_tax_exemption), 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-red-400">₺{{ number_format(max(0, $item->calculated_stamp_tax - $item->stamp_tax_exemption), 2, ',', '.') }}</td>
                            <td class="px-4 py-3 font-bold text-green-400 bg-green-900/5 italic">₺{{ number_format($item->final_net_paid, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 font-medium text-indigo-400 bg-indigo-900/5">₺{{ number_format($item->total_employer_cost, 2, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="px-4 py-12 text-center text-gray-500">
                                Bordro henüz hesaplanmamış. "Tümünü Hesapla" butonu ile başlatabilirsiniz.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
