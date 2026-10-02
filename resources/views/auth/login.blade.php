@section('title', 'Masuk Admin/Seller | BizDev HMPS MI Polmed')

<x-guest-layout>
    <div class="mb-8">
        <x-badge variant="primary">Portal internal</x-badge>
        <h1 class="mt-4 font-display text-3xl font-bold tracking-tight text-ink">Selamat datang kembali</h1>
        <p class="mt-2 text-sm leading-6 text-muted">Masuk untuk mengelola katalog dan usaha mahasiswa.</p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@kampus.ac.id" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Kata sandi" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <label for="remember_me" class="flex min-h-11 items-center gap-3 text-sm text-muted">
            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-secondary" name="remember">
            <span>Ingat saya</span>
        </label>

        <x-primary-button class="w-full justify-center py-3">Masuk ke dashboard <span aria-hidden="true">→</span></x-primary-button>
    </form>

    <p class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs leading-5 text-amber-900 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-200">
        Akun seller yang dinonaktifkan tidak dapat masuk. Hubungi admin untuk memeriksa status akun atau mereset kata sandi.
    </p>
</x-guest-layout>
