<section>
    <header>
        <h2 class="font-display text-xl font-bold text-ink">Ganti kata sandi</h2>
        <p class="mt-1 text-sm text-muted">Gunakan kata sandi yang kuat dan tidak digunakan pada layanan lain.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 grid gap-5 sm:grid-cols-2">
        @csrf
        @method('put')
        <div class="sm:col-span-2">
            <x-input-label for="update_password_current_password" value="Kata sandi saat ini" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>
        <div>
            <x-input-label for="update_password_password" value="Kata sandi baru" />
            <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>
        <div>
            <x-input-label for="update_password_password_confirmation" value="Konfirmasi kata sandi" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>
        <div class="flex items-center gap-4 border-t border-slate-200 pt-5 dark:border-slate-700 sm:col-span-2">
            <x-primary-button>Simpan kata sandi</x-primary-button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Kata sandi tersimpan.</p>
            @endif
        </div>
    </form>
</section>
