<x-app-layout>
    <!-- Main Background Wrapper with Gradient for Glass Effect Context -->
    <div class="min-h-screen bg-gray-50 dark:bg-gradient-to-br dark:from-slate-800 dark:via-slate-900 dark:to-zinc-900 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-300 dark:to-indigo-400 drop-shadow-lg">
                        Üretim Yönetim Paneli
                    </h1>
                    <p class="text-gray-600 dark:text-slate-400 mt-1 text-sm">Üretim süreçlerinizi tek bir noktadan yönetin</p>
                </div>
                <div class="flex space-x-3">
                    <button class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-gray-900 dark:text-white border border-gray-300 dark:border-white/10 backdrop-blur-md transition-all duration-300 shadow-lg text-sm font-medium">
                        Raporlar
                    </button>
                    <a href="{{ route('manufacturing.create') }}" class="relative z-50 px-4 py-2 rounded-xl bg-blue-600/80 hover:bg-blue-600 text-white border border-blue-400/30 backdrop-blur-md transition-all duration-300 shadow-lg shadow-blue-900/50 text-sm font-medium">
                        + Yeni İş Emri
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <!-- Active Work Orders Card -->
                <div class="group relative rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 p-6 backdrop-blur-xl shadow-xl transition-all hover:shadow-2xl hover:-translate-y-1">
                    <div class="absolute inset-0 bg-blue-500/10 rounded-2xl blur-xl group-hover:bg-blue-500/20 transition-all opacity-0 group-hover:opacity-100"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-blue-600 dark:text-blue-200 text-xs font-bold uppercase tracking-wider mb-1">Aktif İş Emirleri</p>
                            <h2 class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $activeWorkOrders }}</h2>
                            <p class="text-gray-600 dark:text-slate-400 text-xs mt-2">Devam eden üretimler</p>
                        </div>
                        <div class="p-4 bg-blue-500/20 rounded-2xl border border-blue-500/30 shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Quality Checks Card -->
                <div class="group relative rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 p-6 backdrop-blur-xl shadow-xl transition-all hover:shadow-2xl hover:-translate-y-1">
                    <div class="absolute inset-0 bg-amber-500/10 rounded-2xl blur-xl group-hover:bg-amber-500/20 transition-all opacity-0 group-hover:opacity-100"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-amber-600 dark:text-amber-200 text-xs font-bold uppercase tracking-wider mb-1">Kalite Kontrol</p>
                            <h2 class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $pendingQualityChecks }}</h2>
                            <p class="text-gray-600 dark:text-slate-400 text-xs mt-2">Onay bekleyenler</p>
                        </div>
                        <div class="p-4 bg-amber-500/20 rounded-2xl border border-amber-500/30 shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                 <!-- Efficiency Placeholder Card -->
                 <div class="group relative rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 p-6 backdrop-blur-xl shadow-xl transition-all hover:shadow-2xl hover:-translate-y-1">
                    <div class="absolute inset-0 bg-emerald-500/10 rounded-2xl blur-xl group-hover:bg-emerald-500/20 transition-all opacity-0 group-hover:opacity-100"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-emerald-600 dark:text-emerald-200 text-xs font-bold uppercase tracking-wider mb-1">Verimlilik</p>
                            <h2 class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">%94</h2>
                            <p class="text-gray-600 dark:text-slate-400 text-xs mt-2">Hedefin üzerinde</p>
                        </div>
                        <div class="p-4 bg-emerald-500/20 rounded-2xl border border-emerald-500/30 shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Work Orders Table -->
            <div class="relative rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 backdrop-blur-xl shadow-2xl overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-500 opacity-50"></div>
                
                <div class="px-8 py-6 border-b border-gray-200 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-white/5">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Son İş Emirleri</h3>
                    <button class="text-xs text-blue-600 dark:text-blue-300 hover:text-blue-800 dark:hover:text-white transition-colors uppercase font-semibold">Tümünü Gör →</button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-100 dark:bg-black/20 text-gray-700 dark:text-slate-300 uppercase text-xs font-semibold tracking-wider">
                            <tr>
                                <th class="py-4 px-8 text-left">İş Emri No</th>
                                <th class="py-4 px-8 text-left">Ürün</th>
                                <th class="py-4 px-8 text-left">Miktar</th>
                                <th class="py-4 px-8 text-center">Durum</th>
                                <th class="py-4 px-8 text-left">Teslim Tarihi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-slate-300 text-sm divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($recentWorkOrders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors duration-200 group">
                                    <td class="py-4 px-8 whitespace-nowrap font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-200 transition-colors">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="py-4 px-8 text-left">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-lg bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center mr-3">
                                                <span class="text-xs font-bold text-indigo-300">P</span>
                                            </div>
                                            <span class="font-medium">{{ optional($order->product)->name ?? 'Bilinmeyen Ürün' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-8 text-left font-mono text-gray-600 dark:text-slate-400">{{ $order->quantity }}</td>
                                    <td class="py-4 px-8 text-center">
                                        @php
                                            $statusClasses = match($order->status) {
                                                'completed' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/20',
                                                'in_progress' => 'bg-blue-500/20 text-blue-300 border-blue-500/20',
                                                'pending' => 'bg-amber-500/20 text-amber-300 border-amber-500/20',
                                                'cancelled' => 'bg-rose-500/20 text-rose-300 border-rose-500/20',
                                                default => 'bg-slate-500/20 text-slate-300 border-slate-500/20'
                                            };
                                            $statusLabel = match($order->status) {
                                                'completed' => 'Tamamlandı',
                                                'in_progress' => 'Devam Ediyor',
                                                'pending' => 'Bekliyor',
                                                'cancelled' => 'İptal',
                                                default => $order->status
                                            };
                                        @endphp
                                        <span class="{{ $statusClasses }} py-1.5 px-3 rounded-lg text-xs font-bold border shadow-sm backdrop-blur-sm">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-8 text-left text-gray-600 dark:text-slate-400">
                                        {{ $order->due_date ? $order->due_date->format('d.m.Y') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500 dark:text-slate-500 italic">Henüz kayıtlı iş emri bulunmamaktadır.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
