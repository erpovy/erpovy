<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Fatura Şablonları') }}
            </h2>
            <a href="{{ route('accounting.invoice-templates.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-lg">add</span>
                Yeni Şablon
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        <x-card class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs text-slate-400 uppercase border-b border-white/5">
                            <th class="py-3 pl-2">Şablon Adı</th>
                            <th class="py-3">Varsayılan</th>
                            <th class="py-3 text-right pr-2">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($templates as $template)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-4 pl-2 font-medium text-white">{{ $template->name }}</td>
                                <td class="py-4">
                                    @if($template->is_default)
                                        <span class="bg-green-500/20 text-green-400 text-xs font-bold px-2 py-1 rounded">Varsayılan</span>
                                    @else
                                        <span class="text-slate-500 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="py-4 text-right pr-2">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('accounting.invoice-templates.edit', $template->id) }}" class="text-blue-400 hover:text-blue-300">
                                            <span class="material-symbols-outlined">edit</span>
                                        </a>
                                        
                                        @unless($template->is_default)
                                            <form action="{{ route('accounting.invoice-templates.destroy', $template->id) }}" method="POST" onsubmit="return confirm('Bu şablonu silmek istediğinize emin misiniz?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-300">
                                                    <span class="material-symbols-outlined">delete</span>
                                                </button>
                                            </form>
                                        @endunless
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-slate-400">
                                    Henüz hiç şablon oluşturulmamış. Yeni bir tane ekleyerek başlayın.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $templates->links() }}
            </div>
        </x-card>
    </div>
</x-app-layout>
