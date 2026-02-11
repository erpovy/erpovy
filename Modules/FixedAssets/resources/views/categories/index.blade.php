<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/5 via-purple-500/5 to-blue-500/5 animate-pulse"></div>
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tight mb-1">
                        Demirbaş Kategorileri
                    </h2>
                    <p class="text-gray-600 dark:text-slate-400 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">category</span>
                        Kategori Yönetimi ve Sınıflandırma
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('fixedassets.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-slate-400 font-bold text-sm transition-all hover:bg-gray-200 dark:hover:bg-white/10">
                        Demirbaş Listesi
                    </a>
                    <button @click="$dispatch('open-modal', 'create-category')" class="group relative px-6 py-2 rounded-xl bg-primary text-gray-900 dark:text-white font-black text-sm uppercase tracking-widest shadow-[0_0_20px_rgba(var(--color-primary),0.3)] hover:scale-105 transition-all">
                        <div class="relative flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                             Yeni Kategori
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="container mx-auto max-w-5xl px-6 lg:px-8">
            <x-card class="p-0 border-gray-200 dark:border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-white/[0.02] border-b border-gray-200 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Kategori Adı</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest">Açıklama</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-center">Demirbaş Sayısı</th>
                                <th class="p-4 text-[11px] font-black text-gray-600 dark:text-slate-500 uppercase tracking-widest text-right">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($categories as $category)
                            <tr class="hover:bg-gray-100 dark:hover:bg-white/5 transition-colors group">
                                <td class="p-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $category->name }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="text-xs text-gray-600 dark:text-slate-400">{{ $category->description ?? '-' }}</div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary">
                                        {{ $category->assets_count }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2" x-data="{ openEdit: false }">
                                        <!-- Edit Button -->
                                        <button @click="$dispatch('open-modal', 'edit-category-{{ $category->id }}')" class="p-2 text-slate-400 hover:text-blue-400 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </button>

                                        <!-- Delete Form -->
                                        @if($category->assets_count == 0)
                                        <form action="{{ route('fixedassets.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                        @else
                                        <span class="p-2 text-slate-600 cursor-not-allowed" title="Demirbaş içeren kategori silinemez">
                                            <span class="material-symbols-outlined text-[20px]">delete_forever</span>
                                        </span>
                                        @endif

                                        <!-- Edit Modal -->
                                        <x-modal name="edit-category-{{ $category->id }}" focusable>
                                            <form method="POST" action="{{ route('fixedassets.categories.update', $category->id) }}" class="p-6">
                                                @csrf
                                                @method('PUT')

                                                <h2 class="text-lg font-black text-gray-900 dark:text-white mb-6">
                                                    Kategori Düzenle: {{ $category->name }}
                                                </h2>

                                                <div class="space-y-4">
                                                    <div>
                                                        <x-input-label for="name" value="Kategori Adı" />
                                                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="$category->name" required />
                                                    </div>

                                                    <div>
                                                        <x-input-label for="description" value="Açıklama" />
                                                        <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ $category->description }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="mt-6 flex justify-end gap-3">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        İptal
                                                    </x-secondary-button>

                                                    <x-primary-button class="ml-3">
                                                        Güncelle
                                                    </x-primary-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-gray-600 dark:text-slate-500 italic">
                                    Henüz kategori oluşturulmamış.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-category" focusable>
        <form method="POST" action="{{ route('fixedassets.categories.store') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-black text-gray-900 dark:text-white mb-6">
                Yeni Kategori Oluştur
            </h2>

            <div class="space-y-4">
                <div>
                    <x-input-label for="name" value="Kategori Adı" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Örn: Bilgisayar, Mobilya" required />
                </div>

                <div>
                    <x-input-label for="description" value="Açıklama" />
                    <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3" placeholder="Kategori açıklaması..."></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    İptal
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    Oluştur
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
