<section>
    <header>
        <h2 class="text-xl font-bold text-white">
            Profil Bilgileri
        </h2>

        <p class="mt-2 text-sm text-slate-400">
            Hesabınızın profil bilgilerini ve e-posta adresini güncelleyebilirsiniz.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label class="input-label" for="name">İsim Soyisim</label>
            <input id="name" name="name" type="text" class="custom-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-xs" :messages="$errors->get('name')" />
        </div>

        <div>
            <label class="input-label" for="email">E-Posta Adresi</label>
            <input id="email" name="email" type="email" class="custom-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2 text-xs" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 rounded-xl bg-orange-500/10 border border-orange-500/20">
                    <p class="text-xs text-orange-400 font-medium">
                        E-posta adresiniz doğrulanmamış.
                        <button form="send-verification" class="ml-1 underline hover:text-orange-300">
                            Doğrulama e-postasını tekrar göndermek için buraya tıklayın.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-[10px] text-green-400 uppercase tracking-widest">
                            Yeni bir doğrulama bağlantısı e-posta adresinize gönderildi.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn-primary">DEĞİŞİKLİKLERİ KAYDET</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-bold text-green-400 uppercase tracking-widest"
                >KAYDEDİLDİ.</p>
            @endif
        </div>
    </form>
</section>
