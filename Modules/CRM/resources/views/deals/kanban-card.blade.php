<div 
    id="deal-card-{{ $deal->id }}"
    draggable="true"
    @dragstart="$event.dataTransfer.setData('text/plain', '{{ $deal->id }}'); $event.dataTransfer.effectAllowed = 'move';"
    @dragend="dragging = false"
    class="bg-slate-800/80 hover:bg-slate-800 border border-white/5 rounded-xl p-4 cursor-grab active:cursor-grabbing shadow-sm hover:shadow-md hover:border-blue-500/30 transition-all duration-200 group relative backdrop-blur-sm"
>
    <!-- Priority/High Value Indicator -->
    @if($deal->amount > 10000)
    <div class="absolute top-0 right-0 w-2 h-2 rounded-bl-lg bg-emerald-500 shadow-[0_0_8px_rgba(var(--color-emerald-500),0.5)]"></div>
    @endif

    <div class="flex flex-col gap-2">
        <h4 class="font-bold text-white text-sm group-hover:text-blue-400 transition-colors line-clamp-2">
            {{ $deal->title }}
        </h4>
        
        <div class="flex items-center justify-between mt-1">
            <span class="text-xs font-bold text-slate-300 bg-slate-900/50 px-2 py-1 rounded border border-white/5">
                {{ number_format($deal->amount, 2) }} ₺
            </span>
            <span class="text-[10px] text-slate-500 font-medium">
                {{ $deal->probability }}% Olasılık
            </span>
        </div>

        <div class="h-1 w-full bg-slate-900 rounded-full mt-1 overflow-hidden">
            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $deal->probability }}%"></div>
        </div>

        <div class="flex items-center justify-between mt-3 pt-3 border-t border-white/5">
            <div class="flex items-center gap-2">
                @if($deal->contact)
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-[10px] text-white font-bold" title="{{ $deal->contact->name }} {{ $deal->contact->surname }}">
                        {{ substr($deal->contact->name, 0, 1) }}{{ substr($deal->contact->surname, 0, 1) }}
                    </div>
                @endif
                <div class="text-xs text-slate-400 truncate max-w-[100px]">
                    {{ $deal->contact ? $deal->contact->name . ' ' . $deal->contact->surname : 'Bağlantı Yok' }}
                </div>
            </div>
             <div class="text-[10px] text-slate-600 font-medium">
                {{ $deal->created_at->format('d.m.Y') }}
            </div>
        </div>
    </div>
</div>
