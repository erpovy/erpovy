<x-app-layout>
    <x-slot name="header">
        İnsan Kaynakları
    </x-slot>

    <div class="h-[calc(100vh-8rem)] flex flex-col">
        <x-card class="flex-1 flex flex-col overflow-hidden">
            <div class="p-6 flex-1 flex flex-col" x-data="leaveCalendar()">
                <!-- Header & Controls -->
                <div class="flex justify-between items-start mb-6 border-b border-white/5 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-white mb-2">İzin Takvimi</h2>
                        <div class="flex items-center gap-4 text-sm">
                            <button @click="prevMonth" class="text-slate-400 hover:text-white transition-colors">
                                <span class="material-symbols-outlined">chevron_left</span>
                            </button>
                            <span class="text-white font-medium min-w-[120px] text-center" x-text="currentMonthName + ' ' + currentYear"></span>
                            <button @click="nextMonth" class="text-slate-400 hover:text-white transition-colors">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </button>
                            <button @click="goToToday" class="ml-4 text-xs font-bold text-primary hover:text-primary-300 uppercase tracking-wider">Bugün</button>
                        </div>
                    </div>

                    <button @click="isModalOpen = true" class="bg-primary hover:bg-primary/80 text-white font-bold py-2 px-4 rounded-lg shadow-neon transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined">add</span>
                        Yeni İzin Girişi
                    </button>
                </div>

                <!-- Calendar Grid -->
                <div class="flex-1 flex flex-col min-h-0">
                    <!-- Days Header -->
                    <div class="grid grid-cols-7 mb-2" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                        <template x-for="day in ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz']">
                            <div class="text-center text-xs font-bold text-slate-500 uppercase tracking-widest py-2" x-text="day"></div>
                        </template>
                    </div>

                    <!-- Days Grid -->
                    <div class="flex-1 grid grid-cols-7 grid-rows-6 gap-px bg-white/5 border border-white/5 rounded-lg overflow-hidden" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
                        <template x-for="date in calendarDays">
                            <div class="bg-[#0f172a] p-2 min-h-[80px] relative hover:bg-white/5 transition-colors group border-r border-b border-white/5"
                                 :class="{'opacity-30': !date.isCurrentMonth, '!bg-blue-900/10': date.isToday}">
                                <span class="text-xs font-medium text-slate-400" :class="{'text-primary font-bold': date.isToday}" x-text="date.day"></span>
                                
                                <!-- Events -->
                                <div class="mt-1 space-y-1 overflow-y-auto max-h-[70px] custom-scrollbar">
                                    <template x-for="event in getEventsForDate(date.fullDate)">
                                        <div class="text-[10px] items-center gap-1 rounded px-1.5 py-0.5 truncate border border-white/5 shadow-sm cursor-pointer hover:scale-[1.02] transition-transform"
                                             :class="getEventColor(event.type)"
                                             :title="event.title">
                                            <span class="font-bold" x-text="getInitials(event.employee_name)"></span>
                                            <span class="opacity-80" x-text="getShortType(event.type)"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Create Leave Modal -->
                <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                    <div class="bg-[#0f172a] border border-white/10 rounded-xl shadow-glass p-6 w-full max-w-md" @click.away="isModalOpen = false">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-white">Yeni İzin Tanımla</h3>
                            <button @click="isModalOpen = false" class="text-slate-400 hover:text-white">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>

                        <form action="{{ route('hr.leaves.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="input-label text-gray-300">Çalışan</label>
                                <select name="employee_id" class="custom-input bg-[#0f172a]" required>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="input-label text-gray-300">Başlangıç</label>
                                    <input type="date" name="start_date" class="custom-input" required>
                                </div>
                                <div>
                                    <label class="input-label text-gray-300">Bitiş</label>
                                    <input type="date" name="end_date" class="custom-input" required>
                                </div>
                            </div>

                            <div>
                                <label class="input-label text-gray-300">İzin Türü</label>
                                <select name="type" class="custom-input bg-[#0f172a]" required>
                                    <option value="annual">Yıllık İzin</option>
                                    <option value="sick">Raporlu / Hastalık</option>
                                    <option value="unpaid">Ücretsiz İzin</option>
                                    <option value="casual">Mazeret İzni</option>
                                </select>
                            </div>

                            <div>
                                <label class="input-label text-gray-300">Açıklama</label>
                                <textarea name="description" rows="2" class="custom-input"></textarea>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="btn-primary w-full justify-center">Oluştur</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Calendar Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('leaveCalendar', () => ({
                currentDate: new Date(),
                isModalOpen: false,
                events: @json($leaves),
                
                get currentMonthName() {
                    return this.currentDate.toLocaleString('tr-TR', { month: 'long' });
                },
                
                get currentYear() {
                    return this.currentDate.getFullYear();
                },

                get calendarDays() {
                    const days = [];
                    const year = this.currentDate.getFullYear();
                    const month = this.currentDate.getMonth();
                    
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    
                    // Adjust for Monday start (0-6 -> 1-7 logic)
                    let startDay = firstDay.getDay() || 7; 
                    startDay = startDay - 1; // 0 for Monday

                    // Previous month pad
                    const prevLastDay = new Date(year, month, 0).getDate();
                    for (let i = startDay - 1; i >= 0; i--) {
                        days.push({
                            day: prevLastDay - i,
                            isCurrentMonth: false,
                            isToday: false,
                            fullDate: '' // Not needed for logic
                        });
                    }

                    // Current month days
                    const today = new Date();
                    for (let i = 1; i <= lastDay.getDate(); i++) {
                        const d = new Date(year, month, i);
                        const dateStr = d.toISOString().split('T')[0];
                        days.push({
                            day: i,
                            isCurrentMonth: true,
                            isToday: d.toDateString() === today.toDateString(),
                            fullDate: dateStr
                        });
                    }

                    // Next month pad to fill 42 cells (6 rows)
                    const remaining = 42 - days.length;
                    for (let i = 1; i <= remaining; i++) {
                        days.push({
                            day: i,
                            isCurrentMonth: false,
                            isToday: false,
                            fullDate: ''
                        });
                    }

                    return days;
                },

                prevMonth() {
                    this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
                },

                nextMonth() {
                    this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
                },

                goToToday() {
                    this.currentDate = new Date();
                },

                getEventsForDate(dateStr) {
                    if (!dateStr) return [];
                    return this.events.filter(e => dateStr >= e.start && dateStr <= e.end);
                },

                getEventColor(type) {
                    const colors = {
                        'annual': 'bg-green-500/20 text-green-400 border-green-500/30',
                        'sick': 'bg-red-500/20 text-red-400 border-red-500/30',
                        'unpaid': 'bg-gray-500/20 text-gray-400 border-gray-500/30 font-extrabold',
                        'casual': 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30'
                    };
                    return colors[type] || 'bg-blue-500/20 text-blue-400';
                },

                getInitials(name) {
                    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                },
                
                getShortType(type) {
                     const types = {
                        'annual': 'Yıllık',
                        'sick': 'Rapor',
                        'unpaid': 'Ücretsiz',
                        'casual': 'Mazeret'
                    };
                    return types[type] || type;
                }
            }))
        });
    </script>
</x-app-layout>
