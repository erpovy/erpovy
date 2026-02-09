<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Yeni Fatura Şablonu') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6">
        <form action="{{ route('accounting.invoice-templates.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Editor -->
                <!-- Left: Editor -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="p-6 bg-slate-800 rounded-lg border border-slate-700">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Şablon Adı <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required class="w-full bg-slate-900 text-slate-200 border-slate-700 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-300 mb-2">HTML İçeriği</label>
                            <textarea name="html_content" id="html_content" rows="20" class="w-full bg-slate-900 text-slate-200 border-slate-700 rounded-lg font-mono text-sm p-4 focus:ring-indigo-500 focus:border-indigo-500">{{ old('html_content', $defaultContent) }}</textarea>
                            <p class="mt-2 text-xs text-slate-500">
                                Dinamik değişkenler: 
                                <code class="bg-black/30 px-1 py-0.5 rounded text-indigo-400">@{{ $invoice->invoice_number }}</code>,
                                <code class="bg-black/30 px-1 py-0.5 rounded text-indigo-400">@{{ $invoice->issue_date }}</code>,
                                <code class="bg-black/30 px-1 py-0.5 rounded text-indigo-400">@{{ $invoice->contact->name }}</code>
                            </p>
                        </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Settings & Actions -->
                <div class="space-y-6">
                    <div class="p-6 bg-slate-800 rounded-lg border border-slate-700">
                        <div class="flex items-center gap-3 mb-6">
                            <input type="checkbox" name="is_default" id="is_default" value="1" class="rounded bg-slate-800 border-slate-600 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_default" class="text-sm text-slate-300">Varsayılan Şablon Yap</label>
                        </div>
                        
                        <div class="flex flex-col gap-3">
                            <button type="button" onclick="previewTemplate()" class="w-full bg-slate-700 hover:bg-slate-600 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">visibility</span>
                                Önizle
                            </button>
                            
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">save</span>
                                Kaydet
                            </button>
                            
                            <a href="{{ route('accounting.invoice-templates.index') }}" class="w-full bg-transparent hover:bg-white/5 text-slate-400 hover:text-white font-bold py-2 px-4 rounded-lg transition-colors text-center border border-slate-700">
                                İptal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closePreview()"></div>

            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Şablon Önizleme</h3>
                        <button type="button" onclick="closePreview()" class="text-gray-400 hover:text-gray-500">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    <div class="border rounded-lg p-4 bg-gray-50 min-h-[500px]">
                        <iframe id="previewFrame" class="w-full h-[600px] border-0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewTemplate() {
            const htmlContent = document.getElementById('html_content').value;
            const token = document.querySelector('input[name="_token"]').value;
            
            fetch('{{ route("accounting.invoice-templates.preview") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ html_content: htmlContent })
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    const modal = document.getElementById('previewModal');
                    const frame = document.getElementById('previewFrame');
                    
                    frame.srcdoc = data.html;
                    modal.classList.remove('hidden');
                } else if (data.error) {
                    alert('Önizleme hatası: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Bir hata oluştu.');
            });
        }
        
        function closePreview() {
            document.getElementById('previewModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
