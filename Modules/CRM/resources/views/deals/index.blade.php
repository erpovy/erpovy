<x-app-layout>
    <div class="py-12" x-data="kanbanBoard()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header & Actions -->
            <div class="relative overflow-hidden group rounded-2xl p-0.5 bg-gradient-to-r from-slate-800 to-slate-900 border border-white/10">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-blue-500/10 animate-pulse"></div>
                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 bg-slate-900/90 backdrop-blur-xl rounded-2xl">
                    <div>
                        <h2 class="text-3xl font-black text-white tracking-tight mb-1">Fırsatlar Panosu</h2>
                        <p class="text-slate-400 font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">view_kanban</span>
                            Satış süreçlerinizi ve anlaşmalarınızı yönetin.
                        </p>
                    </div>
                    <div>
                         <button @click="openCreateModal()" class="group relative px-6 py-3 overflow-hidden rounded-xl bg-blue-600 text-white font-black text-sm uppercase tracking-widest transition-all hover:scale-105 active:scale-95 shadow-[0_0_20px_rgba(var(--color-blue-600),0.3)]">
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            <div class="relative flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                                Yeni Fırsat
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Kanban Board -->
            <div class="flex overflow-x-auto pb-8 gap-6 min-h-[600px] select-none">
                
                @php
                    $stages = [
                        'new' => ['label' => 'Yeni', 'color' => 'blue'],
                        'negotiation' => ['label' => 'Görüşülüyor', 'color' => 'orange'],
                        'proposal' => ['label' => 'Teklif', 'color' => 'purple'],
                        'won' => ['label' => 'Kazanıldı', 'color' => 'green'],
                        'lost' => ['label' => 'Kaybedildi', 'color' => 'red'],
                    ];
                @endphp

                @foreach($stages as $stageKey => $stageConfig)
                    <div class="flex-none w-80 flex flex-col gap-4">
                        <!-- Column Header -->
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-900/80 border border-white/5 backdrop-blur-xl sticky top-0 z-10 shadow-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-{{ $stageConfig['color'] }}-500 shadow-[0_0_10px_rgba(var(--color-{{ $stageConfig['color'] }}-500),0.5)]"></div>
                                <span class="font-black text-white text-sm uppercase tracking-wide">{{ $stageConfig['label'] }}</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-white/5 text-slate-400 border border-white/5" x-text="columns['{{ $stageKey }}']?.length || 0">0</span>
                        </div>

                        <!-- Drop Zone -->
                        <div 
                            class="flex-1 flex flex-col gap-3 min-h-[200px] p-2 rounded-xl transition-colors duration-200"
                            :class="dragging ? 'bg-white/5 border border-white/5 border-dashed' : ''"
                            @dragover.prevent="allowDrop($event)"
                            @drop="drop($event, '{{ $stageKey }}')"
                        >
                            @forelse($dealsByStage[$stageKey] as $deal)
                                @include('crm::deals.kanban-card', ['deal' => $deal])
                            @empty
                                <!-- Empty Placeholder (Only visible if truly empty on load, but alpine handles dynamic) -->
                            @endforelse
                        </div>
                    </div>
                @endforeach

            </div>

        </div>

    <!-- Create Deal Modal -->
    <div x-show="showModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto" 
        style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showModal = false">
                <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-slate-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/10">
                <form action="{{ route('crm.deals.store') }}" method="POST">
                    @csrf
                    <div class="px-6 py-6 border-b border-white/5 flex items-center justify-between bg-white/5">
                        <h3 class="text-xl font-black text-white flex items-center gap-2">
                             <span class="material-symbols-outlined text-blue-400">add_circle</span>
                             Yeni Fırsat Oluştur
                        </h3>
                        <button type="button" @click="showModal = false" class="text-slate-400 hover:text-white transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Title -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Fırsat Başlığı</label>
                            <input type="text" name="title" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-blue-500/50 focus:ring-0 transition-all font-medium" placeholder="Örn: Yazılım Lisans Satışı">
                        </div>

                        <!-- Amount & Probability -->
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Tutar (₺)</label>
                                <input type="number" name="amount" required step="0.01" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-blue-500/50 focus:ring-0 transition-all font-medium" placeholder="0.00">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Olasılık (%)</label>
                                <input type="number" name="probability" required min="0" max="100" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-blue-500/50 focus:ring-0 transition-all font-medium" value="50">
                            </div>
                        </div>

                         <!-- Stage -->
                         <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Aşama</label>
                            <select name="stage" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-blue-500/50 focus:ring-0 transition-all font-medium option:bg-slate-900">
                                @foreach($stages as $key => $config)
                                    <option value="{{ $key }}">{{ $config['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Expected Close Date -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Tahmini Kapanış</label>
                            <input type="date" name="expected_close_date" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-blue-500/50 focus:ring-0 transition-all font-medium">
                        </div>

                         <!-- Description -->
                         <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Açıklama</label>
                            <textarea name="description" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:border-blue-500/50 focus:ring-0 transition-all font-medium"></textarea>
                        </div>

                    </div>

                    <div class="px-6 py-4 bg-white/5 flex justify-end gap-3 border-t border-white/5">
                        <button type="button" @click="showModal = false" class="px-6 py-2.5 rounded-xl border border-white/10 text-slate-400 font-bold hover:bg-white/5 transition-all">İptal</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-black uppercase tracking-widest hover:bg-blue-500 hover:scale-105 active:scale-95 transition-all shadow-lg shadow-blue-600/20">Oluştur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('kanbanBoard', () => ({
                dragging: false,
                showModal: false,
                columns: {
                    // Initial counts can be populated via server-side blade echo if needed for strict reactivity
                    // For now handled by blade loop directly
                },

                openCreateModal() {
                    this.showModal = true;
                },

                allowDrop(e) {
                    e.preventDefault();
                    this.dragging = true;
                },

                drop(e, newStage) {
                    this.dragging = false;
                    const dealId = e.dataTransfer.getData('text/plain');
                    if (!dealId) return;

                    // Move card DOM element purely for visual feedback before reload/ajax?
                    // Better pattern: Submit AJAX to update stage, if success, move card or reload.
                    // For simple MVP: AJax update then reload or just move box. 
                    
                    const card = document.getElementById('deal-card-' + dealId);
                    if(card) {
                        e.target.closest('.flex-col').appendChild(card); // Simple DOM move
                        
                        // Send Request
                        fetch(`/crm/deals/${dealId}/stage`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ stage: newStage })
                        }).then(response => {
                            if(!response.ok) {
                                // Revert move if failed
                                alert('Aşama güncellenemedi.');
                                window.location.reload(); 
                            }
                        });
                    }
                }
            }));
        });
    </script>
</x-app-layout>
