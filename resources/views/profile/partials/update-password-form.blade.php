<section>
    <header>
        <h2 class="text-xl font-bold text-white">
            Şifreyi Güncelle
        </h2>

        <p class="mt-2 text-sm text-slate-400">
            Hesabınızın güvenliğini korumak için uzun ve rastgele bir şifre kullandığınızdan emin olun.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('put')

        <div>
            <label class="input-label" for="update_password_current_password">Mevcut Şifre</label>
            <input id="update_password_current_password" name="current_password" type="password" class="custom-input" autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs" />
        </div>

        <div>
            <label class="input-label" for="update_password_password">Yeni Şifre</label>
            <input id="update_password_password" name="password" type="password" class="custom-input" autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs" />
        </div>

        <div>
            <label class="input-label" for="update_password_password_confirmation">Yeni Şifre Tekrar</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="custom-input" autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn-primary">ŞİFREYİ KAYDET</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-bold text-green-400 uppercase tracking-widest"
                >DEĞİŞİKLİKLER KAYDEDİLDİ.</p>
            @endif
        </div>
    </form>
</section>
