<x-app-layout>
    <!-- Main Background Wrapper with Gradient -->
    <div class="min-h-screen bg-gradient-to-br from-slate-800 via-slate-900 to-zinc-900 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
            
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('manufacturing.index') }}" class="text-blue-300 hover:text-white transition-colors text-sm font-medium flex items-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Listeye Dön
                </a>
                <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-indigo-400 drop-shadow-lg">
                    Yeni İş Emri Oluştur
                </h1>
                <p class="text-slate-400 mt-1">Üretime başlamak için yeni bir iş emri kaydı açın.</p>
            </div>

            <!-- Form Card -->
            <div class="relative rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xl shadow-2xl p-8">
                <div class="absolute inset-0 bg-blue-500/5 rounded-2xl blur-3xl -z-10"></div>
                
                <form action="{{ route('manufacturing.store') }}" method="POST">
                    @csrf
                    
                    <!-- Product Selection -->
                    <div class="mb-6">
                        <label for="product_id" class="block text-sm font-medium text-slate-300 mb-2">Ürün Seçimi</label>
                        <select id="product_id" name="product_id" class="w-full bg-slate-800/50 border border-white/10 rounded-xl text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all placeholder-slate-500">
                            <option value="" disabled selected>Üretilecek ürünü seçiniz...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->code }} - {{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Responsible Person -->
                    <div class="mb-6">
                        <label for="employee_id" class="block text-sm font-medium text-slate-300 mb-2">Sorumlu Personel</label>
                        <select id="employee_id" name="employee_id" class="w-full bg-slate-800/50 border border-white/10 rounded-xl text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all placeholder-slate-500">
                            <option value="" selected>Personel Seçiniz (Opsiyonel)</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity & Dates Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                         <!-- Quantity -->
                         <div>
                            <label for="quantity" class="block text-sm font-medium text-slate-300 mb-2">Miktar</label>
                            <input type="number" id="quantity" name="quantity" min="1" placeholder="0" class="w-full bg-slate-800/50 border border-white/10 rounded-xl text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all placeholder-slate-500">
                            @error('quantity')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                         <!-- Start Date -->
                         <div>
                            <label for="start_date" class="block text-sm font-medium text-slate-300 mb-2">Başlangıç Tarihi</label>
                            <input type="date" id="start_date" name="start_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-800/50 border border-white/10 rounded-xl text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all placeholder-slate-500 [color-scheme:dark]">
                            @error('start_date')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                         <!-- Due Date -->
                         <div>
                            <label for="due_date" class="block text-sm font-medium text-slate-300 mb-2">Teslim Tarihi</label>
                            <input type="date" id="due_date" name="due_date" class="w-full bg-slate-800/50 border border-white/10 rounded-xl text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all placeholder-slate-500 [color-scheme:dark]">
                            @error('due_date')
                                <p class="text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-8">
                        <label for="notes" class="block text-sm font-medium text-slate-300 mb-2">Notlar / Açıklama</label>
                        <textarea id="notes" name="notes" rows="4" placeholder="Eklemek istediğiniz notlar..." class="w-full bg-slate-800/50 border border-white/10 rounded-xl text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all placeholder-slate-500"></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-4 border-t border-white/5 pt-6">
                        <a href="{{ route('manufacturing.index') }}" class="px-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 border border-white/5 transition-all duration-300 text-sm font-medium">
                            İptal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white border border-blue-400/30 shadow-lg shadow-blue-500/30 transition-all duration-300 text-sm font-medium">
                            İş Emrini Oluştur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
