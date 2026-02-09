<section class="space-y-6">
    <header>
        <h2 class="text-xl font-bold text-white">
            Hesabı Sil
        </h2>

        <p class="mt-2 text-sm text-slate-400">
            Hesabınız silindiğinde, tüm kaynakları ve verileri kalıcı olarak silinecektir. Hesabınızı silmeden önce lütfen saklamak istediğiniz tüm verileri veya bilgileri indirin.
        </p>
    </header>

    <button 
        class="btn-danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >HESABI KALICI OLARAK SİL</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="glass-card !bg-[#0f172a] !p-8 !rounded-none min-w-[400px]">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <h2 class="text-xl font-bold text-white">
                    Hesabınızı silmek istediğinizden emin misiniz?
                </h2>

                <p class="mt-4 text-sm text-slate-400">
                    Hesabınız silindiğinde, tüm verileriniz kalıcı olarak kaybolacaktır. Hesabınızı silmek için lütfen şifrenizi girerek işlemi onaylayın.
                </p>

                <div class="mt-6">
                    <label class="input-label" for="password">Şifre</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="custom-input"
                        placeholder="Şifrenizi doğrulayın"
                    />

                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-xs" />
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" class="px-6 py-2.5 rounded-lg text-xs font-bold text-slate-400 hover:text-white transition-colors uppercase tracking-widest" x-on:click="$dispatch('close')">
                        İPTAL
                    </button>

                    <button type="submit" class="btn-danger">
                        HESABI SİL
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</section>
