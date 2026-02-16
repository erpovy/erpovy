<x-app-layout>
<div class="px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between bg-white/50 dark:bg-white/5 p-8 rounded-3xl border border-gray-100 dark:border-white/10 backdrop-blur-xl shadow-glass">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-black text-gray-900 dark:text-gray-100 flex items-center gap-3">
                <span class="material-symbols-outlined text-blue-500 text-4xl">business_center</span>
                Tedarikçi Yönetimi
            </h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-400 font-medium">
                Sistemdeki kayıtlı tüm satıcılar ve alım performansları.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 flex gap-3">
            <a href="{{ route('crm.contacts.index') }}" class="group relative flex items-center justify-center gap-2 rounded-2xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                <span class="material-symbols-outlined text-[20px]">group</span>
                KİŞİ YÖNETİMİ
            </a>
        </div>
    </div>

    <!-- Suppliers Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($suppliers as $supplier)
            <div class="group relative overflow-hidden rounded-3xl bg-white/50 dark:bg-white/5 p-8 border border-gray-100 dark:border-white/10 backdrop-blur-xl transition-all hover:shadow-2xl hover:-translate-y-1">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center font-black text-2xl text-white shadow-lg shadow-blue-500/30">
                            {{ substr($supplier->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white truncate max-w-[150px]">
                                {{ $supplier->name }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">alternate_email</span>
                                {{ $supplier->email ?: 'E-posta yok' }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('purchasing.suppliers.show', $supplier) }}" class="p-2 bg-gray-100 dark:bg-white/5 rounded-xl text-gray-400 hover:text-blue-500 transition-colors">
                        <span class="material-symbols-outlined">open_in_new</span>
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-100 dark:border-white/5">
                    <div>
                        <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Siparişler</p>
                        <p class="mt-1 text-lg font-black text-gray-900 dark:text-white">{{ $supplier->total_orders }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Toplam Harcama</p>
                        <p class="mt-1 text-lg font-black text-emerald-500">{{ number_format($supplier->total_spent ?: 0, 2) }} ₺</p>
                    </div>
                </div>

                <!-- Hover Decoration -->
                <div class="absolute bottom-0 left-0 w-0 h-1 bg-gradient-to-r from-blue-500 to-transparent transition-all duration-500 group-hover:w-full"></div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <span class="material-symbols-outlined text-6xl text-gray-200 dark:text-white/10">business</span>
                <p class="mt-4 text-gray-500 dark:text-gray-400 italic">Henüz tedarikçi bulunmuyor.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $suppliers->links() }}
    </div>
</div>
</x-app-layout>
