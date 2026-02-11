<x-guest-layout>
    <!-- Branding Header -->
    <div class="mb-10 flex flex-col items-center">
        <div class="mb-6">
            @php $logoDark = \App\Models\Setting::get('logo_dark'); @endphp
            <img src="{{ $logoDark ? (str_starts_with($logoDark, 'http') ? $logoDark : asset($logoDark)) : asset('images/logo.png') }}" alt="Erpovy" class="h-20 w-auto">
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">Hoş Geldiniz</h1>
        <p class="text-slate-400 text-sm font-medium">Devam etmek için hesabınıza giriş yapın</p>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="mt-8">
        @csrf

        <!-- Email -->
        <div class="input-group">
            <label class="input-label" for="email">E-posta Adresi</label>
            <div class="input-wrapper">
                <span class="material-symbols-outlined input-icon">alternate_email</span>
                <input id="email" class="custom-input" type="email" name="email" value="{{ old('email') }}" placeholder="E-posta adresinizi giriniz" required autofocus />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px]" />
        </div>

        <!-- Password -->
        <div class="input-group">
            <label class="input-label" for="password">Şifre</label>
            <div class="input-wrapper">
                <span class="material-symbols-outlined input-icon">lock</span>
                <input id="password" class="custom-input" type="password" name="password" placeholder="••••••••" required autocomplete="current-password" />
                <button type="button" class="absolute right-4 text-slate-500 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-[10px]" />
        </div>

        <!-- Options -->
        <div class="flex items-center justify-between mb-8">
            <label class="flex items-center cursor-pointer group" style="text-transform: none !important; margin-bottom: 0 !important;">
                <input id="remember_me" type="checkbox" name="remember" class="hidden">
                <div class="w-4 h-4 rounded border border-slate-700 bg-slate-900/50 flex items-center justify-center transition-all group-hover:border-primary">
                    <span class="material-symbols-outlined text-[12px] text-primary scale-0 transition-transform" id="check-icon">check</span>
                </div>
                <span class="ml-2 text-[12px] text-slate-400 font-medium group-hover:text-slate-300 transition-colors">Beni Hatırla</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-[12px] text-primary font-bold hover:underline" href="{{ route('password.request') }}">
                    Şifremi Unuttum?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary uppercase tracking-widest">
            Giriş Yap
        </button>

        <!-- Footer -->
        <div class="mt-8 text-[13px] text-slate-400 font-medium">
            Hesabınız yok mu? 
            <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Kayıt Ol</a>
        </div>
    </form>

    <script>
        // Custom Checkbox Logic
        const checkbox = document.getElementById('remember_me');
        const checkIcon = document.getElementById('check-icon');
        
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                checkIcon.classList.remove('scale-0');
                checkIcon.classList.add('scale-100');
            } else {
                checkIcon.classList.remove('scale-100');
                checkIcon.classList.add('scale-0');
            }
        });
    </script>
</x-guest-layout>
