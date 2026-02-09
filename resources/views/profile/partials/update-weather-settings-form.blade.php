<section>
    <header>
        <h2 class="text-lg font-black text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-400">wb_cloudy</span>
            {{ __('Hava Durumu Ayarları') }}
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            {{ __("Dashboard'da görüntülenecek şehir bilgisini güncelleyin.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.weather.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="city" :value="__('Şehir')" />
            <x-text-input 
                id="city" 
                name="city" 
                type="text" 
                class="mt-1 block w-full bg-slate-900 border-white/10 text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500" 
                :value="old('city', auth()->user()->company?->settings['city'] ?? '')" 
                required 
                autofocus 
                autocomplete="city" 
                placeholder="Örn: Istanbul, Ankara, İzmir" 
            />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
            
            <p class="mt-2 text-xs text-slate-500">
                <span class="material-symbols-outlined text-[12px] align-middle">info</span>
                Şehir adını Türkçe veya İngilizce olarak girebilirsiniz.
            </p>
        </div>



        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Kaydet') }}</x-primary-button>

            @if (session('weather-updated'))
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400 font-bold flex items-center gap-1"
                >
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    {{ __('Kaydedildi.') }}
                </p>
            @endif
        </div>
    </form>
</section>
