<x-guest-layout>
    <!-- Branding Header -->
    <div class="mb-10 flex flex-col items-center">
        <div class="mb-6">
            <img src="{{ asset('images/logo.png') }}" alt="Erpovy" class="h-20 w-auto">
        </div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">Hesap Oluştur</h1>
        <p class="text-slate-400 text-sm font-medium">Yeni bir şirket profili ile hemen başlayın</p>
    </div>

    <!-- Register Form -->
    <form method="POST" action="{{ route('register') }}" class="mt-8">
        @csrf

        <!-- Name -->
        <div class="input-group">
            <label class="input-label" for="name">İsim Soyisim</label>
            <div class="input-wrapper">
                <span class="material-symbols-outlined input-icon">badge</span>
                <input id="name" class="custom-input" type="text" name="name" value="{{ old('name') }}" placeholder="Ad Soyad giriniz" required autofocus />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-[10px]" />
        </div>

        <!-- Company Name -->
        <div class="input-group">
            <label class="input-label" for="company_name">Şirket Adı</label>
            <div class="input-wrapper">
                <span class="material-symbols-outlined input-icon">corporate_fare</span>
                <input id="company_name" class="custom-input" type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Şirket adını giriniz" required />
            </div>
            <x-input-error :messages="$errors->get('company_name')" class="mt-2 text-[10px]" />
        </div>

        <!-- Email -->
        <div class="input-group">
            <label class="input-label" for="email">E-posta Adresi</label>
            <div class="input-wrapper">
                <span class="material-symbols-outlined input-icon">alternate_email</span>
                <input id="email" class="custom-input" type="email" name="email" value="{{ old('email') }}" placeholder="E-posta adresinizi giriniz" required />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-[10px]" />
        </div>

        <!-- Password -->
        <div class="input-group">
            <label class="input-label" for="password">Şifre</label>
            <div class="input-wrapper">
                <span class="material-symbols-outlined input-icon">lock</span>
                <input id="password" class="custom-input" type="password" name="password" placeholder="••••••••" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-[10px]" />
        </div>

        <!-- Confirm Password -->
        <div class="input-group">
            <label class="input-label" for="password_confirmation">Şifre Tekrar</label>
            <div class="input-wrapper">
                <span class="material-symbols-outlined input-icon">lock_reset</span>
                <input id="password_confirmation" class="custom-input" type="password" name="password_confirmation" placeholder="••••••••" required />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-[10px]" />
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary uppercase tracking-widest mt-4">
            Kayıt Ol ve Başla
        </button>

        <!-- Footer -->
        <div class="mt-8 text-[13px] text-slate-400 font-medium text-center">
            Zaten bir hesabınız var mı? 
            <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Giriş Yap</a>
        </div>
    </form>
</x-guest-layout>
