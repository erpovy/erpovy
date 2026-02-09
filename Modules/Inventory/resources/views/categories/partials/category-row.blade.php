<tr class="hover:bg-white/5 transition-colors group">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center" style="padding-left: {{ $level * 24 }}px;">
            @if($category->icon)
                <span class="material-symbols-outlined text-primary-400 text-2xl">{{ $category->icon }}</span>
            @else
                <span class="material-symbols-outlined text-slate-600 text-2xl">folder</span>
            @endif
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center gap-2">
            @if($level > 0)
                <span class="text-slate-600">└─</span>
            @endif
            <div>
                <div class="text-sm font-medium text-white">{{ $category->name }}</div>
                @if($category->parent)
                    <div class="text-xs text-slate-500">{{ $category->path }}</div>
                @endif
            </div>
        </div>
    </td>
    <td class="px-6 py-4 text-sm text-slate-400">
        {{ Str::limit($category->description, 50) }}
    </td>
    <td class="px-6 py-4 text-center">
        @if($category->children->count() > 0)
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">
                {{ $category->children->count() }}
            </span>
        @else
            <span class="text-slate-600">-</span>
        @endif
    </td>
    <td class="px-6 py-4 text-center">
        @if($category->products->count() > 0)
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-500/10 text-green-400 border border-green-500/20">
                {{ $category->products->count() }}
            </span>
        @else
            <span class="text-slate-600">0</span>
        @endif
    </td>
    <td class="px-6 py-4 text-center">
        @if($category->is_active)
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-500/10 text-green-400 border border-green-500/20">Aktif</span>
        @else
            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-500/10 text-red-400 border border-red-500/20">Pasif</span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('inventory.categories.edit', $category) }}" 
               class="p-2 rounded-lg bg-slate-500/10 text-slate-400 hover:bg-slate-500/20 hover:text-white border border-slate-500/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            <form action="{{ route('inventory.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Bu kategoriyi silmek istediğinizden emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 border border-red-500/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
        </div>
    </td>
</tr>

{{-- Alt kategorileri recursive olarak göster --}}
@if($category->children->count() > 0)
    @foreach($category->children as $child)
        @include('inventory::categories.partials.category-row', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif
