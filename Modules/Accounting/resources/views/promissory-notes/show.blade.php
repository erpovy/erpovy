<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-cyan-500/5 to-blue-500/5 animate-pulse"></div>
            
            <div class="relative flex items-center justify-between py-2">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <a href="{{ route('accounting.promissory-notes.index') }}" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-colors">
                            <span class="material-symbols-outlined">arrow_back</span>
                        </a>
                        <h2 class="font-black text-3xl text-white tracking-tight">
                            #{{ $note->note_number }}
                        </h2>
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $note->type === 'received' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $note->type_label }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($note->status === 'portfolio')
                        <a href="{{ route('accounting.promissory-notes.edit', $note) }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white rounded-lg font-medium transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined">edit</span>
                            Düzenle
                        </a>
                        <form action="{{ route('accounting.promissory-notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Bu senedi silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg font-medium transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined">delete</span>
                                Sil
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ 
        activeModal: null,
        transactionDate: '{{ date('Y-m-d') }}'
    }">
        <div class="container mx-auto max-w-[1920px] px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Sol Kolon: Detaylar -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Özet Kart -->
                    <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                <p class="text-sm text-slate-400 mb-1">Tutar</p>
                                <p class="text-2xl font-black text-white">{{ number_format($note->amount, 2) }} {{ $note->currency }}</p>
                            </div>
                            <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                <p class="text-sm text-slate-400 mb-1">Vade Tarihi</p>
                                <div class="flex items-center gap-2">
                                    <p class="text-xl font-bold {{ $note->is_overdue ? 'text-red-400' : 'text-white' }}">
                                        {{ $note->due_date->format('d.m.Y') }}
                                    </p>
                                    @if($note->is_overdue)
                                        <span class="text-xs px-2 py-0.5 rounded bg-red-500/20 text-red-400 font-medium">Gecikmiş</span>
                                    @else
                                        <span class="text-xs px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 font-medium">{{ $note->days_until_due }} gün kaldı</span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                <p class="text-sm text-slate-400 mb-1">Durum</p>
                                <p class="text-xl font-bold text-white flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full 
                                        {{ $note->status === 'portfolio' ? 'bg-blue-500' : '' }}
                                        {{ $note->status === 'deposited' ? 'bg-purple-500' : '' }}
                                        {{ $note->status === 'cashed' ? 'bg-green-500' : '' }}
                                        {{ $note->status === 'protested' ? 'bg-red-500' : '' }}
                                        {{ $note->status === 'transferred' ? 'bg-orange-500' : '' }}
                                        {{ $note->status === 'cancelled' ? 'bg-gray-500' : '' }}"></span>
                                    {{ $note->status_label }}
                                </p>
                            </div>
                        </div>
                    </x-card>

                    <!-- Detay Tablosu -->
                    <x-card class="border-white/10 bg-white/5 backdrop-blur-2xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-white/10">
                            <h3 class="text-lg font-semibold text-white">Senet Detayları</h3>
                        </div>
                        <div class="divide-y divide-white/10">
                            <div class="grid grid-cols-1 md:grid-cols-2 px-6 py-4 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-400">Senet Numarası</p>
                                    <p class="text-base text-white mt-1">{{ $note->note_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-400">Düzenleme Tarihi</p>
                                    <p class="text-base text-white mt-1">{{ $note->issue_date->format('d.m.Y') }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 px-6 py-4 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-400">Borçlu</p>
                                    <p class="text-base text-white mt-1">{{ $note->drawer }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-400">Düzenleme Yeri</p>
                                    <p class="text-base text-white mt-1">{{ $note->place_of_issue ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 px-6 py-4 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-400">Ödeme Yeri</p>
                                    <p class="text-base text-white mt-1">{{ $note->place_of_payment ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-400">Ciro Eden</p>
                                    <p class="text-base text-white mt-1">{{ $note->endorser ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 px-6 py-4 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-400">İlgili Cari</p>
                                    <p class="text-base text-white mt-1">
                                        @if($note->contact)
                                            <a href="{{ route('crm.contacts.show', $note->contact_id) }}" class="text-blue-400 hover:underline">
                                                {{ $note->contact->name }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($note->notes)
                            <div class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-400">Notlar</p>
                                <p class="text-base text-white mt-1">{{ $note->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Hareket Geçmişi -->
                    <x-card class="border-white/10 bg-white/5 backdrop-blur-2xl">
                        <div class="px-6 py-4 border-b border-white/10">
                            <h3 class="text-lg font-semibold text-white">İşlem Geçmişi</h3>
                        </div>
                        <div class="p-6">
                            <div class="relative border-l-2 border-white/10 ml-3 space-y-8">
                                @foreach($note->transactions->sortByDesc('created_at') as $transaction)
                                    <div class="relative pl-8">
                                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-[#1e293b] 
                                            {{ $transaction->action === 'received' || $transaction->action === 'cashed' ? 'bg-green-500' : '' }}
                                            {{ $transaction->action === 'issued' || $transaction->action === 'protested' ? 'bg-red-500' : '' }}
                                            {{ $transaction->action === 'deposited' ? 'bg-purple-500' : '' }}
                                            {{ $transaction->action === 'transferred' ? 'bg-orange-500' : '' }}
                                            {{ $transaction->action === 'cancelled' ? 'bg-gray-500' : 'bg-blue-500' }}"></div>
                                        
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-1">
                                            <h4 class="text-base font-semibold text-white">
                                                @switch($transaction->action)
                                                    @case('received') Senet Alındı @break
                                                    @case('issued') Senet Verildi @break
                                                    @case('deposited') Bankaya Yatırıldı @break
                                                    @case('cashed') Tahsil Edildi @break
                                                    @case('transferred') Ciro Edildi @break
                                                    @case('protested') Protesto Edildi @break
                                                    @case('cancelled') İptal Edildi @break
                                                    @default {{ $transaction->action }}
                                                @endswitch
                                            </h4>
                                            <span class="text-sm text-slate-400">{{ $transaction->created_at->format('d.m.Y H:i') }}</span>
                                        </div>
                                        <p class="text-sm text-slate-400">{{ $transaction->description }}</p>
                                        <p class="text-xs text-slate-500 mt-1">İşlemi Yapan: {{ $transaction->creator->name }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Sağ Kolon: İşlemler -->
                <div class="space-y-6">
                    <x-card class="p-6 border-white/10 bg-white/5 backdrop-blur-2xl">
                        <h3 class="text-lg font-semibold text-white mb-4">İşlemler</h3>
                        
                        <div class="space-y-3">
                            <!-- Bankaya Yatır -->
                            @if($note->status === 'portfolio')
                                <button @click="activeModal = 'deposit'" class="w-full py-3 px-4 bg-purple-600/20 hover:bg-purple-600/30 text-purple-400 border border-purple-500/30 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">account_balance</span>
                                    Bankaya Yatır / Öde
                                </button>
                            @endif

                            <!-- Tahsil Et -->
                            @if($note->status === 'portfolio' || $note->status === 'deposited')
                                <button @click="activeModal = 'cash'" class="w-full py-3 px-4 bg-green-600/20 hover:bg-green-600/30 text-green-400 border border-green-500/30 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">payments</span>
                                    {{ $note->type === 'issued' ? 'Ödemeyi Gerçekleştir' : 'Tahsil Et' }}
                                </button>
                            @endif

                            <!-- Ciro Et (Sadece Portföydeyse) -->
                            @if($note->status === 'portfolio')
                                <button @click="activeModal = 'transfer'" class="w-full py-3 px-4 bg-orange-600/20 hover:bg-orange-600/30 text-orange-400 border border-orange-500/30 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">swap_horiz</span>
                                    Ciro Et
                                </button>
                            @endif

                            <!-- Protesto (Tahsil Edilmemişse) -->
                            @if($note->status !== 'cashed' && $note->status !== 'cancelled' && $note->status !== 'transferred')
                                <button @click="activeModal = 'protest'" class="w-full py-3 px-4 bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-500/30 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">gavel</span>
                                    Protesto Çek
                                </button>
                            @endif

                            <!-- İptal Et (Sadece Portföydeyse) -->
                            @if($note->status === 'portfolio')
                                <button @click="activeModal = 'cancel'" class="w-full py-3 px-4 bg-gray-600/20 hover:bg-gray-600/30 text-gray-400 border border-gray-500/30 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined">cancel</span>
                                    İptal Et
                                </button>
                            @endif
                            
                            @if(!in_array($note->status, ['portfolio', 'deposited']))
                                <div class="text-center p-4 bg-white/5 rounded-lg border border-white/10">
                                    <p class="text-slate-400 text-sm">Bu senet için yapılabilecek işlem bulunmamaktadır.</p>
                                </div>
                            @endif
                        </div>
                    </x-card>
                </div>

            </div>
        </div>

        <!-- Modals -->
        <!-- 1. Bankaya Yatır Modal -->
        <div x-show="activeModal === 'deposit'" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm" x-cloak>
            <div class="w-full max-w-md bg-[#0f172a] border border-white/10 rounded-2xl shadow-2xl p-6" @click.away="activeModal = null">
                <h3 class="text-xl font-bold text-white mb-4">Bankaya Yatır</h3>
                <form action="{{ route('accounting.promissory-notes.deposit', $note) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">İşlem Tarihi</label>
                            <input type="date" name="transaction_date" x-model="transactionDate" class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Hesap Seçimi</label>
                            <select name="cash_bank_account_id" required class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white">
                                <option value="">Seçiniz...</option>
                                @foreach($cashBankAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->currency }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="activeModal = null" class="px-4 py-2 text-slate-400 hover:text-white transition-colors">İptal</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Tahsil Et Modal -->
        <div x-show="activeModal === 'cash'" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm" x-cloak>
            <div class="w-full max-w-md bg-[#0f172a] border border-white/10 rounded-2xl shadow-2xl p-6" @click.away="activeModal = null">
                <h3 class="text-xl font-bold text-white mb-4">Tahsil Et / Öde</h3>
                <form action="{{ route('accounting.promissory-notes.cash', $note) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">İşlem Tarihi</label>
                            <input type="date" name="transaction_date" x-model="transactionDate" class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Kasa/Banka Hesabı</label>
                            <select name="cash_bank_account_id" required class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white">
                                <option value="">Seçiniz...</option>
                                @foreach($cashBankAccounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }} ({{ $account->currency }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="activeModal = null" class="px-4 py-2 text-slate-400 hover:text-white transition-colors">İptal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg">Onayla</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Ciro Et Modal -->
        <div x-show="activeModal === 'transfer'" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm" x-cloak>
            <div class="w-full max-w-md bg-[#0f172a] border border-white/10 rounded-2xl shadow-2xl p-6" @click.away="activeModal = null">
                <h3 class="text-xl font-bold text-white mb-4">Ciro Et</h3>
                <form action="{{ route('accounting.promissory-notes.transfer', $note) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">İşlem Tarihi</label>
                            <input type="date" name="transaction_date" x-model="transactionDate" class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white">
                        </div>
                        <div x-data="{ selectedContact: '', contactName: '' }">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Ciro Edilecek Kişi/Firma</label>
                            <select name="contact_id" x-model="selectedContact" @change="contactName = $event.target.options[$event.target.selectedIndex].text" required class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white mb-2">
                                <option value="">Seçiniz...</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="endorser" :value="contactName">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="activeModal = null" class="px-4 py-2 text-slate-400 hover:text-white transition-colors">İptal</button>
                        <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-500 text-white rounded-lg">Ciro Et</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 4. Protesto Modal -->
        <div x-show="activeModal === 'protest'" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm" x-cloak>
            <div class="w-full max-w-md bg-[#0f172a] border border-white/10 rounded-2xl shadow-2xl p-6" @click.away="activeModal = null">
                <h3 class="text-xl font-bold text-white mb-4">Protesto Çek</h3>
                <p class="text-slate-400 mb-4">Bu senedi protestolu olarak işaretlemek üzeresiniz.</p>
                <form action="{{ route('accounting.promissory-notes.protest', $note) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">İşlem Tarihi</label>
                            <input type="date" name="transaction_date" x-model="transactionDate" class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="activeModal = null" class="px-4 py-2 text-slate-400 hover:text-white transition-colors">İptal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg">Onayla</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- İptal Modal -->
        <div x-show="activeModal === 'cancel'" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm" x-cloak>
            <div class="w-full max-w-md bg-[#0f172a] border border-white/10 rounded-2xl shadow-2xl p-6" @click.away="activeModal = null">
                <h3 class="text-xl font-bold text-white mb-4">Senedi İptal Et</h3>
                <p class="text-slate-400 mb-4">Bu senedi iptal etmek istediğinize emin misiniz? Bu işlem geri alınamaz.</p>
                <form action="{{ route('accounting.promissory-notes.cancel', $note) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">İşlem Tarihi</label>
                            <input type="date" name="transaction_date" x-model="transactionDate" class="w-full px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-white">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="activeModal = null" class="px-4 py-2 text-slate-400 hover:text-white transition-colors">İptal</button>
                        <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg">İptal Et</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
