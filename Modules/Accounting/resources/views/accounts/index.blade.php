<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            
            <!-- Content -->
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Hesap Planı
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">account_tree</span>
                        Tek Düzen Hesap Planı Yönetimi
                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                        <span class="material-symbols-outlined text-[16px]">schedule</span>
                        <span id="live-clock" class="font-mono">--:--</span>
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- Import Defaults Wrapper -->
                    <div x-data="{ showImportModal: false }">
                        <!-- Trigger Button -->
                        <button @click="showImportModal = true" type="button" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-slate-800 text-gray-700 dark:text-slate-300 font-bold text-sm uppercase tracking-widest transition-all hover:bg-slate-700 hover:text-gray-900 dark:text-white border border-gray-200 dark:border-white/10 hover:border-white/20">
                            <div class="relative flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">cloud_download</span>
                                <span>Standart Hesapları Yükle</span>
                            </div>
                        </button>

                        <!-- Custom Confirmation Modal -->
                        <div x-show="showImportModal" 
                             style="display: none;"
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                            <!-- Overlay -->
                            <div x-show="showImportModal"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 @click="showImportModal = false"
                                 class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

                            <!-- Modal Content -->
                            <div x-show="showImportModal"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                                 class="relative w-full max-w-lg glass-card border-none bg-slate-900/90 shadow-2xl shadow-blue-500/10 p-8 rounded-3xl"
                                 @click.stop>
                                
                                <div class="text-center">
                                    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-blue-500/10 text-blue-500 border border-blue-500/20 shadow-[0_0_30px_rgba(59,130,246,0.2)]">
                                        <span class="material-symbols-outlined text-[40px]">cloud_download</span>
                                    </div>
                                    
                                    <h3 class="mb-3 text-2xl font-black text-gray-900 dark:text-white tracking-tight">Standart Hesap Planı</h3>
                                    
                                    <div class="mb-8 space-y-3 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                                        <p>Tek Düzen Hesap Planı'na (TDHP) ait standart hesaplar şirket hesaplarınıza eklenecektir.</p>
                                        <ul class="text-left bg-white/5 p-4 rounded-xl space-y-2 border border-gray-200 dark:border-white/5">
                                            <li class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span>
                                                <span>100-900 arası ana hesaplar</span>
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-green-500 text-[18px]">check_circle</span>
                                                <span>Varlık, Kaynak, Gelir ve Gider hesapları</span>
                                            </li>
                                            <li class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-blue-400 text-[18px]">info</span>
                                                <span>Mevcut hesaplarınız bu işlemden <strong class="text-gray-900 dark:text-white">etkilenmez</strong></span>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                                        <button @click="showImportModal = false" type="button" class="flex-1 rounded-xl bg-white/5 px-6 py-4 text-sm font-bold text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:bg-white/10 hover:text-gray-900 dark:text-white transition-colors uppercase tracking-wider">
                                            Vazgeç
                                        </button>
                                        
                                        <form action="{{ route('accounting.accounts.import-defaults') }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full h-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-sm font-black text-gray-900 dark:text-white shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 hover:-translate-y-1 active:scale-95 transition-all uppercase tracking-wider flex items-center justify-center gap-2">
                                                <span>Yüklemeyi Başlat</span>
                                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('accounting.accounts.create') }}" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-primary text-gray-900 dark:text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-primary),0.3)]">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add_circle</span>
                            Yeni Hesap Ekle
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8 space-y-8">
            
            <!-- Row 1: Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['label' => 'Toplam Hesap', 'value' => $stats['total'], 'icon' => 'account_balance', 'color' => 'blue'],
                    ['label' => 'Varlık Hesapları', 'value' => $stats['assets'], 'icon' => 'account_balance_wallet', 'color' => 'green'],
                    ['label' => 'Kaynak Hesapları', 'value' => $stats['liabilities'], 'icon' => 'payments', 'color' => 'red'],
                    ['label' => 'Diğer Hesaplar', 'value' => $stats['other'], 'icon' => 'category', 'color' => 'purple']
                ] as $stat)
                <div class="group relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $stat['color'] }}-500/20 to-{{ $stat['color'] }}-500/5 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <x-card class="h-full relative p-6 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl hover:border-{{ $stat['color'] }}-500/30 transition-all duration-300 group-hover:-translate-y-1">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 rounded-xl bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-400">
                                <span class="material-symbols-outlined text-[24px]">{{ $stat['icon'] }}</span>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-gray-900 dark:text-white mb-1">{{ number_format($stat['value']) }}</div>
                        <div class="text-xs text-gray-600 dark:text-slate-500 font-bold uppercase tracking-wider">{{ $stat['label'] }}</div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- Filters & Search -->
            <x-card class="p-4 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl">
                <form action="{{ route('accounting.accounts.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-600 dark:text-slate-500">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Hesap kodu veya adı ile ara..." 
                               class="w-full pl-12 pr-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white placeholder-slate-500 focus:border-primary/50 focus:ring-0 transition-all">
                    </div>
                    <div class="w-full md:w-64 relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-gray-600 dark:text-slate-500">filter_list</span>
                        <select name="type" onchange="this.form.submit()" 
                                class="w-full pl-12 pr-4 py-3 bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-gray-900 dark:text-white appearance-none focus:border-primary/50 focus:ring-0 transition-all">
                            <option value="">Tüm Türler</option>
                            @foreach(\Modules\Accounting\Models\Account::getTypes() as $key => $label)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </x-card>

            <!-- Accounts Table -->
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center w-24">Kod</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Hesap Adı</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Tür</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">Bakiye</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($accounts as $account)
                            <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors group">
                                <td class="p-4 text-center">
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-white/10 rounded text-xs font-mono font-bold text-primary group-hover:bg-primary/20 transition-colors">
                                        {{ $account->code }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">{{ $account->name }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-slate-400 border border-gray-200 dark:border-white/5">
                                        {{ $account->type_label }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-sm font-black {{ $account->current_balance < 0 ? 'text-red-400' : 'text-gray-900 dark:text-white' }}">
                                        {{ number_format($account->current_balance ?? 0, 2, ',', '.') }} ₺
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('accounting.accounts.edit', $account) }}" class="p-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500/20 transition-all active:scale-90" title="Düzenle">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('accounting.accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('Bu hesabı silmek istediğinize emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-all active:scale-90" title="Sil">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-600 dark:text-slate-500 italic">
                                    Kayıt bulunamadı.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($accounts->hasPages())
                <div class="p-6 border-t border-gray-200 dark:border-white/5 bg-white/[0.01]">
                    {{ $accounts->links() }}
                </div>
                @endif
            </x-card>
        </div>
    </div>

    <!-- Live Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const target = document.getElementById('live-clock');
            if (target) target.textContent = `${hours}:${minutes}`;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</x-app-layout>

